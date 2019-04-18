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
use App\Model\log_point_open;
use App\Model\log_rtsh_join;
use App\Model\admin_join_order;
use App\Model\log_point_user;
use App\Model\rtsh_order;
use Log;
class JoinController extends Controller
{
    public function isLogin()
    {
        // session(['join'=>'']);
        if (!empty(session('join'))) {
            $result = $this->result('success','成功!');
        }else{
            $result = $this->result('fail','未登录!');
        }
        return $result;
    }
    public function isJoin()
    {
        // dd(session('smsCode'));
    	$input = Input::all();
        if (isset($input['password'])) {
            // 用电话号码查询到用户身份
            $join = User::where('user.user_phone',$input['phone'])
                    ->leftJoin('join','user.user_uuid','=','join.uuid')
                    ->where('join.status','1')
                    ->select('join.*')
                    ->first();
            if ($join) {
                // dd(Crypt::decrypt($join->password));
                if (Crypt::decrypt($join->password) == $input['password']) {
                    session(['join'=>$join]);
                    $result = $this->result('success','登录成功!');
                }else{
                    $result = $this->result('fail','密码错误,请重新输入!');
                }
            }else{
                $result = $this->result('fail','该用户不是加盟商!');
            }
        }else{
            // 验证码登录
            if (session('smsCode') == $input['code']) {
                $join = User::where('user.user_phone',$input['phone'])
                    ->leftJoin('join','user.user_uuid','=','join.uuid')
                    ->select('user.*','join.*')
                    ->first();
                if ($join->uuid) {
                    session(['join'=>$join]);
                    $result = $this->result('success','登录成功!');
                }else{
                    $result = $this->result('fail','该用户不是加盟商!');
                }
            }else{
                $result = $this->result('fail','验证码错误,请重新输入!');
            }
        }
        return $result;
    }

    public function index()
    {
        $app = app('wechat.official_account');
        $join = join::where('join.uuid', session('join')->uuid)
            ->where('join.status','1')
            ->leftJoin('user','user.user_uuid','=','join.uuid')
            ->select('join.*','user.user_name','user.user_pic','user.user_nickname')
            ->first();
        if ($join) {
            // 非会员用户人数
            $free = User::where('join_pid', session('join')->uuid)
                ->where('user_rank','0')
                ->get()->count();
            // 会员用户人数
            $pay = User::where('join_pid', session('join')->uuid)
                ->where('user_rank','>','0')
                ->get()->count();
            // 累计汇款
            $sum = admin_join_order::where('uuid',session('join')->uuid)
                ->where('agree','1')
                ->get()
                ->sum('price');
            // 获取准会员数量
            $open = log_point_open::where('sale',session('join')->uuid)->get()->count();
            // 验证是否修改了初始密码
            if ($join->password == 'eyJpdiI6ImpiRDM0cXF0QXBDYnh0QlhzZkpoY2c9PSIsInZhbHVlIjoiaU1NWnVxd3RXSmt3a2pBVkV2cWJMUT09IiwibWFjIjoiZDAzZDBkYTkyNmE4MTQ5N2E5ZmE2ZWJjZTRjNzZhOGYyZDFiYmVhODY2YjlmYTY5ZGU1YTVhMjRlZWZjNTM0ZSJ9') {
                $pw = true;
            }else{
                $pw = false;
            }
        }else{
            return view('home.join-login');
        }
        return view('home.join',compact('app','join','pay','free','sum','pw','open'));
    }

    public function payUser()
    {
        $app = app('wechat.official_account');
        $info = User::where('join_pid', session('join')->uuid)
            ->where('user_rank','>','0')
            ->get();
        $user = array();
        if ($info->isNotEmpty()) {
            foreach ($info as $key => $value) {
                if ($value->user_rank == 0) {
                    $value->rankName = '非付费用户';
                }elseif ($value->user_rank == 1) {
                    $value->rankName = '体验用户';
                }elseif ($value->user_rank == 2) {
                    $value->rankName = '男爵会员';
                }elseif ($value->user_rank == 3) {
                    $value->rankName = '子爵会员';
                }elseif ($value->user_rank == 4) {
                    $value->rankName = '伯爵会员';
                }elseif ($value->user_rank == 5) {
                    $value->rankName = '侯爵会员';
                }elseif ($value->user_rank == 6) {
                    $value->rankName = '公爵会员';
                }
                $user[] = $value;
            }
        }
        return view('home.join-user-pay',compact('app','user','info'));
    }

    public function tempUser()
    {
        $input = Input::all();
        $app = app('wechat.official_account');
        if ($input['rank'] == 99) {
            // 获取开拓会籍用户
            $info = log_point_open::where('log_point_open.sale',session('join')->uuid)
                ->leftjoin('user','user.user_uuid','=','log_point_open.uuid')
                ->where('user_rank','0')
                ->select('user.user_name','user.user_nickname','user.user_phone','user.user_pic','user.user_rank','user.user_point_open','log_point_open.*')
                ->get();
        }else{
            if ($input['rank'] == 0) {
                $info = User::where('join_pid', session('join')->uuid)
                ->where('user_rank','>','0')
                ->get();
            }else{
                $info = User::where('join_pid', session('join')->uuid)
                    ->where('user_rank',$input['rank'])
                    ->get();
            }
        }
        $user = array();
        if ($info->isNotEmpty()) {
            foreach ($info as $key => $value) {
                if ($value->user_rank == 0) {
                    $value->rankName = '非付费用户';
                }elseif ($value->user_rank == 1) {
                    $value->rankName = '体验用户';
                }elseif ($value->user_rank == 2) {
                    $value->rankName = '男爵会员';
                }elseif ($value->user_rank == 3) {
                    $value->rankName = '子爵会员';
                }elseif ($value->user_rank == 4) {
                    $value->rankName = '伯爵会员';
                }elseif ($value->user_rank == 5) {
                    $value->rankName = '侯爵会员';
                }elseif ($value->user_rank == 6) {
                    $value->rankName = '公爵会员';
                }
                $user[] = $value;
            }
            $result = $this->result('success','成功!',$user);
        }else{
            $result = $this->result('fail','未获取到信息!');
        }
        return $result;
    }

    public function freeUser()
    {
        $app = app('wechat.official_account');
        $user = User::orderBy('id','DESC')
            ->where('join_pid', session('join')->uuid)
            ->where('user_rank','0')
            ->get();
        // dd($user);
        return view('home.join-user-free',compact('app','user'));
    }

    public function pay()
    {
        $app = app('wechat.official_account');
        $user = join::orderBy('id','DESC')
            ->where('join.uuid', session('join')->uuid)
            ->where('join.status','1')
            ->leftJoin('user','user.user_uuid','=','join.uuid')
            ->select('join.*','user.user_name','user.user_pic','user.user_nickname')
            ->first();
        return view('home.join-pay',compact('app','user'));
    }

    public function allFreeUser($uuid)
    {
        $app = app('wechat.official_account');
        $user = User::orderBy('id','DESC')
            ->where('join_pid', $uuid)
            ->where('user_rank','0')
            ->get();
        // dd($user);
        return view('home.join-user-free',compact('app','user'));
    }

    public function allPayUser($uuid)
    {
        $app = app('wechat.official_account');
        $info = User::where('join_pid', $uuid)
            ->where('user_rank','>','0')
            ->get();
        $user = array();
        if ($info->isNotEmpty()) {
            foreach ($info as $key => $value) {
                if ($value->user_rank == 0) {
                    $value->rankName = '非付费用户';
                }elseif ($value->user_rank == 1) {
                    $value->rankName = '体验用户';
                }elseif ($value->user_rank == 2) {
                    $value->rankName = '男爵会员';
                }elseif ($value->user_rank == 3) {
                    $value->rankName = '子爵会员';
                }elseif ($value->user_rank == 4) {
                    $value->rankName = '伯爵会员';
                }elseif ($value->user_rank == 5) {
                    $value->rankName = '侯爵会员';
                }elseif ($value->user_rank == 6) {
                    $value->rankName = '公爵会员';
                }
                $user[] = $value;
            }
        }

        return view('home.join-user-pay',compact('app','user','info'));
    }

    public function select($phone,$rank)
    {
        $user = User::where('user_phone',$phone)
            ->first();
        if ($user) {
            if ($user->user_uid) {
                // dd(strlen($rank));
                if (strlen($rank) > 1) {
                    if ($user->temp_join < 1) {
                        if (substr($rank, 0, 1) > $user->user_rank) {
                            $result = $this->result('success','查询成功!',$user);
                        }else{
                            $result = $this->result('fail','请选择高于当前级别的会籍进行购买!');
                        }
                    }else{
                        $result = $this->result('fail','该用户已经购买过了体验会员,无法再重复购买,请选择单一购买选项');
                    }
                }else{
                    if (substr($rank, 0, 1) > $user->user_rank) {
                        $result = $this->result('success','查询成功!',$user);
                    }else{
                        $result = $this->result('fail','请正确选择升级的会籍项',$user);
                    }
                }
            }else{
                $result = $this->result('fail','无法进行该操作,请提示用户到用户中心完成实名认证!');
            }
        }else{
            $result = $this->result('fail','未获取到用户信息,请检查手机号码是否正确');
        }
        return $result;
    }

    public function handle()
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
                    $user = User::where('user_phone',$input['phone'])
                        ->first();
                        if ($user->temp_join) {
                            $temp_join = 1;
                        }else{
                            $temp_join = 0;
                        }
                        if (strlen($input['rank']) == 2 && $temp_join || $input['rank'] == $temp_join) {
                            $result = $this->result('fail','该用户已经购买过了体验会员!');
                        }else{
                            $order = new join_order;
                            $order->num = "pay".date('YmdHis').rand('10000','99999');
                            $order->uuid = session('join')->uuid;
                            $order->to = $user->user_uuid;
                            $order->type = 1;
                            $order->status = 1;
                            switch ($input['rank']) {
                                case '1':
                                    $order->point = 1000;
                                    $addPoint = 2000;
                                    $rank = 1;
                                    break;
                                case '2':
                                    $order->point = 10000;
                                    $addPoint = 10000;
                                    $rank = 2;
                                    break;
                                case '21':
                                    $order->point = 11000;
                                    $addPoint = 12000;
                                    $temp_join = 1;
                                    $rank = 2;
                                    break;
                                case '3':
                                    $order->point = 50000;
                                    $addPoint = 50000;
                                    $rank = 3;
                                    break;
                                case '31':
                                    $order->point = 51000;
                                    $addPoint = 52000;
                                    $temp_join = 1;
                                    $rank = 3;
                                    break;
                                case '4':
                                    $order->point = 100000;
                                    $addPoint = 100000;
                                    $rank = 4;
                                    break;
                                case '41':
                                    $order->point = 101000;
                                    $addPoint = 102000;
                                    $temp_join = 1;
                                    $rank = 4;
                                    break;
                                case '5':
                                    $order->point = 200000;
                                    $addPoint = 200000;
                                    $rank = 5;
                                    break;
                                case '51':
                                    $order->point = 201000;
                                    $addPoint = 202000;
                                    $temp_join = 1;
                                    $rank = 5;
                                    break;
                                case '6':
                                    $order->point = 1000000;
                                    $addPoint = 1000000;
                                    $rank = 6;
                                    break;
                                case '61':
                                    $order->point = 1001000;
                                    $addPoint = 1002000;
                                    $temp_join = 1;
                                    $rank = 6;
                                    break;
                            }
                            $newPoint = $user->user_point_give + $addPoint;
                            if ($user->join_buy) {
                                $joinBuy = $user->jon_buy;
                            }else{
                                $joinBuy = session('join')->uuid;
                            }
                            // 加盟商扣除后的积分
                            // 优先扣除返佣和赠送的积分
                            if ($join->type == 1 && $user->user_rank < 2) {
                                if ($join->point_fund > $order->point) {
                                    $joinNewPoint = $join->point;
                                    $joinNewPointGive = $join->point_give;
                                    $joinNewPointFund = $join->point_fund - $order->point;
                                }else{
                                    $joinNewPoint = ($join->point_fund + $join->point) - $order->point;
                                    $joinNewPointGive = $join->point_give;
                                    $joinNewPointFund = 0;
                                }
                            }else{
                                if ($join->point_fund > $order->point) {
                                    $joinNewPoint = $join->point;
                                    $joinNewPointGive = $join->point_give;
                                    $joinNewPointFund = $join->point_fund - $order->point;
                                }else{
                                    if (($join->point_fund + $join->point_give) > $order->point) {
                                        $joinNewPoint = $join->point;
                                        $joinNewPointGive = ($join->point_fund + $join->point_give) - $order->point;
                                        $joinNewPointFund = 0;
                                    }else{
                                        $joinNewPoint = ($join->point_fund + $join->point_give + $join->point) - $order->point;
                                        $joinNewPointGive = 0;
                                        $joinNewPointFund = 0;
                                    }
                                }
                            }
                            // 记录加盟商日志
                            $log = new log_point_join;
                            $log->uuid = $join->uuid;
                            $log->point = $join->point;
                            $log->point_give = $join->point_give;
                            $log->new_point = $joinNewPoint;
                            $log->new_point_give = $joinNewPointGive;
                            $log->point_fund = $join->point_fund;
                            $log->new_point_fund = $joinNewPointFund;
                            $log->status = 1;
                            $log->type = 5;
                            $log->add = 2;
                            // 记录用户添加的赠送积分;
                            $logUser = new log_point_user;
                            $logUser->uuid = $user->user_uuid;
                            $logUser->point = $user->user_point;
                            $logUser->new_point = $user->user_point;
                            $logUser->type = 3;
                            $logUser->point_give = $user->user_point_give;
                            $logUser->new_point_give = $user->user_point_give + $addPoint;
                            $logUser->status = 1;
                            $logUser->add = 1;
                            // 记录代理商的积分扣除
                            if ($joinNewPoint >= 0) {
                                DB::beginTransaction();
                                try{
                                    $logUser->save();
                                    $order->save();
                                    $log->save();
                                    User::where('user_uuid',$user->user_uuid)
                                        ->update(['user_point_give'=>$newPoint,'user_rank'=>$rank,'temp_join'=>$temp_join,'join_buy'=>$joinBuy,'rank_start'=>time()]);
                                    join::where('uuid',$join->uuid)
                                        ->update(['point'=>$joinNewPoint,'point_give'=>$joinNewPointGive,'point_fund'=>$joinNewPointFund]);
                                    DB::commit();
                                    $result = $this->result('success','购买会籍成功,当前剩余: ('.$joinNewPoint.' 基本积分),('.$joinNewPointGive.' 赠送积分),('.$joinNewPointFund.' 返佣积分)');
                                }catch (\Exception $e) {
                                    //接收异常处理并回滚
                                    DB::rollBack();
                                    $result = $this->result('fail','ERROR!当前系统繁忙,购买失败!');
                                }
                            }else{
                                $result = $this->result('fail','您的加盟商账户积分不足以抵扣!');
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

    public function recharge()
    {
        $app = app('wechat.official_account');
        $user = join::where('join.uuid', session('join')->uuid)
            ->where('join.status','1')
            ->leftJoin('user','user.user_uuid','=','join.uuid')
            ->select('join.*','user.user_name','user.user_pic','user.user_nickname')
            ->first();
        return view('home.join-recharge',compact('app','user'));
    }

    public function rechargeSelect($phone, $point)
    {
        $user = User::where('user_phone',$phone)
            ->first();
        $join = join::where('uuid',session('join')->uuid)->first();
        if ($join) {
            if (($join->point + $join->point_give + $join->point_fund) < $point) {
                $result = $this->result('fail','当前积分不足以为用户充值,请降低充值额度!');
            }else{
                if ($user) {
                    $result = $this->result('success','获取用户信息成功!',$user);
                }else{
                    $result = $this->result('fail','未获取到用户信息,请重新输入电话号码!');
                }
            }
        }else{
            $result = $this->result('fail','当前登录状态过期,请重新登录!');
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
                    $user = User::where('user_phone',$input['phone'])
                    ->first();
                    if ($user->user_rank < 2 && $join->type != 1) {
                        $result = $this->result('fail','只支持为会员用户充值!');
                    } else {
                        // 扣除积分
                        // 加盟商扣除后的积分
                        // 优先扣除返佣和赠送的积分
                        // 增加合伙人扣分规则
                        if ($join->type == 1 && $user->user_rank < 2) {
                            if ($join->point_fund > $input['point']) {
                                $joinNewPoint = $join->point;
                                $joinNewPointGive = $join->point_give;
                                $joinNewPointFund = $join->point_fund - $input['point'];
                            }else{
                                $joinNewPoint = ($join->point_fund + $join->point) - $input['point'];
                                $joinNewPointGive = $join->point_give;
                                $joinNewPointFund = 0;
                            }
                        }else{
                            if ($join->point_fund > $input['point']) {
                                $joinNewPoint = $join->point;
                                $joinNewPointGive = $join->point_give;
                                $joinNewPointFund = $join->point_fund - $input['point'];
                            }else{
                                if (($join->point_fund + $join->point_give) > $input['point']) {
                                    $joinNewPoint = $join->point;
                                    $joinNewPointGive = ($join->point_fund + $join->point_give) - $input['point'];
                                    $joinNewPointFund = 0;
                                }else{
                                    $joinNewPoint = ($join->point_fund + $join->point_give + $join->point) - $input['point'];
                                    $joinNewPointGive = 0;
                                    $joinNewPointFund = 0;
                                }
                            }
                        }
                        // 为用户增加积分
                        $newUserPoint = $user->user_point + $input['point'];
                        // 增加赠送规则
                        switch ($user->user_rank) {
                            case '2':
                                $givePoint = $user->user_point_give +($input['point'] * 0.05);
                                break;
                            case '3':
                                $givePoint = $user->user_point_give +($input['point'] * 0.1);
                                break;
                            case '4':
                                $givePoint = $user->user_point_give +($input['point'] * 0.15);
                                break;
                            case '5':
                                $givePoint = $user->user_point_give +($input['point'] * 0.2);
                                break;
                            case '6':
                                $givePoint = $user->user_point_give +($input['point'] * 0.25);
                                break;
                            default:
                                $givePoint = $user->user_point_give;
                                break;
                        }
                        // 记录用户添加的积分;
                        $logUser = new log_point_user;
                        $logUser->uuid = $user->user_uuid;
                        $logUser->point = $user->user_point;
                        $logUser->new_point = $newUserPoint;
                        $logUser->type = 4;
                        $logUser->point_give = $user->user_point_give;
                        $logUser->new_point_give = $givePoint;
                        $logUser->status = 1;
                        $logUser->add = 1;

                        $order = new join_order;
                        $order->num = "cz".date('YmdHis').rand('10000','99999');
                        $order->uuid = $join->uuid;
                        $order->to = $user->user_uuid;
                        $order->type = 2;
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
                        $log->to = $user->user_uuid;
                        $log->type = 6;
                        $log->add = 2;
                        if ($joinNewPoint >= 0) {
                            DB::beginTransaction();
                            try{
                                $logUser->save();
                                $order->save();
                                $log->save();
                                User::where('user_uuid',$user->user_uuid)
                                    ->update([
                                        'user_point'=>$newUserPoint,
                                        'user_point_give'=>$givePoint
                                    ]);
                                join::where('uuid',$join->uuid)
                                    ->update(['point'=>$joinNewPoint,'point_give'=>$joinNewPointGive,'point_fund'=>$joinNewPointFund]);
                                DB::commit();
                                $result = $this->result('success','积分充值成功,当前剩余: ('.$joinNewPoint.' 基本积分),('.$joinNewPointGive.' 赠送积分),('.$joinNewPointFund.' 返佣积分)');
                            }catch (\Exception $e) {
                                //接收异常处理并回滚
                                DB::rollBack();
                                $result = $this->result('fail','ERROR!当前系统繁忙,购买失败!');
                            }
                        }else{
                            $result = $this->result('fail','您的加盟商账户积分不足以抵扣!');
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

    public function card()
    {
        $app = app('wechat.official_account');
        $user = join::where('join.uuid', session('join')->uuid)
            ->where('join.status','1')
            ->leftJoin('user','user.user_uuid','=','join.uuid')
            ->select('join.*','user.user_name','user.user_pic','user.user_nickname')
            ->first();
        return view('home.join-card',compact('app','user'));
    }

    public function makeCard()
    {
        $input = Input::all();
        // 验证用户是否存在
        $user = User::where('user_phone', $input['phone'])->first();
        // 制作名片
        if ($user) {
                // 制作名片
                $url = "http://".env('HTTP_HOST')."/handlePid/".$user->user_uuid."/".session('join')->uuid;
                $QIMG = $this->makeQrcode($url,$user->user_pic);
                if ($QIMG) {
                    $dst_path = 'images/card_bg.png';//底图
                    $src_path = $QIMG;//覆盖图
                    // 创建图片实例
                    $dst = imagecreatefromstring(file_get_contents($dst_path));
                    $src = imagecreatefromstring(file_get_contents($src_path));
                    list($src_w, $src_h) = getimagesize($src_path);
                    imagecopymerge($dst, $src, 190, 650, 0, 0, $src_w, $src_h, 100);
                    // 在图片上写入文字
                    $font = '/fonts/msyh.ttf';
                    // Log::notice($font);
                    $color = imagecolorallocate($dst,220,0,132);
                    @imagefttext($dst, 30, 0, 50, 50, $color, $font, $user->user_nickname);
                    //输出图片
                    list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
                    $newPic = "qrcode/card-".rand(1000000,9999999).time().".png";
                    imagepng($dst, $newPic);
                    imagedestroy($dst);
                    imagedestroy($src);
                    $newPicSrc = "http://".env('HTTP_HOST')."/".$newPic;
                    $result = $this->result('success','生成名片成功!',$newPicSrc);
                }else{
                    $result = $this->result('fail','系统繁忙,处理数据失败!');
                }
        }else{
            $result = $this->result('fail','未查询到该用户!');
        }
        return $result;
    }

    public function order()
    {
        $app = app('wechat.official_account');
        $user = join_order::orderBy('join_order.id','DESC')
            ->where('join_order.uuid',session('join')->uuid)
            ->leftJoin('user','user.user_uuid','=','join_order.to')
            ->where('join_order.status','1')
            ->select('join_order.*','user.user_pic','user.user_name','user_nickname')
            ->get();
        return view('home.join-order',compact('app','user'));
    }

    public function handlePid($userPid,$joinPid)
    {
        // 检查登录状态,如果登录则开始执行逻辑.如果没登录则保存预判动作存入session
        if (session('user')) {
            // Log::notice($userPid);
            // Log::notice($joinPid);
            $user = User::where('user_uuid',session('user')->user_uuid)->first();
            if (empty($user->join_pid)) {
                $pidJoin = $joinPid;
            }else{
                $pidJoin = $user->join_pid;
            }
            if (empty($user->user_pid)) {
                $pidUser = $userPid;
            }else{
                $pidUser = $user->user_pid;
            }
            if ($user->user_rank == 0) {
                if (User::where('user_uuid',session('user')->user_uuid)
                    ->update([
                        'user_pid'=>$pidUser,
                        'join_pid'=>$pidJoin
                    ])) {
                    return redirect('/');
                }else{
                    return redirect('/');
                }
            }else{
                return redirect('/');
            }
        }else{
            // 存储session到预判动作
            session(['ACTION_USER_PID'=>$userPid]);
            session(['ACTION_JOIN_PID'=>$joinPid]);
            return redirect('/');
        }
    }

    public function log()
    {
        $app = app('wechat.official_account');
        $log = log_point_join::orderBy('id','DESC')
            ->where('uuid',session('join')->uuid)
            ->get();
        // dd($log);
        return view('home.join-log',compact('app','log'));
    }

    public function asset()
    {
        $app = app('wechat.official_account');
        $join = join::where('uuid',session('join')->uuid)->first();
        // 查询出收益账户得总额
        return view('home.join-asset',compact('app','join'));
    }

    public function assetPoint()
    {
        $app = app('wechat.official_account');
        $join = join::where('uuid',session('join')->uuid)->first();
        $log = log_point_join::orderBy('log_point_join.id','DESC')
            ->leftJoin('user','user.user_uuid','=','log_point_join.to')
            ->where('log_point_join.uuid',session('join')->uuid)
            ->where('log_point_join.status','1')
            ->select('log_point_join.*','user.user_name','user.user_nickname')
            ->get();
        // dd($log);
        return view('home.join-asset-point',compact('app','join','log'));
    }

    public function changeCash()
    {
        $input = Input::all();
        // dd($input);
        if ($input['point_fund'] % 100 == 0) {
            // 验证提现密码是否正确
            $user = User::where('user_uuid',session('join')->uuid)->first();
            if ($user->cash_password) {
                if (Crypt::decrypt($user->cash_password) == $input['password']) {
                    // 检查积分是否足够.
                    $join = join::where('uuid',session('join')->uuid)->first();
                    $joinNewPointFund = $join->point_fund - $input['point_fund'];
                    if ($joinNewPointFund >= 0) {
                        $newJoinCash = $join->join_cash + ($input['point_fund'] *0.8);
                        // 写入积分变动日志
                        $pointLog = new log_point_join;
                        $pointLog->point_fund = $join->point_fund;
                        $pointLog->new_point_fund = $joinNewPointFund;
                        $pointLog->uuid = $join->uuid;
                        $pointLog->add = 0;
                        $pointLog->status = 1;
                        $pointLog->type = 9;
                        // 写入现金变动日志
                        $priceLog = new log_price_join;
                        $priceLog->type = 1;
                        $priceLog->status = 1;
                        $priceLog->add = 1;
                        $priceLog->uuid = $join->uuid;
                        $priceLog->join_cash = $join->join_cash;
                        $priceLog->new_join_cash = $newJoinCash;
                        DB::beginTransaction();
                        try{
                            $pointLog->save();
                            $priceLog->save();
                            join::where('uuid',$join->uuid)
                                ->update(['join_cash'=>$newJoinCash,'point_fund'=>$joinNewPointFund]);
                            DB::commit();
                            $result = $this->result('success','转现操作成功!请到梦享家收益账户查看详情!');
                        }catch (\Exception $e) {
                            //接收异常处理并回滚
                            DB::rollBack();
                            $result = $this->result('fail','ERROR!当前系统繁忙,转现失败!');
                        }
                    }else{
                        $result = $this->result('fail','当前账户用于转现的积分不足,请降低额度后再试!');
                    }
                }else{
                    $result = $this->result('fail','提现密码错误,请重新输入后再试!');
                }
            }else{
                $result = $this->result('fail','请您先设置提现密码!(用户中心->信息管理)');
            }
        }else{
            $result = $this->result('fail','请输入100的整数倍进行转现');
        }
        return $result;
    }

    public function assetPrice()
    {
        $app = app('wechat.official_account');
        $join = join::where('uuid',session('join')->uuid)->first();
        $log = log_price_join::orderBy('id','DESC')
            ->where('uuid',session('join')->uuid)
            ->where('status','1')
            ->get();
        $rtsh_log = log_rtsh_join::orderBy('log_rtsh_join.id','DESC')
            ->where('log_rtsh_join.uuid',session('join')->uuid)
            ->leftJoin('rtsh_order','rtsh_order.num','=','log_rtsh_join.num')
            ->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
            ->where('log_rtsh_join.status','1')
            ->select('user.user_name','rtsh_order.price','rtsh_order.odds','rtsh_order.time','log_rtsh_join.rtsh_bond','log_rtsh_join.new_rtsh_bond','log_rtsh_join.updated_at','log_rtsh_join.num')
            ->get();
        // dd($rtsh_log);
        return view('home.join-asset-price',compact('app','join','log',
            'rtsh_log'));
    }

    public function spring()
    {
        $app = app('wechat.official_account');
        $spring = join::where('uuid',session('join')->uuid)->first();
        // 查询出发展会籍得总数
        $count = User::where('join_buy',session('join')->uuid)
            ->get();
        // 查询出收益账户得总额
        return view('home.spring-asset',compact('app','spring','count'));
    }

    public function springLog()
    {
        $app = app('wechat.official_account');
        // 获取春蚕流水日志
        $spring = log_point_spring::orderBy('id','DESC')
            ->where('uuid', session('join')->uuid)
            ->get();
        return view('home.spring-asset-log',compact('app','spring'));
    }

    public function password()
    {
        // 加盟商重置登录密码
        $user = User::where('user_uuid',session('join')->uuid)->first();
        if ($user->user_phone) {
            if ($this->sendSms($user->user_phone)) {
                $result = $this->result('success','短信已经发送!',$user->user_phone);
            }else{
                $result = $this->result('fail','未知的错误,短信发送失败!');
            }
        }else{
            $result = $this->result('fail','未查询到该用户的电话号码!');
        }
        return $result;
    }

    public function passwordUpdate()
    {
        $input = Input::all();
        if ($input['code'] == session('smsCode')) {
            if (join::where('uuid',session('join')->uuid)
                ->update([
                    "password" => Crypt::encrypt($input['password'])
                ])) {
                $result = $this->result('success','加盟商登录密码修改成功!');
            }else{
                $result = $this->result('fail','加盟商登录密码修改失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','短信验证码错误!');
        }
        return $result;
    }

    public function atm()
    {
        $app = app('wechat.official_account');
        $card = bank::where('uuid',session('join')->uuid)
            ->orderBy('status','DESC')
            ->get();
        $user = user::where('user_uuid',session('join')->uuid)->first();
        if ($user->status == 0) {
            dd('该账号功能已被部分限制,请联系客服人员!');
        }else{
            return view('home.join-cash',compact('app','user','card'));
        }
    }

    public function getBalance()
    {
        $input = Input::all();
        $balance = join::where('uuid',session('join')->uuid)
                    ->first();
        switch ($input['type']) {
            case '3':
                $balance = $balance->join_cash;
                break;
            case '4':
                $balance = $balance->rtsh_bond;
                break;
            case '5':
                $balance = $balance->rtsh_property;
                break;
            case '6':
                $balance = $balance->price;
                break;
        }
        if (isset($balance)) {
            $result = $this->result('success','查询成功!',$balance);
        }else{
            $result = $this->result('fail','获取用户余额失败,请稍后再试!');
        }
        return $result;
    }

    public function springAtm()
    {
        $app = app('wechat.official_account');
        $card = bank::where('uuid',session('join')->uuid)
            ->orderBy('status','DESC')
            ->get();
        $user = user::where('user_uuid',session('join')->uuid)->first();
        if ($user->status == 0) {
            dd('该账号功能已被部分限制,请联系客服人员!');
        }else{
            return view('home.spring-cash',compact('app','user','card'));
        }
    }

    public function joinLogin()
    {
        if (session('join')) {
            $app = app('wechat.official_account');
            $join = join::where('join.uuid', session('join')->uuid)
                ->where('join.status','1')
                ->leftJoin('user','user.user_uuid','=','join.uuid')
                ->select('join.*','user.user_name','user.user_pic','user.user_nickname')
                ->first();
            if ($join) {
                // 非会员用户人数
                $free = User::where('join_pid', session('join')->uuid)
                    ->where('user_rank','0')
                    ->get()->count();
                // 会员用户人数
                $pay = User::where('join_pid', session('join')->uuid)
                    ->where('user_rank','>','0')
                    ->get()->count();
                // 累计汇款
                $sum = admin_join_order::where('uuid',session('join')->uuid)
                    ->where('agree','1')
                    ->get()
                    ->sum('price');
                // 获取准会员数量
                $open = log_point_open::where('sale',session('join')->uuid)->get()->count();
                // 验证是否修改了初始密码
                if ($join->password == 'eyJpdiI6ImpiRDM0cXF0QXBDYnh0QlhzZkpoY2c9PSIsInZhbHVlIjoiaU1NWnVxd3RXSmt3a2pBVkV2cWJMUT09IiwibWFjIjoiZDAzZDBkYTkyNmE4MTQ5N2E5ZmE2ZWJjZTRjNzZhOGYyZDFiYmVhODY2YjlmYTY5ZGU1YTVhMjRlZWZjNTM0ZSJ9') {
                    $pw = true;
                }else{
                    $pw = false;
                }
            }else{
                return view('home.join-login');
            }
            return view('home.join',compact('app','join','pay','free','sum','pw','open'));
        }else{
            return view('home.join-login');
        }
    }

    public function setInfo()
    {
        $info = User::where('user.user_uuid',session('join')->uuid)
            ->leftJoin('join','join.uuid','=','user.user_uuid')
            ->first();
        return view('home.join-set',compact('info'));
    }

    public function out()
    {
        if (session(['join'=>''])) {
            return redirect('/');
        }else{
            return redirect('/');
        }
    }

    public function setPasswordPage()
    {
        return view('home.join-set-password');
    }

    public function setPassword()
    {
        // 用户重置登录密码
        $user = User::where('user_uuid',session('join')->uuid)->first();
        if ($user->user_phone) {
            if ($this->sendSms($user->user_phone)) {
                $result = $this->result('success','短信已经发送!',$user->user_phone);
            }else{
                $result = $this->result('fail','未知的错误,短信发送失败!');
            }
        }else{
            $result = $this->result('fail','未查询到该用户的电话号码!');
        }
        return $result;
    }

    public function setPasswordUpdate()
    {
        $input = Input::all();
        if ($input['code'] == session('smsCode')) {
            if (join::where('uuid',session('join')->uuid)
                ->update([
                    "password" => Crypt::encrypt($input['password'])
                ])) {
                $result = $this->result('success','加盟商登录密码修改成功!');
            }else{
                $result = $this->result('fail','加盟商登录密码修改失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','短信验证码错误!');
        }
        return $result;
    }
}
