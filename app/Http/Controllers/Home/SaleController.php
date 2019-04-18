<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\join;
use App\Model\bank;
use App\Model\join_order;
use App\Model\log_point_up;
use App\Model\log_point_join;
use App\Model\log_price_join;
use App\Model\log_point_spring;
use App\Model\log_rtsh_join;
use App\Model\log_point_open;
use App\Model\admin_join_order;
use App\Model\log_point_user;
use App\Model\rtsh_order;
use Log;

class SaleController extends Controller
{
    public function transfer()
    {
    	// 查询出合伙人的积分余额
    	$sale = join::where('uuid',session('join')->uuid)
        	->where('type','1')
        	->where('status','1')
        	->first();
    	if ($sale) {
    		return view('home.sale-transfer',compact('sale'));
    	}else{
    		dd('身份验证失败');
    	}
    }

    public function selectSaleRecharge($phone, $point)
    {
        $user = User::where('user.user_phone',$phone)
            ->leftJoin('join','join.uuid','=','user.user_uuid')
            ->where('join.type','!=','0')
            ->select('user.*')
            ->first();
        if ($user) {
            $join = join::where('uuid',session('join')->uuid)
                ->where('type','!=','0')
                ->first();
            if ($join) {
                if (($join->point + $join->point_give + $join->point_fund) < $point) {
                    $result = $this->result('fail','当前积分不足,请降低转账金额!');
                }else{
                    $result = $this->result('success','获取合伙人信息成功!',$user);
                }
            }else{
                $result = $this->result('fail','当前登录状态过期,请重新登录!');
            }
        }else{
            $result = $this->result('fail','未查找到当前用户,或者当前用户不是合伙人!');
        }
        return $result;
    }

    public function handleRecharge()
    {
        $input = Input::all();
        // dd($input);
        if (session('join')) {
            $join = join::where('uuid', session('join')->uuid)
                ->first();
            $info = User::where('user_uuid',session('join')->uuid)
                ->first();
            if ($info->cash_password) {
                // 核对支付密码
                if (Crypt::decrypt($info->cash_password) == $input['password']) {
                    $sale = User::where('user.user_phone',$input['phone'])
                        ->leftJoin('join','join.uuid','=','user.user_uuid')
                        ->where('join.type','!=','0')
                        ->select('join.*')
                        ->first();
                    if (!$sale->type) {
                        $result = $this->result('fail','只支持为合伙人转账!');
                    } else {
                        // 扣除积分
                        // 加盟商扣除后的积分
                        // 优先扣除返佣和赠送的积分
                        if ($join->point_fund > $input['point']) {
                            $joinNewPoint = $join->point;
                            $joinNewPointGive = $join->point_give;
                            $joinNewPointFund = $join->point_fund - $input['point'];
                        }else{
                            $joinNewPoint = ($join->point_fund + $join->point) - $input['point'];
                            $joinNewPointGive = $join->point_give;
                            $joinNewPointFund = 0;
                        }
                        // 为合伙人增加积分
                        $newSalePoint = $sale->point + $input['point'];

                        // 记录用户添加的积分;
                        $logSale = new log_point_join;
                        $logSale->uuid = $sale->uuid;
                        $logSale->point = $sale->point;
                        $logSale->new_point = $newSalePoint;
                        $logSale->type = 14;
                        $logSale->point_give = $sale->point_give;
                        $logSale->new_point_give = $sale->point_give;
                        $logSale->point_fund = $sale->point_fund;
                        $logSale->new_point_fund = $sale->point_fund;
                        $logSale->to = $join->uuid;
                        $logSale->status = 1;
                        $logSale->add = 1;

                        $order = new join_order;
                        $order->num = "zz".date('YmdHis').rand('10000','99999');
                        $order->uuid = $join->uuid;
                        $order->to = $sale->uuid;
                        $order->type = 4;
                        $order->status = 1;
                        $order->point = $input['point'];

                        // 记录加盟商日志
                        $log = new log_point_join;
                        $log->uuid = $join->uuid;
                        $log->point = $join->point;
                        $log->new_point = $joinNewPoint;
                        $log->point_give = $join->point_give;
                        $log->new_point_give = $joinNewPointGive;
                        $log->point_fund = $join->point_fund;
                        $log->new_point_fund = $joinNewPointFund;
                        $log->status = 1;
                        $log->type = 13;
                        $log->to = $sale->uuid;
                        $log->add = 2;
                        if ($joinNewPoint >= 0) {
                            DB::beginTransaction();
                            try{
                                $order->save();
                                $log->save();
                                $logSale->save();
                                join::where('uuid',$sale->uuid)
                                    ->update(['point'=>$newSalePoint]);
                                join::where('uuid',$join->uuid)
                                    ->update(['point'=>$joinNewPoint,'point_give'=>$joinNewPointGive,'point_fund'=>$joinNewPointFund]);
                                DB::commit();
                                $result = $this->result('success','积分转账成功,当前剩余: ('.$joinNewPoint.' 基本积分),('.$joinNewPointGive.' 赠送积分),('.$joinNewPointFund.' 返佣积分)');
                            }catch (\Exception $e) {
                                //接收异常处理并回滚
                                DB::rollBack();
                                $result = $this->result('fail','ERROR!当前系统繁忙,购买失败!');
                            }
                        }else{
                            $result = $this->result('fail','您的合伙人账户积分不足以抵扣!');
                        }
                    }
                }else{
                    $result = $this->result('fail','您输入的支付密码不正确!');
                }
            }else{
                $result = $this->result('fail','请您先设置提现密码(用户中心->个人信息设置)!');
            }
        }else{
            $result = $this->result('fail','当前登录状态过期,请重新登录!');
        }
        return $result;
    }

    public function pageAdd()
    {
        // 查询出合伙人的积分余额
        $sale = join::where('uuid',session('join')->uuid)
        ->where('type','1')
        ->where('status','1')
        ->first();
        if ($sale) {
            return view('home.sale-add',compact('sale'));
        }else{
            dd('身份验证失败');
        }
    }

    public function selectAdd($phone)
    {
        $user = User::where('user_phone',$phone)->first();
        if ($user) {
            $join = join::where('uuid',$user->user_uuid)->first();
            if ($join) {
                $result = $this->result('fail','该用户已经是加盟商,不能开通合伙人.');
            }else{
                $result = $this->result('success','获取数据成功!',$user);
            }
        }else{
            $result = $this->result('fail','未查询到用户,请检查输入的电话号码!');
        }
        return $result;
    }

    public function addSale()
    {
        $input = Input::all();
        if (session('join')) {
            $join = join::where('uuid', session('join')->uuid)
                ->first();
            $info = User::where('user_uuid',session('join')->uuid)
                ->first();
            if ($info->cash_password) {
                // 核对支付密码
                if (Crypt::decrypt($info->cash_password) == $input['password']) {
                    $user = User::where('user_phone',$input['phone'])->first();
                    if ($user->join_buy) {
                        $joinBuy = $user->jon_buy;
                    }else{
                        $joinBuy = session('join')->uuid;
                    }
                    if (join::where('uuid',$user->user_uuid)->first()) {
                        $result = $this->result('fail','该用户已经是合伙人或者加盟商!');
                    }else{
                        // 新建join数据列
                        $sale = new join;
                        $sale->uuid = $user->user_uuid;
                        $sale->pid = $user->join_pid;
                        $sale->point = 100000;
                        $sale->point_give = 0;
                        $sale->point_fund = 0;
                        $sale->status = 1;
                        $sale->protocol = 1;
                        $sale->password = 'eyJpdiI6ImpiRDM0cXF0QXBDYnh0QlhzZkpoY2c9PSIsInZhbHVlIjoiaU1NWnVxd3RXSmt3a2pBVkV2cWJMUT09IiwibWFjIjoiZDAzZDBkYTkyNmE4MTQ5N2E5ZmE2ZWJjZTRjNzZhOGYyZDFiYmVhODY2YjlmYTY5ZGU1YTVhMjRlZWZjNTM0ZSJ9';
                        $sale->price = 0;
                        $sale->join_cash = 0;
                        $sale->type = 1;

                        // 扣除上级合伙人的积分
                        // 优先扣除返佣和赠送的积分
                        if ($join->point_fund > 100000) {
                            $joinNewPoint = $join->point;
                            $joinNewPointGive = $join->point_give;
                            $joinNewPointFund = $join->point_fund - 100000;
                        }else{
                            if (($join->point_fund + $join->point_give) > 100000) {
                                $joinNewPoint = $join->point;
                                $joinNewPointGive = ($join->point_fund + $join->point_give) - 100000;
                                    $joinNewPointFund = 0;
                            }else{
                                $joinNewPoint = ($join->point_fund + $join->point_give + $join->point) - 100000;
                                $joinNewPointGive = 0;
                                $joinNewPointFund = 0;
                            }
                        }

                        //  写入订单日志
                        $order = new join_order;
                        $order->num = "jx".date('YmdHis').rand('10000','99999');
                        $order->uuid = $join->uuid;
                        $order->to = $sale->uuid;
                        $order->type = 3;
                        $order->status = 1;
                        $order->point = 100000;

                        // 记录开通合伙人日志
                        $log = new log_point_join;
                        $log->uuid = $join->uuid;
                        $log->point = $join->point;
                        $log->point_give = $join->point_give;
                        $log->new_point = $joinNewPoint;
                        $log->new_point_give = $joinNewPointGive;
                        $log->point_fund = $join->point_fund;
                        $log->new_point_fund = $joinNewPointFund;
                        $log->status = 1;
                        $log->type = 15;
                        $log->add = 2;

                        // 记录成为合伙人日志
                        $logSale = new log_point_join;
                        $logSale->uuid = $user->user_uuid;
                        $logSale->point = 0;
                        $logSale->point_give = 0;
                        $logSale->new_point = 100000;
                        $logSale->new_point_give = 0;
                        $logSale->point_fund = 0;
                        $logSale->new_point_fund = 0;
                        $logSale->status = 1;
                        $logSale->type = 16;
                        $logSale->add = 1;
                        if ($joinNewPoint >= 0) {
                            DB::beginTransaction();
                            try{
                                $log->save();
                                $logSale->save();
                                $order->save();
                                $sale->save();
                                //
                                //
                                //
                                // 增加提成
                                //
                                //
                                //
                                // User::where('user_uuid',$user->user_uuid)
                                //         ->update([
                                //             'user_rank'=>4,
                                //             'join_buy'=>$joinBuy,
                                //             'rank_start'=>time()
                                //         ]);
                                join::where('uuid',$join->uuid)
                                    ->update(['point'=>$joinNewPoint,'point_give'=>$joinNewPointGive,'point_fund'=>$joinNewPointFund]);
                                DB::commit();
                                $result = $this->result('success','开通合伙人成功!,当前剩余: ('.$joinNewPoint.' 基本积分),('.$joinNewPointGive.' 赠送积分),('.$joinNewPointFund.' 返佣积分)');
                            }catch (\Exception $e) {
                                //接收异常处理并回滚
                                DB::rollBack();
                                $result = $this->result('fail','ERROR!当前系统繁忙,开通失败!');
                            }
                        }else{
                            $result = $this->result('fail','您的合伙人账户积分不足以抵扣!');
                        }

                    }
                }else{
                    $result = $this->result('fail','您输入的支付密码不正确!');
                }
            }else{
                $result = $this->result('fail','请您先设置提现密码(用户中心->个人信息设置)!');
            }
        }else{
            $result = $this->result('fail','当前登录状态过期,请重新登录!');
        }
        return $result;
    }

    public function userCount()
    {
        // 构造合伙人树状图
        $me = User::where('join_pid',session('join')->uuid)->get()->count();
        $result = $this->result('success','成功!',$me);
        return $result;
    }

    public function userCountTop()
    {
        $sale = join::where('type','1')
            ->get();
        $data = 0;
        foreach ($sale as $key => $value) {
            $data += User::where('join_pid',$value->uuid)->get()->count();
        }
        $result = $this->result('success','成功!',$data);
        return $result;
    }
    public function saleCount()
    {
        $me = join::where('pid',session('join')->uuid)
            ->where('type','1')
            ->get()
            ->count();
        $result = $this->result('success','成功!',$me);
        return $result;
    }

    public function saleList($uuid)
    {
        // 查询所属的合伙人
        $sale = join::where('join.pid',$uuid)
            ->where('join.type','1')
            ->leftJoin('user','join.uuid','=','user.user_uuid')
            ->get();
        if ($sale->isNotEmpty()) {
            foreach ($sale as $key => $value) {
                $value['count_user_free'] = User::where('join_pid',$value->user_uuid)
                    ->where('user_rank','0')
                    ->get()->count();
                $value['count_user_pay'] = User::where('join_pid',$value->user_uuid)
                    ->where('user_rank','>','0')
                    ->get()->count();
                $value['count_sale'] = join::where('join.pid',$value->user_uuid)
                    ->where('join.type','1')
                    ->leftJoin('user','join.uuid','=','user.user_uuid')
                    ->get()->count();
                $value['user_open'] = log_point_open::where('sale',$value->user_uuid)
                    ->where('end','0')
                    ->where('status','1')
                    ->get()->count();
                $data[] = $value;
            }
        }else{
            $data = array();
        }

        $info = User::where('user_uuid',$uuid)
            ->first();
        // dd($sale);
        return view('home.sale-list',compact('sale','data','info'));
    }

    public function openPage()
    {
        // 查询出积分,可以给用户充值开拓积分
        $sale = join::where('uuid',session('join')->uuid)
            ->where('type','1')
            ->first();
        return view('home.sale-open',compact('sale'));
    }

    public function selectOpenUser($phone)
    {
        $user = User::where('user_phone',$phone)->first();
        if ($user) {
            if ($user->user_rank > 1) {
                $result = $this->result('fail','该用户已经购买过会籍,不能充值开拓积分.');
            }else{
                $join = join::where('uuid',$user->user_uuid)->first();
                if ($join) {
                    $result = $this->result('fail','该用户已经是加盟商,不能充值开拓积分.');
                }else{
                    $result = $this->result('success','获取数据成功!',$user);
                }
            }
        }else{
            $result = $this->result('fail','未查询到用户,请检查输入的电话号码!');
        }
        return $result;
    }

    public function openRecharge()
    {
        $input = Input::all();
        // 检查是否符合充值条件
        $user = User::where('user_phone',$input['phone'])
            ->where('user_rank','<','2')->first();
        $join = join::where('uuid',session('join')->uuid)->first();
        if ($join) {
            $newJoin = $join->point_open - $input['point_open'];
            $newUser = $user->user_point_open + $input['point_open'];
            $info = User::where('user_uuid',$join->uuid)->first();
            if ($info->cash_password) {
                if (Crypt::decrypt($info->cash_password) == $input['password']) {
                    if ($user) {
                        // 检查是否已经充值过
                        $recharge = log_point_open::where('uuid',$user->user_uuid)->first();
                        if ($recharge) {
                            $result = $this->result('fail','当前用户已经充值过开拓积分,无法再次进行充值!');
                        }else{
                            if ($newJoin < 0) {
                                    $result = $this->result('fial','充值开拓积分失败,当前账户余额不足.');
                            }else{
                                // 进行充值动作
                                DB::beginTransaction();
                                    try{
                                        // 添加用户积分
                                        User::where('user_uuid',$user->user_uuid)
                                            ->update([
                                                "user_point_open"=>$newUser
                                            ]);
                                        // 扣减经销商积分
                                        join::where('uuid',session('join')->uuid)
                                            ->update([
                                                "point_open"=>$newJoin
                                            ]);
                                        // 记录到用户积分日志
                                        $userLog = new log_point_user;
                                        $userLog->uuid = $user->user_uuid;
                                        $userLog->point_open = $user->user_point_open;
                                        $userLog->new_point_open = $newUser;
                                        $userLog->type = 17;
                                        $userLog->status = 1;
                                        $userLog->add = 1;
                                        $userLog->save();
                                        // 记录经销商扣分日志
                                        $joinLog = new log_point_join;
                                        $joinLog->uuid = session('join')->uuid;
                                        $joinLog->point_open = $join->point_open;
                                        $joinLog->new_point_open = $newJoin;
                                        $joinLog->type = 24;
                                        $joinLog->status = 1;
                                        $joinLog->to = $user->user_uuid;
                                        $joinLog->add = 2;
                                        $joinLog->save();

                                        $log = new log_point_open;
                                        $log->uuid = $user->user_uuid;
                                        $log->point_open = $input['point_open'];
                                        $log->type = 1;
                                        $log->new_point_open = $newUser;
                                        $log->status = 1;
                                        $log->time = date("Y-m-d",strtotime("+".$input['time']." month"));
                                        $log->end = 0;
                                        $log->sale = session('join')->uuid;
                                        $log->save();
                                        DB::commit();
                                        $result = $this->result('success','充值积分成功!');
                                    }catch (\Exception $e) {
                                    //接收异常处理并回滚
                                    DB::rollBack();
                                    $result = $this->result('fail','ERROR!当前系统繁忙,充值失败!');
                                    }
                            }
                        }
                    }else{
                        $result = $this->result('fail','充值失败,该用户不符合充值条件!');
                    }
                }else{
                    $result = $this->result('fail','您输入的支付密码不正确!');
                }
            }else{
                $result = $this->result('fail','请您先设置提现密码(用户中心->个人信息设置)!');
            }
        }else{
            $result = $this->result('fail','转换失败,请重新登录!');
        }
        return $result;
    }

    public function openChange()
    {
        $input = Input::all();
        $sale = join::where('uuid',session('join')->uuid)->first();
        if ($sale) {
            $info = User::where('user_uuid',$sale->uuid)->first();
            if ($info->cash_password) {
                if (Crypt::decrypt($info->cash_password) == $input['password']) {
                    if (($sale->point + $sale->point_give) >= $input['point']) {
                        if ($sale->point_give >= $input['point']) {
                            $newPoint = $sale->point;
                            $newPointGive = $sale->point_give - $input['point'];
                            $newPointOpen = $sale->point_open + $input['point'];
                        }else{
                            $newPoint = $sale->point + $sale->point_give - $input['point'];;
                            $newPointGive = 0;
                            $newPointOpen = $sale->point_open + $input['point'];
                        }
                        DB::beginTransaction();
                        try{
                            join::where('uuid',$sale->uuid)->update([
                                "point"=>$newPoint,
                                "point_give"=>$newPointGive,
                                "point_open"=>$newPointOpen
                            ]);
                            // 添加用户积分
                            $joinLog = new log_point_join;
                            $joinLog->uuid = session('join')->uuid;
                            $joinLog->point = $sale->point;
                            $joinLog->point_give = $sale->point_give;
                            $joinLog->new_point = $newPoint;
                            $joinLog->new_point_give = $newPointGive;
                            $joinLog->type = 25;
                            $joinLog->status = 1;
                            $joinLog->to = session('join')->uuid;
                            $joinLog->add = 2;
                            $joinLog->save();

                            $log = new log_point_join;
                            $log->uuid = session('join')->uuid;
                            $log->point_open = $sale->point_open;
                            $log->new_point_open = $newPointOpen;
                            $log->type = 25;
                            $log->status = 1;
                            $log->to = session('join')->uuid;
                            $log->add = 1;
                            $log->save();
                            DB::commit();
                            $result = $this->result('success','开拓积分转换成功!');
                        }catch (\Exception $e) {
                            //接收异常处理并回滚
                            DB::rollBack();
                            $result = $this->result('fail','ERROR!当前系统繁忙,转换失败!');
                        }
                    }else{
                        $result = $this->result('fail','当前积分不足,请重新填写数值!');
                    }
                }else{
                    $result = $this->result('fail','您输入的支付密码不正确!');
                }
            }else{
                $result = $this->result('fail','请您先设置提现密码(用户中心->个人信息设置)!');
            }
        }else{
            $result = $this->result('fail','转换失败,请重新登录!');
        }
        return $result;
    }

    public function openList($uuid)
    {
        $info = User::where('user_uuid',$uuid)->first();
        $data = log_point_open::orderBy('log_point_open.id','ASC')
            ->where('log_point_open.sale',$uuid)
            ->leftJoin('user','user.user_uuid','=','log_point_open.uuid')
            ->where('log_point_open.status','1')
            ->select('log_point_open.*','user.user_pic','user.user_name','user.user_phone','user.user_nickname','user.user_point_open')
            ->get();
        return view('home.sale-open-list',compact('info','data'));
    }
}



























