<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\ads;
use App\Model\join;
use App\Model\admin_recharge_point;
use App\Model\log_point_user;
use App\Model\log_point_join;
use App\Model\log_point_spring;
use App\Model\order;
use App\Model\log_price_join;
class UserController extends Controller
{
    public function index()
    {
    	$data = User::orderBy('id','DESC')
    		->select('id','user_pic','user_uuid','user_name','user_rank','user_nickname','user_sex','created_at','updated_at','status')
    		->take(20)
    		->get();
    	return view('service.pages.member.list',compact('data'));
    }

    public function info($uuid)
    {
    	$user = User::where('user_uuid',$uuid)->first();
        // 获取用户绑定的上级用户
        if ($user->user_pid) {
            $user_pid = User::where('user_uuid',$user->user_pid)->select('user_uuid','user_name','user_nickname','user_phone','user_pic')->first();
        }else{
            $user_pid = '';
        }
        if ($user->join_pid) {
            $join_pid = User::where('user_uuid',$user->join_pid)->select('user_name','user_nickname','user_phone','user_pic')->first();
        }else{
            $join_pid = '';
        }

    	return view('service.pages.member.info',compact('user','ads','user_pid','join_pid'));
    }

    public function infoPost()
    {
        $input = Input::all();
        if ($input['password']) {
            $password = Crypt::encrypt($input['password']);
        }else{
            $password = '';
        }
        if ($input['cash_password']) {
            $cash_password = Crypt::encrypt($input['cash_password']);
        }else{
            $cash_password = '';
        }
        if (User::where('user_uuid',$input['uuid'])->update([
            "user_name"=>$input['name'],
            "user_nickname"=>$input['nickname'],
            "temp_join"=>$input['temp'],
            "password"=>$password,
            "cash_password"=>$cash_password,
            "user_uid"=>$input['uid'],
            "user_rank"=>$input['rank'],
        ])) {
            $result = $this->result('success','修改成功!');
        }else{
            $result = $this->result('fail','修改失败,请稍后再试!');
        }
        return $result;
    }

    public function infoPostAds()
    {
        $input = Input::all();
        $ads = explode('/',$input['area']);
        $province = $ads['0'];
        $city = $ads['1'];
        if (isset($ads['2'])) {
            $area = $ads['2'];
        }else{
            $area = '';
        }
        if (User::where('user_uuid',$input['uuid'])->update([
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "ads"=>$input['ads']

        ])) {
            $result = $this->result('success','修改通讯地址成功!');
        }else{
            $result = $this->result('success','ERROR!修改通讯地址失败!');
        }
        return $result;
    }

    public function infoPostUserPid()
    {
        $input = Input::all();
        $data = User::where('user_phone',$input['text'])->first();
        if ($data) {
            if (User::where('user_uuid',$input['uuid'])->update([
                "user_pid"=>$data->user_uuid
            ])) {
                $result = $this->result('success','成功!');
            }else{
                $result = $this->result('fail','绑定失败了,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','未查询到该电话号码的用户');
        }
        return $result;
    }

    public function infoPostJoinPid()
    {
        $input = Input::all();
        $data = User::where('user_phone',$input['text'])->first();
        if ($data) {
            if (User::where('user_uuid',$input['uuid'])->update([
                "join_pid"=>$data->user_uuid
            ])) {
                $result = $this->result('success','成功!');
            }else{
                $result = $this->result('fail','绑定失败了,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','未查询到该电话号码的用户');
        }
        return $result;
    }

    public function pay($uuid)
    {
        $user = User::where('user_uuid',$uuid)->first();
        return view('service.pages.member.pay',compact('user'));
    }

    // 修改用户会籍
    public function changeRank()
    {
        $input = Input::all();
        $user = User::where('user_uuid',$input['uuid'])
            ->first();
        DB::beginTransaction();
        try{
            // 修改会员等级 检查用户等级变动时间是否为空
            if ($user->rank_start) {
                User::where('user_uuid',$input['uuid'])->update([
                    "user_rank"=>$input['rank']
                ]);
            }else{
                User::where('user_uuid',$input['uuid'])->update([
                    "user_rank"=>$input['rank'],
                    "rank_start"=>time()
                ]);
            }
            if ($input['rank'] == 1) {
                User::where('user_uuid',$input['uuid'])->update([
                    "temp_join"=>1
                ]);
            }

            // 记录到admin操作日志
            $admin_log = new admin_recharge_point;
            $admin_log->uuid = $input['uuid'];
            $admin_log->admin = session('admin')->user_uuid;
            $admin_log->type = 1;
            $admin_log->log = $input['log'];
            $admin_log->save();
            DB::commit();
            $result = $this->result('success','操作成功');
        }catch (\Exception $e) {
            //接收异常处理并回滚
            DB::rollBack();
            $result = $this->result('fail','ERROR!系统繁忙,操作失败','');
        }
        return $result;
    }


    public function changePoint()
    {
        $input = Input::all();
        // dd($input);
        $user = User::where('user_uuid',$input['uuid'])->first();
        DB::beginTransaction();
        try{
            // 修改用户积分
            User::where('user_uuid',$input['uuid'])->update([
                "user_point" => $user->user_point + $input['point'],
                "user_point_give" => $user->user_point_give + $input['point_give'],
                "user_point_open" => $user->user_point_open + $input['point_open']
            ]);
            // 记录到积分日志
            $user_log = new log_point_user;
            $user_log->uuid = $input['uuid'];
            $user_log->type = 10;
            $user_log->point = $user->point;
            $user_log->point_give = $user->point_give;
            $user_log->point_open = $user->point_open;
            $user_log->new_point = $user->point + $input['point'];
            $user_log->new_point_give = $user->point_give + $input['point_give'];
            $user_log->new_point_open = $user->point_open + $input['point_open'];
            $user_log->save();
            // 记录到管理员操作日志
            $admin_log = new admin_recharge_point;
            $admin_log->uuid = $input['uuid'];
            $admin_log->admin = session('admin')->user_uuid;
            $admin_log->type = 10;
            $admin_log->point = $input['point'];
            $admin_log->point_give = $input['point_give'];
            $admin_log->point_open = $input['point_open'];
            $admin_log->log = $input['log'];
            $admin_log->save();
            DB::commit();
            $result = $this->result('success','操作成功!');
        }catch (\Exception $e) {
            //接收异常处理并回滚
            DB::rollBack();
            $result = $this->result('fail','ERROR!系统繁忙,操作失败','');
        }
        return $result;
    }


    // 获取用户积分日志
    public function pointLog($uuid)
    {
        $input = Input::all();
        $user = User::where('user_uuid',$uuid)->first();
        $join = join::where('uuid',$uuid)->first();
        // $log_user_point = log_point_user::where('uuid',$uuid)
        //     ->where('status','1')
        //     ->get()->toJson();
        // $log_user_order = order::where('uuid',$uuid)->first();
        // if ($join) {
        //     $log_join_point = log_point_join::where('uuid',$uuid)->where('status','1')->first();
        //     $log_join_spring = log_point_spring::where('uuid',$uuid)->where('status','1')->first();
        //     $log_join_price = log_price_join::where('uuid',$uuid)->where('status','1')->first();
        // }else{
        //     $log_join_point = array();
        //     $log_join_spring = array();
        //     $log_join_price = array();
        // }

        
        return view('service.pages.member.log',compact('user','join'));
    }

    public function userPointLog($uuid)
    {
        $input = Input::all();
        $data = log_point_user::where('uuid',$uuid)
            ->where('status','1')
            ->get();
        return $this->result('success','获取数据成功!',$data);
    }
}
