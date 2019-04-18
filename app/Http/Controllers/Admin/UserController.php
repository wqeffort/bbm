<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\log_point_user;
use App\Model\admin_recharge_point;
use App\Model\admin_join_order;
use App\Model\join;
use App\Model\join_order;
class UserController extends Controller
{
	// 获取用户列表
    public function list()
    {
    	$common = new Controller;
    	return view('admin.user-list');
    }

    public function info()
    {
        $userAll = User::get()->count();
        $userPay = User::where('user_rank','>=','1')->get()->count();
        $userFree = User::where('user_rank','0')->get()->count();
        $userJoin = join::where('protocol','1')->where('status','1')->where('type','0')->get()->count();
        $userSale = Join::where('protocol','1')->where('status','1')->where('type','1')->get()->count();
        $userSpring = join::where('protocol','2')->where('status','1')->get()->count();
        $rank1 = User::where('user_rank','1')->get()->count();
        $rank2 = User::where('user_rank','2')->get()->count();
        $rank3 = User::where('user_rank','3')->get()->count();
        $rank4 = User::where('user_rank','4')->get()->count();
        $rank5 = User::where('user_rank','5')->get()->count();
        $rank6 = User::where('user_rank','6')->get()->count();
        // 获取上一个月的充值详情 及总额
        // print_r(date('Y-m-d',strtotime("last month")));
		$joinOrder = join_order::orderBy('join_order.id','DESC')
			->join('user','join_order.uuid','=','user.user_uuid')
            ->join('user as users','join_order.to','=','users.user_uuid')
            ->select('join_order.*','user.user_name as join_name','users.user_name')
            ->where('join_order.created_at','like',date('Y-m',strtotime("last month")).'%')
			->get();
        $joinOrderRecharge = join_order::where('join_order.created_at','like',date('Y-m',strtotime("last month")).'%'
            )->where('type','1')
            ->get();
        $joinOrderPay = join_order::where('join_order.created_at','like',date('Y-m',strtotime("last month")).'%'
            )->where('type','2')
            ->get();
        // dd($joinOrder);
        return view('admin.user-info',compact('userAll','userPay','userFree','userJoin','userSale','userSpring','rank1','rank2','rank3','rank4','rank5','rank6','joinOrder','joinOrderRecharge','joinOrderPay'));
    }

    public function infoData()
    {
        $input = Input::all();
        $data['userAll'] = User::whereBetween('created_at',[$input['start'],$input['end']])
            ->get()
            ->count();
        $data['userPay'] = User::where('user_rank','>=','1')
            ->whereBetween('created_at',[$input['start'],$input['end']])
            ->get()
            ->count();
        $data['userFree'] = User::where('user_rank','0')
            ->whereBetween('created_at',[$input['start'],$input['end']])
            ->get()
            ->count();
        $data['userJoin'] = join::where('protocol','1')
            ->whereBetween('created_at',[$input['start'],$input['end']])
            ->get()
            ->count();
        $data['userSpring'] = join::where('protocol','2')
            ->whereBetween('created_at',[$input['start'],$input['end']])
            ->get()
            ->count();
        $data['rank1'] = User::where('user_rank','1')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $data['rank2'] = User::where('user_rank','2')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $data['rank3'] = User::where('user_rank','3')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $data['rank4'] = User::where('user_rank','4')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $data['rank5'] = User::where('user_rank','5')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $data['rank6'] = User::where('user_rank','6')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get()
            ->count();
        $rank= User::orderBy('rank_start','DESC')
            ->whereBetween('rank_start',[strtotime($input['start']),strtotime($input['end'])])
            ->get();
        foreach ($rank as $key => $value) {
            $value->time = date('Y-m-d H:i:s',$value->rank_start);
            switch ($value->user_rank) {
                case '0':
                    $value->rank = '普通用户';
                    break;
                case '1':
                    $value->rank = '体验会员';
                    break;
                case '2':
                    $value->rank = '男爵会员';
                    break;
                case '3':
                    $value->rank = '子爵会员';
                    break;
                case '4':
                    $value->rank = '伯爵会员';
                    break;
                case '5':
                    $value->rank = '侯爵会员';
                    break;
                case '6':
                    $value->rank = '公爵会员';
                    break;
                default:
                    $value->rank = '内部会员';
                    break;
            }
            $data['rank'][] = $value;
        }
        $result = $this->result('success','成功!',$data);
        return $result;
    }


    public function payRank()
    {
        if (session('admin')) {
            $input = Input::all();
            // 查询用户资料
            $user = User::where('user_uuid',$input['uuid'])->first();
            // 换算积分
            $temp = true;
            $isPay = true;
            switch ($input['rank']) {
                case 1:
                    // 检查用户是否购买过体验会籍
                    if ($user->temp_join) {
                        $temp = false;
                    }
                    $pay = 1000;
                    break;
                case 2:
                    $pay = 10000;
                    break;
                case 3:
                $pay = 50000;
                    break;
                case 4:
                $pay = 100000;
                    break;
                case 5:
                $pay = 200000;
                    break;
                case 6:
                $pay = 1000000;
                    break;
                case 10:
                    break;
            }
            if (($user->user_point + $user->user_point_give) >= $pay) {
                if ($temp) {
                    if ($user->user_point_give >= $pay) {
                        $newPoint = $user->user_point;
                        $newPointGive = $user->user_point_give - $pay;
                    } else {
                        $newPoint = ($user->user_point + $user->user_point_give) - $pay;
                        $newPointGive = 0;
                    }
                    
                    // 扣除积分,修改等级,写入日志
                    DB::beginTransaction();
                    try{
                        // 修改账户积分,和等级
                        if ($input['rank'] == 1) {
                            $newPointGive = $user->user_point_give + 2000;
                            User::where('user_uuid',$input['uuid'])->update([
                                "user_point"=>$newPoint,
                                "user_point_give"=>$newPointGive,
                                "user_rank"=>$input['rank'],
                                "temp_join"=>1,
                                "rank_start"=>time()
                            ]);
                        } else {
                            User::where('user_uuid',$input['uuid'])->update([
                                "user_point"=>$newPoint,
                                "user_point_give"=>$newPointGive,
                                "user_rank"=>$input['rank'],
                                "rank_start"=>time()
                            ]);
                        }
                        // 写入日志
                        $log = new log_point_user;
                        $log->uuid = $input['uuid'];
                        $log->point = $user->user_point;
                        $log->point_give = $user->user_point_give;
                        $log->type = 8;
                        $log->new_point = $newPoint;
                        $log->new_point_give = $newPointGive;
                        $log->status = 1;
                        $log->add = 2;
                        $log->save();
                        // 写入管理员日志
                        $admin = new admin_recharge_point;
                        $admin->uuid = $input['uuid'];
                        $admin->admin = session('admin')->user_uuid;
                        $admin->point = $user->point - $newPoint;
                        $admin->point_give = $user->point_give - $newPointGive;
                        $admin->type = 1;
                        $admin->log = $input['log'];
                        $admin->status = 1;
                        $admin->save();
                        DB::commit();
                        $result = $this->result('success','会籍修改成功!');
                    }catch (\Exception $e) {
                        //接收异常处理并回滚
                        DB::rollBack();
                        $result = $this->result('fail','ERROR!当前系统繁忙,修改失败!');
                    }
                } else {
                    $result = $this->result('fail','该用户已经购买过体验会籍,无法再次购买');
                }
            }else{
                $result = $this->result('fail','购买会籍失败,用户账户积分不足!');
            }
        } else {
            $result = $this->result('fail','管理员登录状态已经过期,请重新登录!');
        }
        return $result;
    }
    // 后台为用户充值
    public function recharge()
    {
        $input = Input::all();
        if (session('admin')) {
            if ($input['type'] == '0') {
                // 仿照加盟商充值.提交给财务进行处理
                if ($input['price'] >= 10000) {
                    $order = new admin_join_order;
                    $order->uuid = $input['uuid'];
                    $order->price = $input['price'];
                    $order->type = 3;
                    $order->log = $input['log'];
                    $order->admin = session('admin')->user_uuid;
                    if ($order->save()) {
                        $result = $this->result('success','提交申请成功,请等待财务确认!');
                    }else{
                        $result = $this->result('fail','ERROR!数据录入失败,请检查数据长度!');
                    }
                }else{
                    $result = $this->result('fail','ERROR!充值金额最低不能少于1万!');
                }
                return $result;
            }else{
                $user = User::where('user_uuid',$input['uuid'])->first();
                $newPoint = $user->user_point + $input['point'];
                $newPointGive = $user->user_point_give + $input['point_give'];
                DB::beginTransaction();
                try{
                    // 变动账户
                    User::where('user_uuid',$input['uuid'])->update([
                        "user_point"=>$newPoint,
                        "user_point_give"=>$newPointGive
                    ]);
                    // 写入日志
                    $log = new log_point_user;
                    $log->uuid = $input['uuid'];
                    $log->point = $user->user_point;
                    $log->point_give = $user->user_point_give;
                    $log->type = $input['type'];
                    $log->new_point = $newPoint;
                    $log->new_point_give = $newPointGive;
                    $log->status = 1;
                    $log->add = 1;
                    $log->save();
                    // 写入管理员日志
                    $admin = new admin_recharge_point;
                    $admin->uuid = $input['uuid'];
                    $admin->admin = session('admin')->user_uuid;
                    $admin->point = $input['point'];
                    $admin->point_give = $input['point_give'];
                    $admin->type = $input['type'];
                    $admin->log = $input['log'];
                    $admin->status = 1;
                    $admin->save();
                    DB::commit();
                    $result = $this->result('success','积分充值成功!');
                }catch (\Exception $e) {
                    //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail','ERROR!当前系统繁忙,充值失败!');
                }
            }
        }else{
            $result = $this->result('fail','管理员登录状态已经过期,请重新登录!');
        }
    	return $result;
    }
}
