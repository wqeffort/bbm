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
use App\Model\log_point_user;
use App\Model\cash;
use Log;
class FinanceController extends Controller
{
    public function list()
    {
    	$order = admin_join_order::orderBy('admin_join_order.id','DESC')
    		->where('admin_join_order.agree','0')
    		->leftJoin('user','user.user_uuid','=','admin_join_order.uuid')
    		->select('admin_join_order.*','user.user_pic','user.user_name','user.user_uid','user.user_phone','user.user_uid_a')
    		->get();
    	$data = array();
    	foreach ($order as $key => $value) {
    		$value->admin = User::where('user_uuid',$value->admin)->first()->user_name;
    		$data[] = $value;
    	}
    	// dd($data);
    	return view('admin.finance-list',compact('data'));
    }

    public function cash()
    {
        $data = cash::orderBy('cash.id','DESC')
            ->where('cash.status','1')
            ->whereIn('cash.type',[3,6,7])
            ->where('end','0')
            ->leftJoin('bank','cash.bank_id','=','bank.id')
            ->leftJoin('user','user.user_uuid','=','cash.uuid')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_phone','bank.bank_logo','bank.bank_location','user.user_phone','user.user_name')
            ->get();
        // dd($data);
        return view('admin.finance-cash',compact('data'));
    }

    public function showCash($id)
    {
        $data = cash::orderBy('cash.id','DESC')
            ->where('cash.status','1')
            ->where('cash.id',$id)
            ->where('end','0')
            ->leftJoin('bank','cash.bank_id','=','bank.id')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_phone','bank.bank_logo','bank.bank_location')
            ->first();
        // dd($data);
        return view('admin.finance-cash-show',compact('data'));
    }

    public function handleCash($id)
    {
        $input = Input::all();
        if (cash::where('id',$id)->update([
            'status'=>$input['status'],
            'img'=>$input['img'],
            'log'=>$input['log'],
            'admin'=>session('admin')->user_uuid
        ])) {
            $result = $this->result('success','处理成功!');
        }else{
            $result = $this->result('fail','未知错误,处理失败!');
        }
        return $result;
    }


    public function show($id)
    {
    	$input = Input::all();
    	$order = $order = admin_join_order::where('admin_join_order.id',$id)
    		->leftJoin('user','user.user_uuid','=','admin_join_order.uuid')
    		->select('admin_join_order.*','user.user_pic','user.user_name','user.user_uid','user.user_phone','user.user_uid_a')
    		->first();
    	// dd($order->img);
    	return view('admin.finance-show',compact('order'));
    }

    public function edit($id)
    {
        $input = Input::all();
        // 查询出订单信息
        $order = admin_join_order::find($id);
        if ($order->agree == '1') {
            $result = $this->result('fail','禁止重复处理订单!');
        }else{
            switch ($order->type) {
                case '1':
                    // 获取到加盟商信息
                    $join = join::where('uuid',$order->uuid)->first();
                    // 充值
                    // 日志记录
                    $log = new log_point_join;
                    $log->uuid = $order->uuid;
                    $log->type = 3;
                    $log->point = $join->point;
                    $log->point_give = $join->point_give;
                    $log->new_point = $order->price + $join->point;
                    $log->new_point_give = ($order->price * 0.3) + $join->point_give;
                    $log->status = 1;
                    $log->add = 1;

                    // 检查加盟商充值是否满50 (满40 加这次共计50)
                    $sum = admin_join_order::where('uuid',$order->uuid)
                        ->where('type','1')
                        ->where('status','1')
                        ->where('agree','1')
                        ->get();
                    if ($sum->isNotEmpty()) {
                        if ($sum->sum('price') >= 400000) {
                            $joinSum = true;
                        }else{
                            $joinSum = false;
                        }
                    }else{
                        $joinSum = false;
                    }
                    break;
                case '2':
                    $joinSum = false;
                    // 开通加盟商
                    // // 日志记录
                    // $log = new log_point_join;
                    // $log->uuid = $order->uuid;
                    // $log->type = 7;
                    // $log->point = 0;
                    // $log->point_give = 0;
                    // $log->new_point = $order->price;
                    // $log->new_point_give = $order->price * 0.3;
                    // $log->status = 1;
                    // $log->add = 1;

                    // 新建加盟商数据咧
                    $newJoin = new join;
                    $newJoin->uuid = $order->uuid;
                    $newJoin->order_id = $id;
                    // $newJoin->point = $order->price;
                    // $newJoin->point_give = $order->price * 0.3;
                    // 写入初始密码 123456
                    $newJoin->password = 'eyJpdiI6ImpiRDM0cXF0QXBDYnh0QlhzZkpoY2c9PSIsInZhbHVlIjoiaU1NWnVxd3RXSmt3a2pBVkV2cWJMUT09IiwibWFjIjoiZDAzZDBkYTkyNmE4MTQ5N2E5ZmE2ZWJjZTRjNzZhOGYyZDFiYmVhODY2YjlmYTY5ZGU1YTVhMjRlZWZjNTM0ZSJ9';
                    break;
                case '3':
                    // 处理后台用户线下打款充值
                    $user = User::where('user_uuid',$order->uuid)
                        ->first();
                    // 写入用户积分日志
                    $userLog = new log_point_user;
                    $userLog->uuid = $order->uuid;
                    $userLog->point = $user->user_point;
                    $userLog->point_give = $user->user_point_give;
                    $userLog->type = 10;
                    switch ($user->user_rank) {
                            case '2':
                                $addGivePoint = $order->price * 0.05;
                                break;
                            case '3':
                                $addGivePoint = $order->price * 0.1;
                                break;
                            case '4':
                                $addGivePoint = $order->price * 0.15;
                                break;
                            case '5':
                                $addGivePoint = $order->price * 0.2;
                                break;
                            case '6':
                                $addGivePoint = $order->price * 0.25;
                                break;
                            default:
                                $addGivePoint = 0;
                                break;
                    }
                    $userLog->new_point = $user->user_point + $order->price;
                    $userLog->new_point_give =$user->user_point_give + $addGivePoint;
                    $userLog->status = 1;
                    $userLog->add = 1;
                    $joinSum = false;
                    break;
            }
            DB::beginTransaction();
            try {
                switch ($order->type) {
                    case '1':
                        // 进行账户变动
                        join::where('uuid',$order->uuid)
                            ->update([
                                "point"=>$order->price + $join->point,
                                "point_give"=>($order->price * 0.3) + $join->point_give
                            ]);
                        $log->save();
                        break;
                    case '2':
                        $newJoin->save();
                        break;
                    case '3':
                        // 日志入库
                        $userLog->save();
                        // 进行账户变动
                        User::where('user_uuid',$user->user_uuid)->update([
                            "user_point"=>$user->user_point + $order->price,
                            "user_point_give"=>$user->user_point_give + $addGivePoint
                        ]);
                        // 发放加盟商提成.默认发放到绑定的加盟商
                        if ($user->join_pid) {
                            $join = join::where('uuid',$user->join_pid)->first();
                            // 写入加盟商提成日志
                            $joinLog = new log_point_join;
                            $joinLog->uuid = $user->join_pid;
                            $joinLog->point_fund = $join->point_fund;
                            $joinLog->new_point_fund = $join->point_fund + ($order->price * 0.25);
                            $joinLog->type = 12;
                            $joinLog->status = 1;
                            $joinLog->add = 1;
                            $joinLog->save();
                            // 派发提成
                            join::where('uuid',$user->join_pid)->update([
                                "point_fund"=>$join->point_fund + ($order->price * 0.25)
                            ]);
                        }
                        break;
                }
                if ($joinSum) {
                    // 修改提单状态
                    admin_join_order::where('id',$id)
                        ->update([
                            "agree"=>$input['agree'],
                            "img"=>$input['img'],
                            "status"=>1,
                            "log"=>$input['log'],
                            "end"=>1
                        ]);
                    admin_join_order::where('uuid',$order->uuid)
                        ->where('type','1')
                        ->where('status','1')
                        ->where('agree','1')
                        ->update(['end'=>1]);

                    // 获取到奖励的加盟商
                    if ($join->pid) {
                        $joinPid = join::where('uuid',$join->pid)
                            ->first();
                        // 写入奖励日志
                        $joinLog = new log_point_join;
                        $joinLog->uuid = $join->pid;
                        $joinLog->point_give = $joinPid->point_give;
                        $joinLog->new_point_give = $joinPid->point_give + 50000;
                        $joinLog->type = 11;
                        $joinLog->status = 1;
                        $joinLog->add = 1;
                        $joinLog->save();
                        // 修改账户
                        join::where('uuid',$join->pid)->update([
                            "point_give"=>$joinLog->new_point_give
                        ]);
                    }
                }else{
                    // 修改提单状态
                    admin_join_order::where('id',$id)
                        ->update([
                            "agree"=>$input['agree'],
                            "img"=>$input['img'],
                            "status"=>1,
                            "log"=>$input['log']
                        ]);
                }
                // 写入加盟商积分变动日志
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

    public function cashAll()
    {
        // 查询处所有出账的列表
        $data = cash::orderBy('cash.id','ASC')
            ->whereIn('cash.type',[3,6])
            ->where('end','0')
            ->leftJoin('bank','cash.bank_id','=','bank.id')
            ->leftJoin('user','user.user_uuid','=','cash.uuid')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_phone','bank.bank_logo','bank.bank_location','user.user_name','user.user_phone')
            ->paginate(50);
        // dd($data);
        return view('admin.finance-cash-all',compact('data'));
    }

    public function foreignCash()
    {
        $data = cash::orderBy('cash.id','ASC')
            ->where('cash.status','1')
            ->where('end','0')
            ->whereIn('cash.type',[1,2,4,5])
            ->leftJoin('bank','cash.bank_id','=','bank.id')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_phone','bank.bank_logo','bank.bank_location')
            ->get();
        // dd($data);
        return view('admin.foreign-cash',compact('data'));
    }

    public function foreignCashAll()
    {
        $data = cash::orderBy('cash.id','DESC')
            ->whereIn('cash.type',[1,2,4,5])
            ->where('end','0')
            ->leftJoin('bank','cash.bank_id','=','bank.id')
            ->select('cash.*','bank.bank_card','bank.name','bank.bank_name','bank.bank_phone','bank.bank_logo','bank.bank_location')
            ->paginate(50);
        // dd($data);
        return view('admin.foreign-cash-all',compact('data'));
    }
}
