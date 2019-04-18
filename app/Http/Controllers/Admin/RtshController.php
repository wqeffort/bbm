<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\rtsh_obj;
use App\Model\rtsh_order;
use App\Model\log_rtsh;
use App\Model\log_rtsh_rent;
use App\Model\User;
use App\Model\join;
use App\Model\cash;
use App\Model\admin;
use App\Model\log_price_join;
use App\Model\log_rtsh_join;
use Log;
class RtshController extends Controller
{
    public function info()
    {
        // 获取最近几个月的总投资额和总派息额
        $mouth = rtsh_obj::orderBy('id','DESC')
            ->where('status','1')
            ->where('start','<',date('Y-m-d H:i:s',strtotime('last month')))
            ->take(5)
            ->get();
        // dd($mouth);
        // 根据订单项目id获取相应项目的所有订单号
        $data = array();
        foreach ($mouth as $key => $value) {
            $order = rtsh_order::where('type','1')
            ->where('obj_id',$value->id)
            ->get();
            $value->order = $order;
            $value->orderCount = $order->count();
            $value->price = $order->sum('price');
            $value->rent_on = 0;
            $value->rent_all = 0;
            foreach ($order as $k => $v) {
                if ($rentA = log_rtsh_rent::where('num',$v->num)
                ->first()) {
                    $value->rent_all += $rentA->price;
                }
                if ($rentB = log_rtsh_rent::where('num',$v->num)
                ->where('status','1')
                ->first()) {
                    $value->rent_on += $rentB->price;
                }
            }
            $data[] = $value;
        }
        return view('admin.rtsh-info',compact('data'));
    }
	public function list()
	{
		$obj = rtsh_obj::orderBy('id','DESC')
			->paginate(50);
		return view('admin.rtsh-obj-list',compact('obj'));
	}
    public function createObj()
    {
    	return view('admin.rtsh-obj-add');
    }

    public function addObj()
    {
    	$input = Input::all();
    	// dd($input);
    	// 检查两个时间节点
    		$obj = new rtsh_obj;
    		$obj->title = $input['title'];
    		$obj->img = $input['img'];
    		$obj->start = $input['start'];
    		$obj->odds_1 = $input['odds_1'];
    		$obj->odds_2 = $input['odds_2'];
    		$obj->log = $input['log'];
    		$obj->desc = $input['text'];
    		if ($obj->save()) {
    			$result = $this->result('success','项目创建成功!');
    		}else{
    			$result = $this->result('fail','ERROR,数据未写入数据库,项目创建失败!');
    		}
    	return $result;
    }

    public function objShow($id)
    {
    	$obj = rtsh_obj::find($id);
    	return view('admin.rtsh-obj-show',compact('obj'));
    }

    public function objEdit($id)
    {
    	$input = Input::all();
    	if (rtsh_obj::where('id',$id)
    		->update([
    			"title" => $input['title'],
	    		"img" => $input['img'],
	    		"start" => $input['start'],
	    		"odds_1" => $input['odds_1'],
	    		"odds_2" => $input['odds_2'],
	    		"log" => $input['log'],
	    		"desc" => $input['text']
    		])) {
    		$result = $this->result('success','编辑成功!');
    	}else{
    		$result = $this->result('fail','ERROR,数据未写入数据库,项目编辑失败!');
    	}
    	return $result;
    }

    public function orderCreate()
    {
    	$now = date('Y-m-d');
    	$obj = rtsh_obj::orderBy('id','DESC')
    		->where('start','>=',$now)
    		->where('status','1')
    		->where('end','0')
    		->get();
    	return view('admin.rtsh-order-add', compact('obj'));
    }

    public function selectUser()
    {
    	$input = Input::all();
    	$user = User::where('user_phone',$input['phone'])->first();
    	if (empty($user)) {
    		$result = $this->result('fail','未查询到该用户的信息!');
    	}else{
    		if ($user->user_uid) {
    			$result = $this->result('success','查询用户信息成功!',$user);
    		}else{
    			$result = $this->result('fail','该用户还未进行实名制认证!');
    		}
    	}
    	return $result;
    }

    public function selectJoin()
    {
        $input = Input::all();
        $join = User::where('user.user_name',$input['join'])
            ->leftJoin('join','join.uuid','=','user.user_uuid')
            ->where('join.status','1')
            ->whereNotNull('join.uuid')
            ->first();
        if (empty($join)) {
            $result = $this->result('fail','未查询到该加盟商的信息!');
        }else{
            $result = $this->result('success','查询加盟商信息成功!',$join);
        }
        return $result;
    }

    public function selectObj()
    {
    	$input = Input::all();
    	$obj = rtsh_obj::find($input['id']);
    	if ($obj) {
    		$result = $this->result('success','查询成功!',$obj);
    	}else{
    		$result = $this->result('fail','未查询到该项目的项目信息!');
    	}
    	return $result;
    }

    public function orderAdd()
    {
    	$input = Input::all();
        // dd($input);
        if (!isset($input['join_uuid'])) {
            $input['join_uuid'] = '';
        }
        if (!isset($input['join_name'])) {
            $input['join_name'] = '';
        }
    	$rtsh_order = new rtsh_order;
    	$rtsh_order->num = date('YmdHis').rand(100000,999999);
    	$rtsh_order->obj_id = $input['obj_id'];
    	$rtsh_order->uuid = $input['uuid'];
    	$rtsh_order->join_name = $input['join_name'];
    	$rtsh_order->join_uuid = $input['join_uuid'];
    	$rtsh_order->price = $input['price'];
    	$rtsh_order->odds = $input['odds'];
    	$rtsh_order->img = $input['img'];
        $rtsh_order->status = 1;
    	$rtsh_order->time = explode("dds", $input['time'])['1'];
    	$rtsh_order->type = 1;
    	$rtsh_order->log = $input['log'];
        // dd($rtsh_order);
    	if ($rtsh_order->save()) {
    		$result = $this->result('success','订单添加成功,请等待财务审核');
    	}else{
    		$result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
    	}
    	return $result;
    }

    public function orderList()
    {
    	$order = rtsh_order::orderBy('rtsh_order.id','DESC')
    		->where('rtsh_order.end','!=','2')
    		->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
    		->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
    		->select('rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start')
    		->paginate(50);
    	// dd($order);
    	return view('admin.rtsh-order-list',compact('order'));
    }

    public function orderListAll()
    {
        $order = rtsh_order::orderBy('rtsh_order.id','DESC')
    		->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
    		->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
    		->select('rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start')
    		->paginate(50);
    	// dd($order);
    	return view('admin.rtsh-order-list-all',compact('order'));
    }

    public function refundEnd()
    {
        if (session('admin')) {
            $input = Input::all();
            // dd($input);
            $log = log_rtsh_rent::find($input['id']);
            if ($log->status == 0) {
                $orderInfo = rtsh_order::where('num',$log->num)
                    ->where('count',$log->price)
                    ->first();
                if ($orderInfo) {
                    // 获取到用户的账户信息
                    $user = User::where('user_uuid',$orderInfo->uuid)->first();
                    if ($user) {
                        $newBond = $user->rtsh_bond + $orderInfo->count;
                        // 返回本金和加入日志记录
                        $log = new log_rtsh;
                        $log->num = $orderInfo->num;
                        $log->uuid = $orderInfo->uuid;
                        $log->price = $user->rtsh_bond;
                        $log->new_price = $newBond;
                        $log->type = 5;
                        $log->status = 1;
                        $log->admin = session('admin')->user_uuid;
                        DB::beginTransaction();
                try{
                    // 写入日志
                    $log->save();
                    // 修改派息余额
                    rtsh_order::where('num',$orderInfo->num)->update(['count'=>0]);
                    // 修改rent日志
                    log_rtsh_rent::where('num',$orderInfo->num)->update([
                        'admin'=>session('admin')->user_uuid,
                        'status'=>1
                    ]);
                    User::where('user_uuid',$orderInfo->uuid)->update([
                        "rtsh_bond"=>$newBond
                    ]);
                    // 修改用户账户
                     ;
                    DB::commit();
                    $result = $this->result('success','派息成功!');
                }catch (\Exception $e) {
                    //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail','ERROR!订单完结失败!!');
                }
                    }else{
                        $result = $this->result('fail','未查询到用户账户');
                    }
                }else{
                    $result = $this->result('fail','该订单派息余额和日志记录值存在差异,系统拒绝操作!');
                }
            }else{
                $result = $this->result('fail','该订单已经派息,请勿重复操作!');
            }
        }else{
            $result = $this->result('fail','登录状态过期了!请刷新页面后重新登录!');
        }
        return $result;
    }

    public function refund()
    {
        // $order = log_rtsh_rent::orderBy('log_rtsh_rent.id','ASC')
        //     ->whereNull('log_rtsh_rent.admin')
        //     ->paginate(10);
        // dd($order);
        $order = rtsh_order::orderBy('rtsh_order.id','DESC')
            ->where('rtsh_order.end','!=','2')
            ->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
            ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
            ->leftJoin('log_rtsh_rent','log_rtsh_rent.num','=','rtsh_order.num')
            ->where('log_rtsh_rent.status','0')
            ->select('log_rtsh_rent.type as refundType','log_rtsh_rent.status as refundStatus','log_rtsh_rent.price as refundPrice','rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start','log_rtsh_rent.id as rentId')
            ->paginate(50);
        // dd($order);
        return view('admin.rtsh-refund-list',compact('order'));
    }

    public function refundAll()
    {
        $order = rtsh_order::orderBy('rtsh_order.id','DESC')
            ->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
            ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
            ->leftJoin('log_rtsh_rent','log_rtsh_rent.num','=','rtsh_order.num')
            ->where('log_rtsh_rent.id','!=','')
            ->select('log_rtsh_rent.type as refundType','log_rtsh_rent.status as refundStatus','log_rtsh_rent.price as refundPrice','rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start','log_rtsh_rent.id as rentId')
            ->paginate(50);
        // dd($order);
        return view('admin.rtsh-refund-list-all',compact('order'));
    }

    public function searchRefund($name)
    {
        $data = rtsh_order::orderBy('rtsh_order.id','DESC')
            ->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
            ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
            ->leftJoin('log_rtsh_rent','log_rtsh_rent.num','=','rtsh_order.num')
            ->where('log_rtsh_rent.id','!=','')
            ->where('user.user_name','like', '%'.$name.'%')
            ->select('log_rtsh_rent.type as refundType','log_rtsh_rent.status as refundStatus','log_rtsh_rent.price as refundPrice','rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start','log_rtsh_rent.id as rentId')
            ->get();

        if ($data->isNotEmpty()) {
            $result = $this->result('success','获取数据成功!',$data);
        }else{
            $result = $this->result('fail','未查询到数据,请更换搜索关键词!');
        }
        return $result;
    }

    // 添加续期订单 
    // 2018.8.21 修改合并为添加订单
    public function renew()
    {
        $now = date('Y-m-d',strtotime("-2 month"));
        $obj = rtsh_obj::orderBy('id','DESC')
            ->where('start','>=',$now)
            ->where('status','1')
            ->where('end','0')
            ->get();
        return view('admin.rtsh-order-renew', compact('obj'));
    }

    public function renewAdd()
    {
        $input = Input::all();
        // dd($input);
        if (!isset($input['join_uuid'])) {
            $input['join_uuid'] = '';
        }
        if (!isset($input['join_name'])) {
            $input['join_name'] = '';
        }
        $user = User::where('user_uuid',$input['uuid'])->first();
        $newPrice = $user->rtsh_frozen - $input['price'];
        if ($newPrice >= 0) {
            DB::beginTransaction();
            try{
                $num = date('YmdHis').rand(100000,999999);
                $rtsh_order = new rtsh_order;
                $rtsh_order->num = $num;
                $rtsh_order->obj_id = $input['obj_id'];
                $rtsh_order->uuid = $input['uuid'];
                $rtsh_order->join_name = $input['join_name'];
                $rtsh_order->join_uuid = $input['join_uuid'];
                $rtsh_order->price = $input['price'] + $input['cash'];
                $rtsh_order->cash = $input['cash']; // 新增的添加资金
                $rtsh_order->odds = $input['odds'];
                $rtsh_order->time = explode("dds", $input['time'])['1'];
                $rtsh_order->account = $input['account'];
                $rtsh_order->type = 1;
                $rtsh_order->status = 1;
                $rtsh_order->log = $input['log'];
                $rtsh_order->admin = session('admin')->user_uuid;
                $rtsh_order->save();

                // 写入用户流水
                $log = new log_rtsh;
                $log->num = $num;
                $log->uuid = $user->user_uuid;
                $log->frozen = $user->rtsh_frozen;
                $log->new_frozen = $newPrice;
                $log->admin = session('admin')->user_uuid;
                $log->type = 4;
                $log->status = 1;
                $log->save();
                // 扣除用户余额
                User::where('user_uuid',$input['uuid'])
                    ->update(['rtsh_frozen'=>$newPrice]);
                // 发送短信通知
                if ($input['send_sms'] == 1) {
                    $this->sendNotice($user->user_phone,1);
                }
                DB::commit();
                $result = $this->result('success','录入订单成功,续期成功!');
            }catch (\Exception $e) {
            //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
            }
        }else{
            $this->result('fail','ERROR,录入订单失败,用户余额不足!');
        }
        return $result;
    }

    public function viewOrder($num)
    {
        // 查询出订单
        $data = rtsh_order::where('rtsh_order.num',$num)
            ->leftJoin('user','user.user_uuid','=','rtsh_order.uuid')
            ->leftJoin('rtsh_obj','rtsh_obj.id','=','rtsh_order.obj_id')
            ->select('rtsh_order.*','user.rtsh_bond','user.rtsh_frozen','user.user_name','user.user_uid','user.user_uid_a','user.user_uid_b','rtsh_obj.title','rtsh_obj.odds_1','rtsh_obj.odds_2')
            ->first();
        // dd($data);
        return view('admin.rtsh-view-renew',compact('data'));
    }

    public function viewPost($num)
    {
        $input = Input::all();

        // 计算金额是否足够用于抵扣
        $order = rtsh_order::where('num',$num)->first();
        $user = User::where('user_uuid',$input['uuid'])->first();
        if ($input['price'] > ($order->price - $order->cash)) {
            // 增加冻结购买
            $dValue = $input['price'] - ($order->price - $order->cash);
            if ($user->rtsh_frozen >= $dValue) {
                $newUserFrozen = $user->rtsh_frozen - $dValue;
            } else {
                $result = $this->result('fail','操作失败,用户冻结账户资金不足以抵扣!');
                return $result;die;
            }
        } else {
            // 减少冻结购买
            // 多余的钱返回冻结账户
            $dValue = ($order->price - $order->cash) - $input['price'];
            $newUserFrozen = $user->rtsh_frozen + $dValue;
        }
        DB::beginTransaction();
            try{
                if ($dValue != 0) {
                    // 修改用户账户
                    User::where('user_uuid',$input['uuid'])
                        ->update([
                            "rtsh_frozen"=>$newUserFrozen
                        ]);
                    // 添加日志
                    $log = new log_rtsh;
                    $log->num = $order->num;
                    $log->uuid = $user->user_uuid;
                    $log->frozen = $user->rtsh_frozen;
                    $log->new_frozen = $newUserFrozen;
                    $log->admin = session('admin')->user_uuid;
                    $log->type = 9;
                    $log->status = 1;
                    $log->save();
                }
                // 修改订单
                if (empty($input['cash'])) {
                    $input['cash'] = 0;
                }
                rtsh_order::where('num',$num)->update([
                    "log"=>$input['log'],
                    "time"=>$input['time'],
                    "odds"=>$input['odds'],
                    "price"=>$input['price'] + $input['cash'],
                    "cash"=>$input['cash']
                ]);
                DB::commit();
                $result = $this->result('success','订单修改成功!');
            }catch (\Exception $e) {
            //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
            } 
        return $result;
    }

    public function handleEnd()
    {
        $input = Input::all();
        // dd($input);
        // 检查该单号是否到期
        $order = rtsh_order::where('num',$input['num'])->first();
        if ($order->end == 2) {
            $result = $this->result('fail','ERROR,该订单已经完结!');
        }elseif ($order->end == 0) {
            $result = $this->result('fail','ERROR,该订单未到期!');
        }elseif ($order->end == 1) {
            // 检查该订单是否已经派息(派息列表)
            if ($order->count == 0) {
                DB::beginTransaction();
                try{
                    // 返回本金到账户
                    $user = User::where('user_uuid',$order->uuid)->first();
                    $newPrice = $user->rtsh_frozen + $order->price;
                    // 写入用户流水
                    $log = new log_rtsh;
                    $log->num = $input['num'];
                    $log->uuid = $user->user_uuid;
                    $log->frozen = $user->rtsh_frozen;
                    $log->new_frozen = $newPrice;
                    $log->type = 5;
                    $log->status = 1;
                    $log->admin = session('admin')->user_uuid;
                    $log->save();
                    User::where('user_uuid',$order->uuid)->update([
                        "rtsh_frozen"=>$newPrice
                    ]);
                    rtsh_order::where('num',$input['num'])->update([
                        "end"=>2
                    ]);
                    $this->sendNotice($user->user_phone,1);
                    DB::commit();
                    $result = $this->result('success','完结订单成功!');
                }catch (\Exception $e) {
                //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
                }
            }else{
                $result = $this->result('fail','ERROR,该订单还有利息未派出!');
            }
        }
        return $result;
    }

    public function cash()
    {
        $data = cash::orderBy('cash.id','ASC')
            ->whereIn('cash.type',[1,2,4,5])
            ->where('cash.status','!=','2')
            ->where('end','0')
            ->leftJoin('user','user.user_uuid','=','cash.uuid')
            ->leftJoin('bank','bank.id','=','cash.bank_id')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_location','bank.bank_code','bank.bank_logo','bank.bank_card','user.user_name','user.user_phone')
            ->paginate(50);
        // dd($data);
        return view('admin.rtsh-cash-list',compact('data'));
    }

    public function searchCash($name)
    {
        $user = User::where('user_name',$name)->first();
        if ($user) {
            $data = cash::orderBy('cash.id','DESC')
                ->whereIn('cash.type',[1,2,4,5])
                ->where('end','0')
                // ->where('cash.status','!=','2')
                ->leftJoin('user','user.user_uuid','=','cash.uuid')
                ->leftJoin('bank','bank.id','=','cash.bank_id')
                ->where('user.user_uuid',$user->user_uuid)
                ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_location','bank.bank_code','bank.bank_logo','bank.bank_card','user.user_name','user.user_phone')
                ->get();
            if ($data->isNotEmpty()) {
                $result = $this->result('success','获取数据成功!',$data);
            }else{
                $result = $this->result('fail','未查询到该用户的提现记录!');
            }
        }else{
            $result = $this->result('fail','未查询到该用户!');
        }
        return $result;
    }

    public function cashSatatus($id)
    {
        if (cash::find($id)->status == 0) {
            if (cash::where('id',$id)->where('end','0')->update(['status'=>1,'admin'=>session('admin')->uuid])) {
                $result = $this->result('success','操作成功,提交财务处理!');
            }else{
                $result = $this->result('fail','未知的错误!请刷新页面稍后再试!');
            }
        }else{
            $result = $this->result('fail','系统繁忙!请刷新页面稍后再试!');
        }
        return $result;
    }

    public function account()
    {
        return view('admin.rtsh-account');
    }

    public function getAccount()
    {
        $input = Input::all();
        $user = User::where('user_phone', $input['phone'])
            ->leftJoin('join','user.user_uuid','=','join.uuid')
            ->select('user.*','join.rtsh_bond as join_rtsh_bond')
            ->first();
        if ($user) {
            $result = $this->result('success','查询成功!',$user);
        }else{
            $result = $this->result('fail','未查询到用户数据!');
        }
        return $result;
    }

    public function actionAccount()
    {
        if (session('admin')->user_uuid) {
            $input = Input::all();
            $user = User::where('user_phone', $input['phone'])
                ->where('user_uuid',$input['uuid'])
                ->leftJoin('join','user.user_uuid','=','join.uuid')
                ->select('user.*','join.rtsh_bond as join_rtsh_bond')
                ->first();
            if ($user) {
                switch ($input['type']) {
                    case '1':
                        // 债权余额
                        $newBalance = $user->rtsh_bond - $input['price'];
                        if ($newBalance >= 0) {
                            DB::beginTransaction();
                            try{
                                // 发送短信
                                if ($input['sms'] == 1) {
                                    $this->sendNotice($user->user_phone,1);
                                }
                                // 调动账户金额
                                User::where('user_uuid',$input['uuid'])
                                    ->update([
                                        "rtsh_bond"=>$newBalance,
                                        "rtsh_frozen"=>$user->rtsh_frozen + $input['price'],
                                        "rtsh_desc"=>$input['desc']
                                    ]);
                                // 写入日志
                                $log = new log_rtsh;
                                $log->num = date('YmdHis').rand('100000','999999');
                                $log->uuid = $input['uuid'];
                                $log->price = $user->rtsh_bond;
                                $log->new_price = $newBalance;
                                $log->frozen = $user->rtsh_frozen;
                                $log->new_frozen = $user->rtsh_frozen + $input['price'];
                                $log->type = 6;
                                $log->status = 1;
                                $log->admin = session('admin')->user_uuid;
                                $log->save();
                                DB::commit();
                                $result = $this->result('success','账户调动成功!');
                            }catch (\Exception $e) {
                            //接收异常处理并回滚
                                DB::rollBack();
                                $result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
                            }
                        }else{
                            $result = $this->result('fail','操作失败,调度金额不足!');
                        }
                        break;
                    case '2':
                        // 加盟商债权提成
                        $newBalance = $user->join_rtsh_bond - $input['price'];
                        if ($newBalance >= 0) {
                            DB::beginTransaction();
                            try{
                                // 发送短信
                                if ($input['sms'] == 1) {
                                    $this->sendNotice($user->user_phone,1);
                                }
                                // 调动账户金额
                                join::where('uuid',$input['uuid'])
                                    ->update([
                                        "rtsh_bond"=>$newBalance
                                    ]);
                                User::where('user_uuid',$input['uuid'])
                                    ->update([
                                        "rtsh_frozen"=>$user->rtsh_frozen + $input['price'],
                                        "rtsh_desc"=>$input['desc']
                                    ]);
                                // 写入日志
                                $log = new log_rtsh;
                                $log->num = date('YmdHis').rand('100000','999999');
                                $log->uuid = $input['uuid'];
                                $log->price = $user->rtsh_bond;
                                $log->new_price = $user->rtsh_bond;
                                $log->frozen = $user->rtsh_frozen;
                                $log->new_frozen = $user->rtsh_frozen + $input['price'];
                                $log->type = 7;
                                $log->status = 1;
                                $log->admin = session('admin')->user_uuid;
                                $log->save();
                                $joinLog = new log_price_join;
                                $joinLog->uuid = $input['uuid'];
                                $joinLog->rtsh_bond = $user->join_rtsh_bond;
                                $joinLog->new_rtsh_bond = $newBalance;
                                $joinLog->status = 1;
                                $joinLog->type = 5;
                                $joinLog->add = 2;
                                $joinLog->save();
                                DB::commit();
                                $result = $this->result('success','账户调动成功!');
                            }catch (\Exception $e) {
                            //接收异常处理并回滚
                                DB::rollBack();
                                $result = $this->result('fail','ERROR,录入订单失败,数据未写入数据库!');
                            }
                        }else{
                            $result = $this->result('fail','操作失败,调度金额不足!');
                        }
                        break;
                }
            }else{
                $result = $this->result('fail','获取用户数据失败!');
            }
        }else{
            $result = $this->result('fail','登录状态过期,请重新登录后台!');
        }
        return $result;
    }

    public function userInfo($uuid)
    {
        $user = User::where('user_uuid',$uuid)->first();
        // 获取账户流水
        $log = log_rtsh::orderBy('log_rtsh.id','DESC')
            ->where('log_rtsh.uuid',$uuid)
            ->leftJoin('user','log_rtsh.admin','=','user.user_uuid')
            ->select('log_rtsh.*','user.user_name as admin')
            ->paginate(20);
        return view('admin.rtsh-user-info',compact('user','log'));
    }

    public function userInfoEdit($uuid)
    {
        if (session('admin')->uuid) {
            $input = Input::all();
            $user = User::where('user_uuid',$uuid)->first();
            DB::beginTransaction();
            try{
                User::where('user_uuid', $uuid)->update([
                    $input['type']=>$input['val']
                ]);
                if ($input['type'] == 'rtsh_bond' || $input['type'] == 'rtsh_frozen') {
                    $log = new log_rtsh;
                    $log->num = date('YmdHis').rand('100000','999999');
                    $log->uuid = $uuid;
                    switch ($input['type']) {
                        case 'rtsh_bond':
                            $log->price = $user->rtsh_bond;
                            $log->new_price = $input['val'];
                            break;
                        case 'rtsh_frozen':
                            $log->frozen = $user->rtsh_frozen;
                            $log->new_frozen = $input['val'];
                            break;
                    }
                    $log->type = 8;
                    $log->desc = $input['log'];
                    $log->status = 1;
                    $log->admin = session('admin')->uuid;
                    $log->save();
                }
                DB::commit();
                $result = $this->result('success','账户信息变更成功!');
            }catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR,账户信息变更失败,数据未写入数据库!');
            }
        }else{
            $result = $this->result('fail','登录状态过期,请重新登录后台!');
        }
        return $result;
    }

    public function joinRefund()
    {
        $data = rtsh_order::where('rtsh_order.join_count','!=','0')
            ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
            ->leftJoin('user','user.user_uuid','=','rtsh_order.uuid')
            ->select('rtsh_order.*','rtsh_obj.title','user.user_name')
            ->paginate(50);
        return view('admin.rtsh_join_refund',compact('data'));
    }

    public function searchJoin($name)
    {
        // dd(1);
        $data = rtsh_order::orderBy('rtsh_order.id','DESC')
            ->where('rtsh_order.join_name','like','%'.$name.'%')
            ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
            ->leftJoin('user','user.user_uuid','=','rtsh_order.uuid')
            ->join('log_rtsh_join','rtsh_order.num','=','log_rtsh_join.num')
            ->join('user as admin','admin.user_uuid','=','log_rtsh_join.admin')
            ->select('rtsh_order.*','rtsh_obj.title','log_rtsh_join.rtsh_bond as oldPrice','log_rtsh_join.new_rtsh_bond as newPrice','user.user_name','admin.user_name as admin')
            ->get();
        if ($data->isNotEmpty()) {
            $result = $this->result('success','获取数据成功!',$data);
        }else{
            $result = $this->result('fail','未查询到数据!');
        }
        return $result;
    }

    public function joinRefundPost()
    {
        $input = Input::All();
        if (session('admin')) {
            $order = rtsh_order::find($input['id']);
            if ($order) {
                if ($order->join_count > 0) {
                    $join = join::where('uuid',$order->join_uuid)->first();
                    // 执行事务
                    DB::beginTransaction();
                    try{
                        $newRtshBond = $join->rtsh_bond + $order->join_count;
                        // 写入融通日志
                        $log_rtsh_join = new log_rtsh_join;
                        $log_rtsh_join->num = $order->num;
                        $log_rtsh_join->uuid = $order->join_uuid;
                        $log_rtsh_join->rtsh_bond = $join->rtsh_bond;
                        $log_rtsh_join->new_rtsh_bond = $newRtshBond;
                        $log_rtsh_join->type = 1;
                        $log_rtsh_join->status = 1;
                        $log_rtsh_join->admin = session('admin')->user_uuid;
                        $log_rtsh_join->save();
                        // 写入加盟商日志
                        $log_price_join = new log_price_join;
                        $log_price_join->uuid = $order->join_uuid;
                        $log_price_join->rtsh_bond = $join->rtsh_bond;
                        $log_price_join->new_rtsh_bond = $newRtshBond;
                        $log_price_join->type = 2;
                        $log_price_join->add = 1;
                        $log_price_join->status = 1;
                        $log_price_join->save();
                        // 变动账户金额
                        join::where('uuid',$order->join_uuid)->update([
                            "rtsh_bond"=>$newRtshBond
                        ]);
                        // 改变订单表余额
                        $order = rtsh_order::where('id',$input['id'])
                            ->where('join_uuid',$join->uuid)
                            ->update(['join_count'=>0]);
                        DB::commit();
                        $result = $this->result('success','加盟商提成派发成功!');
                    }catch (\Exception $e) {
                        //接收异常处理并回滚
                        DB::rollBack();
                        $result = $this->result('fail','ERROR,账户信息变更失败,数据未写入数据库!');
                    }
                }else{
                    $result = $this->result('fail','该订单加盟商提成为0,请查询订单号,确定是否已经派发过提成!');
                }
            }else{
                $result = $this->result('fail','为查询到该订单');
            }
        }else{
            $result = $this->result('fail','登录状态过期,请重新登录后台!');
        }
        return $result;
    }

    public function searchOrder($num)
    {
        if (preg_match("/^\d*$/",$num)) {
            $data = rtsh_order::orderBy('rtsh_order.id','DESC')
                ->where('rtsh_order.num','like','%'.$num.'%')
                ->leftJoin('user','rtsh_order.uuid','=','user.user_uuid')
                ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
                ->select('rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start')
                ->get();
        }else{
            $data = User::orderBy('rtsh_order.id','DESC')
                ->where('user.user_name','like','%'.$num.'%')
                ->join('rtsh_order','rtsh_order.uuid','=','user.user_uuid')
                ->leftJoin('rtsh_obj','rtsh_order.obj_id','=','rtsh_obj.id')
                ->select('rtsh_order.*','user.user_name','user.user_phone','rtsh_obj.title','rtsh_obj.start')
                ->get();
        }

        if ($data->isNotEmpty()) {
            $result = $this->result('success','获取成功!',$data);
        }else{
            $result = $this->result('fail','未查询到订单');
        }
        return $result;
    }
}
