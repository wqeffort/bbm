<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\log_login;
use App\Model\join;
use App\Model\admin_join_order;
use App\Model\log_point_join;
use App\Model\log_point_up;
use App\Model\log_point_spring;
use App\Model\log_price_join;
use App\Model\log_rtsh;
use App\Model\join_spring;
use App\Model\cash;
use App\Model\log_point_user;
use Log;
class JoinController extends Controller
{
    public function list()
    {
    	$join = join::orderBy('join.id','DESC')
    		->leftJoin('user','user.user_uuid','=','join.uuid')
            // ->where('join.protocol','0')
    		->select('join.*','user.user_nickname','user.user_name','user.user_pic','user.user_phone','user.user_rank')
            ->where('join.created_at','>',date("Y-m-d",strtotime("-2 month")))
    		->paginate(20);
        // dd($join);
    	return view('admin.join-list',compact('join'));
    }

    public function add()
    {
    	return view('admin.join-add');
    }

    public function create()
    {
    	$input = Input::all();
        // 检查加盟商是否存在重复添加
        if (join::where('uuid',$input['uuid'])->first()) {
            $result = $this->result('fail','ERROR!该用户已经是加盟商,添加加盟商失败!');
        }else{
            // dd($input);
            $order = new admin_join_order;
            $order->uuid = $input['uuid'];
            $order->join_pid = $input['join'];
            // $order->price = $is_price;
            $order->log = $input['log'];
            $order->price = 100000;
            $order->type = 2;
            $order->admin = session('admin')->user_uuid;
            DB::beginTransaction();
            try {
                $order->save();
                DB::commit();
                $result = $this->result('success','添加加盟商成功,请等待财务确认');
            }catch (\Exception $e) {
                // 业务逻辑
                $result = $this->result('fail','ERROR!系统错误,添加加盟商失败!');
                //接收异常处理并回滚
                DB::rollBack();
            }
        }
    	return $result;
    }

    public function status($id)
    {
        if (join::find($id)->status) {
            $newStatus = '0';
        }else{
            $newStatus = '1';
        }
        if (isset($newStatus)) {
            if (join::where('id',$id)
                ->update(['status'=>$newStatus])) {
                $result = $this->result('success','修改成功!','');
            }else{
                $result = $this->result('fail','ERROR!修改状态失败!','');
            }
        }else{
            $result = $this->result('fail','修改状态失败!','');
        }
        return $result;
    }

    public function recharge()
    {
        return view('admin.join-recharge-add',compact('admin'));
    }

    public function addRecharge()
    {
        $input = Input::all();
        if ($input['price'] >= 100000) {
            $order = new admin_join_order;
            $order->uuid = $input['uuid'];
            $order->price = $input['price'];
            $order->type = 1;
            $order->log = $input['log'];
            $order->admin = session('admin')->user_uuid;
            if ($order->save()) {
                $result = $this->result('success','提交申请成功,请等待财务确认!');
            }else{
                $result = $this->result('fail','ERROR!数据录入失败,请检查数据长度!');
            }
        }else{
            $result = $this->result('fail','ERROR!充值金额最低不能少于10万!');
        }
        return $result;
    }

    public function protocol()
    {
        $input = Input::all();
        // dd($input);
        $join = join::find($input['id']);
        // 检查是否存在协议
        if ($join->protocol != 0) {
            $result = $this->result('fail','加盟商协议无法修改!');
        }else{
            $order = admin_join_order::find($join->order_id);

            if ($order) {
                $newPoint = $join->point + $order->price;
                switch ($input['protocol']) {
                    case '0':
                        $pointGive = 0;
                        break;
                    case '1':
                        $pointGive = $order->price * 0.3;
                        break;
                    case '2':
                        $pointGive = 0;
                        break;
                    default:
                        $pointGive = 0;
                        break;
                }
                $newPointGive = $pointGive + $join->point_give;
                // 日志记录
                $log = new log_point_join;
                $log->uuid = $join->uuid;
                $log->point = $order->price;
                $log->point_give = $pointGive;
                $log->new_point = $newPoint;
                $log->new_point_give = $newPointGive;
                $log->type = 7;
                $log->status = 1;
                // dd($log);
                DB::beginTransaction();
                try {
                    // 添加协议
                    // 增加积分(赠送的,普通的)
                    join::where('id',$input['id'])->update([
                        'protocol'=>$input['protocol'],
                        'point'=>$newPoint,
                        'point_give'=>$newPointGive
                    ]);
                    // $log->save();
                    DB::commit();
                    $result = $this->result('success','处理成功!');
                }catch (\Exception $e) {
                    // 业务逻辑
                    $result = $this->result('fail','ERROR!处理失败!');
                    //接收异常处理并回滚
                    DB::rollBack();
                }
            }else{
                $result = $this->result('fail','未查询到财务记录!');
            }
        }
        return $result;
    }


    // 接收处理添加春蚕协议
    public function spring()
    {
        $input = Input::all();
        // dd($input);
        $join = join::find($input['id']);
        if ($join->protocol != 0) {
            $result = $this->result('fail','加盟商协议无法修改!');
        }else{
            // dd($input);
            $start = $input['time']." 00:00:00";
            $end = date('Y-m-d H:i:s', strtotime ("+270 day", strtotime($start)));
            // dd($join);
            // action 补充积分,开通伯爵会员,修改数据创建时间.日志添加积分记录(包含春蚕日志)
            $user = User::where('user_uuid',$join->uuid)
                ->first();
            // 推荐的加盟商获得5000返佣积分
            $order = admin_join_order::orderBy('id','DESC')
                ->where('uuid',$join->uuid)
                ->where('agree','1')
                ->where('type','2')
                ->first();
            // dd($order);
            if ($order->join_pid) {
                $joinPid = join::where('uuid',$order->join_pid)->first();
                $newJoinPidPointFund = $joinPid->point_fund + 5000;
            }else{
                $joinPid = false;
            }
            // dd($joinPid);
            $newPoint = $user->user_point_give + $input['point'];
            // 记录用户添加的积分;
            $log = new log_point_user;
            $log->uuid = $user->user_uuid;
            $log->point = $user->user_point;
            $log->new_point = $user->user_point;
            $log->type = 2;
            $log->point_give = $user->user_point_give;
            $log->new_point_give = $newPoint;
            $log->status = 1;
            $log->add = 1;
            // 写入春蚕日志
            $logSpring = new log_point_spring();
            if ($input['price'] > 0) {
                $logSpringData = [
                    [
                        "uuid"=>$join->uuid,
                        "point"=>$user->user_point,
                        "new_point"=>$newPoint,
                        "add"=>1,
                        "type"=>2,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s')
                    ],
                    [
                        "uuid"=>$join->uuid,
                        "point"=>$join->price,
                        "new_point"=>$input['price'] + $join->price,
                        "add"=>1,
                        "type"=>1,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s')
                    ],
                ];
            }else{
                $logSpringData = [
                    [
                        "uuid"=>$join->uuid,
                        "point"=>$user->user_point,
                        "new_point"=>$newPoint,
                        "add"=>1,
                        "type"=>2,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s')
                    ]
                ];
            }
            DB::beginTransaction();
            try {
                // 添加协议
                // 增加积分(赠送的,普通的)(添加春蚕基金)
                join::where('id',$input['id'])->update([
                    'protocol'=>'2',
                    'spring_start'=>$start,
                    'spring_end'=>$end,
                    "price"=>$input['price'],
                    "pid"=>$order->join_pid
                ]);
                user::where('user_uuid',$join->uuid)->update([
                    "user_point_give"=>$newPoint,
                    "user_rank"=>4,
                    "rank_start"=>time()
                ]);
                // 增加加盟商获得5000返佣积分
                if ($joinPid) {
                    // 写入日志
                    $logJoin = new log_point_join;
                    $logJoin->uuid = $joinPid->uuid;
                    $logJoin->type = 10;
                    $logJoin->point_fund = $joinPid->point_fund;
                    $logJoin->new_point_fund = $newJoinPidPointFund;
                    $logJoin->add = 1;
                    $logJoin->status = 1;
                    $logJoin->save();
                    // 写入增加春蚕的个数
                    $joinPidInfo = join::where('uuid',$joinPid->uuid)->where('protocol','2')
                        ->first();
                    if ($joinPidInfo) {
                        join::where('uuid',$joinPid->uuid)->update([
                            "point_fund"=>$newJoinPidPointFund,
                            "spring_pid_count"=>$joinPidInfo->spring_pid_count + 1
                        ]);
                    }else{
                        join::where('uuid',$joinPid->uuid)->update([
                            "point_fund"=>$newJoinPidPointFund
                        ]);
                    }
                }
                $log->save();
                $logSpring::insert($logSpringData);
                DB::commit();
                $result = $this->result('success','处理成功!');
            }catch (\Exception $e) {
                // 业务逻辑
                $result = $this->result('fail','ERROR!处理失败!');
                //接收异常处理并回滚
                DB::rollBack();
            }
        }
        return $result;
    }

    // 提单列表
    public function order()
    {
        $order = admin_join_order::where('admin_join_order.end','0')
            ->orderBy('admin_join_order.id','DESC')
            ->leftJoin('User','user.user_uuid','=','admin_join_order.uuid')
            ->select('admin_join_order.*','user.user_name','user.user_phone')
            ->paginate(20);
        return view('admin.join-order-list',compact('order'));
    }

    // 获取加盟商提现列表
    public function cash()
    {
        $data = cash::orderBy('cash.id','DESC')
            ->whereIn('cash.type',[3,6,7])
            ->where('cash.end','0')
            ->leftJoin('user','user.user_uuid','=','cash.uuid')
            ->leftJoin('join','join.uuid','=','cash.uuid')
            ->leftJoin('bank','bank.id','=','cash.bank_id')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_logo','bank.bank_card','user.user_name','user.user_phone')
            ->where('cash.created_at','>',date("Y-m-d",strtotime("-3 month")))
            ->paginate(50);
        // dd($data);
        return view('admin.join-cash-list',compact('data'));
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

    // 取消加盟商提现
    public function retract()
    {
        $input = Input::all();
        $cash = cash::find($input['id']);
        if ($cash) {
            switch ($cash->type) {
                case '1':
                    // 债权提现
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $user = User::where('user_uuid',$cash->uuid)->first();
                        $newPrice = $user->rtsh_bond + $cash->price;
                        User::where('user_uuid',$cash->uuid)->update([
                            "rtsh_bond"=>$newPrice
                        ]);
                        // 增加撤回日志记录
                        $log = new log_rtsh;
                        $log->num = date('YmdHis').rand(100000,999999);
                        $log->uuid = $cash->uuid;
                        $log->price = $user->rtsh_bond;
                        $log->new_price = $newPrice;
                        $log->type = 10;
                        $log->desc = '撤回提现';
                        $log->status = 1;
                        $log->admin = session('admin')->user_uuid;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
                case '2':
                    // 产权提现
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $user = User::where('user_uuid',$cash->uuid)->first();
                        $newPrice = $user->rtsh_property + $cash->price;
                        User::where('user_uuid',$cash->uuid)->update([
                            "rtsh_property"=>$newPrice
                        ]);
                        // 增加撤回日志记录
                        $log = new log_rtsh;
                        $log->num = date('Y-m-d H:i:s').rand(100000,999999);
                        $log->uuid = $cash->uuid;
                        $log->price = $user->rtsh_property;
                        $log->new_price = $newPrice;
                        $log->type = 10;
                        $log->desc = '撤回提现';
                        $log->status = 1;
                        $log->admin = session('admin')->user_uuid;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
                case '3':
                    // 加盟商现金提现
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $join = join::where('uuid',$cash->uuid)->first();
                        $newPrice = $join->join_cash + $cash->price;
                        join::where('uuid',$cash->uuid)->update([
                            "join_cash"=>$newPrice
                        ]);
                        $log = new log_price_join;
                        $log->uuid = $cash->uuid;
                        $log->join_cash = $join->join_cash;
                        $log->new_join_cash = $newPrice;
                        $log->add = 1;
                        $log->status = 1;
                        $log->type = 4;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
                case '4':
                    // 加盟商债权提成
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $join = join::where('uuid',$cash->uuid)->first();
                        $newPrice = $join->rtsh_bond + $cash->price;
                        join::where('uuid',$cash->uuid)->update([
                            "rtsh_bond"=>$newPrice
                        ]);
                        $log = new log_price_join;
                        $log->uuid = $cash->uuid;
                        $log->rtsh_bond = $join->rtsh_bond;
                        $log->new_rtsh_bond = $newPrice;
                        $log->add = 1;
                        $log->status = 1;
                        $log->type = 4;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
                case '5':
                    // 加盟商产权提成
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $join = join::where('uuid',$cash->uuid)->first();
                        $newPrice = $join->rtsh_property + $cash->price;
                        join::where('uuid',$cash->uuid)->update([
                            "rtsh_property"=>$newPrice
                        ]);
                        $log = new log_price_join;
                        $log->uuid = $cash->uuid;
                        $log->rtsh_property = $join->rtsh_property;
                        $log->new_rtsh_property = $newPrice;
                        $log->add = 1;
                        $log->status = 1;
                        $log->type = 4;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
                case '6':
                    // 春蚕提现
                    DB::beginTransaction();
                    try {
                        // 删除数据
                        cash::find($input['id'])->update(['end'=>1]);
                        // 增加金额
                        $join = join::where('uuid',$cash->uuid)->first();
                        $newPrice = $join->price + $cash->price;
                        join::where('uuid',$cash->uuid)->update([
                            "price"=>$newPrice
                        ]);
                        $log = new log_point_spring;
                        $log->uuid = $cash->uuid;
                        $log->point = $join->price;
                        $log->new_point = $newPrice;
                        $log->add = 1;
                        $log->status = 1;
                        $log->type = 4;
                        $log->save();
                        DB::commit();
                        $result = $this->result('success','处理成功!');
                    }catch (\Exception $e) {
                        // 业务逻辑
                        $result = $this->result('fail','ERROR!处理失败!');
                        //接收异常处理并回滚
                        DB::rollBack();
                    }
                    break;
            }
        }else{
            $result = $this->result('fail','未找到该提现记录!');
        }
        return $result;
    }
}
