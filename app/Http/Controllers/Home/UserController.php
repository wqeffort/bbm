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
use App\Model\order;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\bank;
use App\Model\goods;
use App\Model\join;
use App\Model\card;
use App\Model\collection;
use App\Model\log_point_user;
use Log;
class UserController extends Controller
{
	// 获取到用户的积分
    public function getUserPoint()
    {
    	$info = User::where('user_uuid',session('user')->user_uuid)
    		->first();
    	// dd($data);
    	if (!isset($info)) {
    		$result = $this->result('fail','ERROR!获取积分失败,请稍后再试!','');
    	}else{
            $data = $info->user_point + $info->user_point_give + $info->user_point_open;
    		$result = $this->result('success','成功',$data);
    	}
    	return $result;
    }

    public function index()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            $user = User::where('user_uuid',session('user')->user_uuid)
                ->where('status','1')
                ->first();
            $collection = collection::where('uuid',session('user')->user_uuid)->where('status','1')->get()->count();
            $card = card::where('uuid',session('user')->user_uuid)
                ->where('status','1')
                ->get()->count();
        }
        return view('home.user',compact('app','user','collection','card'));
    }

    // 获取到用户得所有订单
    public function allSend()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            // dd(session('user'));
            // 获取用户的所有订单
            $data = order::orderBy('status','ASC')
                ->where('order.uuid',session('user')->user_uuid)
                ->where('order.status','1')
                ->rightJoin('goods','goods.id','=','order.goods_id')
                ->orderBy('order.id','DESC')
                ->select('goods.goods_name','goods.goods_pic','order.*')
                ->get()->groupBy('num');
            // dd($data);
            foreach ($data as $key => $value) {
                $totalPrice = 0;
                $totalPoint = 0;
                // dd($value);
                foreach ($value as $v) {
                    $totalPrice += $v->price;
                    $totalPoint += $v->point;
                    $type = $v->type;
                    $orderNum = $v->num;
                    $time = $v->created_at;
                    $status = $v->status;
                    $express = $v->express_status;
                    $sale = $v->sale;
                }
                $value->sale = $sale;
                $value->payType = $type;
                $value->totalPrice = $totalPrice;
                $value->totalPoint = $totalPoint;
                $value->orderNum = $orderNum;
                $value->created_at = $time;
                $value->status = $status;
                $value->express = $express;
                $order[] = $value;
            }
            // dd($order);
            return view('home.order-send-all',compact('app','order'));
        }
    }
    // 获取到用户的未发货订单
    public function noSend()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            // dd(session('user'));
            // 获取用户的所有订单
            $data = order::orderBy('status','ASC')
                ->where('order.uuid',session('user')->user_uuid)
                ->where('order.status','1')
                ->where('order.express_status','0')
                ->rightJoin('goods','goods.id','=','order.goods_id')
                ->orderBy('order.id','DESC')
                ->select('goods.goods_name','goods.goods_pic','order.*')
                ->get()->groupBy('num');
            // dd($data);
            foreach ($data as $key => $value) {
                $totalPrice = 0;
                $totalPoint = 0;
                // dd($value);
                foreach ($value as $v) {
                    $totalPrice += $v->price;
                    $totalPoint += $v->point;
                    $type = $v->type;
                    $orderNum = $v->num;
                    $time = $v->created_at;
                    $status = $v->status;
                    $sale = $v->sale;
                }
                $value->sale = $sale;
                $value->payType = $type;
                $value->totalPrice = $totalPrice;
                $value->totalPoint = $totalPoint;
                $value->orderNum = $orderNum;
                $value->created_at = $time;
                $value->status = $status;
                $order[] = $value;
            }
            // dd($order);
            return view('home.order-send-no',compact('app','order'));
        }
    }

    // 获取到用户的待收货订单
    public function onSend()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            // dd(session('user'));
            // 获取用户的所有订单
            $data = order::orderBy('status','ASC')
                ->where('order.uuid',session('user')->user_uuid)
                ->where('order.status','1')
                ->where('order.express_status','2')
                ->whereNotNull('express')
                ->rightJoin('goods','goods.id','=','order.goods_id')
                ->orderBy('order.id','DESC')
                ->select('goods.goods_name','goods.goods_pic','order.*')
                ->get()->groupBy('num');
            // dd($data);
            foreach ($data as $key => $value) {
                $totalPrice = 0;
                $totalPoint = 0;
                // dd($value);
                foreach ($value as $v) {
                    $totalPrice += $v->price;
                    $totalPoint += $v->point;
                    $type = $v->type;
                    $orderNum = $v->num;
                    $time = $v->created_at;
                    $status = $v->status;
                    $sale = $v->sale;
                }
                $value->sale = $sale;
                $value->payType = $type;
                $value->totalPrice = $totalPrice;
                $value->totalPoint = $totalPoint;
                $value->orderNum = $orderNum;
                $value->created_at = $time;
                $value->status = $status;
                $order[] = $value;
            }
            return view('home.order-send-on',compact('app','order'));
        }
    }

    // 设置用户信息页面
    public function setInfo()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $user = User::where('user_uuid',session('user')->user_uuid)
                ->first();
            $bank = bank::where('uuid',$user->user_uuid)->first();

            return view('home.user-set',compact('app','user','bank'));
        }
    }

    // 设置和修改提现密码
    public function cashPasswordPage()
    {
        // 检查用户是否设置了提现密码
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            if ($cash_password = User::where('user_uuid',session('user')->user_uuid)
                ->first()->cash_password) {
                $cashPassword = Crypt::decrypt($cash_password);
                return view('home.user-edit-password',compact('app','cashPassword'));
            }else{
                return view('home.user-set-password',compact('app','cashPassword'));
            }
        }
    }

    public function cashPassword()
    {
        $input = Input::all();
        if ($input['code'] == session('smsCode')) {
            if (User::where('user_uuid',session('user')->user_uuid)
                ->update([
                    "cash_password"=>Crypt::encrypt($input['password'])
                ])) {
                $result = $this->result('success','设置提现密码成功!','');
            }else{
                $result = $this->result('fail','设置提现密码失败,请稍后再试!','');
            }
        }else{
            $result = $this->result('fail','验证码错误!');
        }
        return $result;
    }

    public function verifyCashPassword()
    {
        $input = Input::all();
        $pw = User::where('user_uuid',session('user')->user_uuid)
            ->first()->cash_password;
        if (Crypt::decrypt($pw) == $input['password']) {
            $result = $this->result('success','密码验证成功,请输入新密码!','');
        }else{
            $result = $this->result('fail','密码验证失败,请重新输入','');
        }
        return $result;
    }

    public function uidPage()
    {
        $app = app('wechat.official_account');
        if (empty(session('user'))) {
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $user = User::where('user_uuid',session('user')->user_uuid)
                ->first();
            // $uid = $user->user_uid;
            // $uid_type = 1;
            // $uid_a = $user->user_uid_a;
            // $uid_b = $user->user_uid_b;
            // $name = $user->user_name;
            return view('home.user-set-uid',compact('app','user'));
        }
    }

    public function uid()
    {
        $input = Input::all();
        if ($input['user_sex'] == '男') {
            $user_sex = 1;
        }else{
            $user_sex = 2;
        }

        if ($input['user_uid_type'] == '身份证') {
            $user_uid_type = 1;
        }else{
            $user_uid_type = 2;
        }
        if (User::where('user_uuid',session('user')->user_uuid)
            ->update([
                "user_name" => $input['user_name'],
                "user_uid" => $input['user_uid'],
                "user_sex" => $user_sex,
                "user_uid_type" => $user_uid_type,
                "user_birthday" => $input['user_birthday'],
                "user_uid_a" => $input['user_uid_a'],
                "user_uid_b" => $input['user_uid_b']
            ])) {
            session(['user'=>User::where('user_uuid',session('user')->user_uuid)->first()]);
            $result = $this->result('success','信息提交成功','');
        }else{
            $result = $this->result('fail','信息修改失败,请稍后再试!','');
        }
        return $result;
    }

    public function rechargePage()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.recharge',compact('app','user'));
    }

    public function member()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member',compact('app','user'));
    }

    public function member0()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member0',compact('app','user'));
    }

    public function member1()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member1',compact('app','user'));
    }

    public function member2()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member2',compact('app','user'));
    }

    public function member3()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member3',compact('app','user'));
    }

    public function member4()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.member4',compact('app','user'));
    }

    public function loginOut()
    {
        session(['user'=>'']);
        $app = app('wechat.official_account');
        return view('home.login',compact('app'));
    }

    public function loginPassword()
    {
        $input = Input::all();
        if ($user = User::where('user_phone',$input['phone'])
            ->first()) {
            if ($input['password']) {
                if ($input['password'] == Crypt::decrypt($user->password)) {
                session(['user'=>$user]);
                    $result = $this->result('success','登录成功!');
                }else{
                    $result = $this->result('fail','你输入的密码于账号不匹配!');
                }
            }else{
                $result = $this->result('fail','你的账号未设置登录密码,请到个人中心中进行设置,否则只能微信登录!');
            }
        }else{
            $result = $this->result('fail','您输入的账号不存在!');
        }
        return $result;
    }

    public function collection()
    {
        $app = app('wechat.official_account');
        $goods = collection::orderBy('collection.id','DESC')
            ->leftJoin('goods','collection.goods_id','=','goods.id')
            ->where('collection.uuid',session('user')->user_uuid)
            ->where('collection.status','1')
            ->get();
        // dd($goods);
        return view('home.collection-list',compact('app','goods'));
    }

    public function setPassword()
    {
        // 用户重置登录密码
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
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
            if (User::where('user_uuid',session('user')->user_uuid)
                ->update([
                    "password" => Crypt::encrypt($input['password'])
                ])) {
                $result = $this->result('success','会员登录密码修改成功!');
            }else{
                $result = $this->result('fail','会员登录密码修改失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','短信验证码错误!');
        }
        return $result;
    }

    public function walletUser()
    {
        // 查询出用户账户的积分流水总和
    }

    public function pointList()
    {
        $app = app('wechat.official_account');
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        $log = log_point_user::orderBy('id','DESC')
            ->where('uuid',session('user')->user_uuid)->get();
        return view('home.user-asset-point',compact('app','user','log'));
    }

    public function setAdsPage()
    {
        // 查询出用户的现在的地址
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        // 获取到所有的收货地址
        $ads = ads::where('uuid',session('user')->user_uuid)->where('del','0')->get();
        return view('home.user-set-ads',compact('user','ads'));
    }

    public function setAds()
    {
        $input = Input::all();
        $ads = explode('/',$input['city']);
        $province = $ads['0'];
        $city = $ads['1'];
        if (isset($ads['2'])) {
            $area = $ads['2'];
        }else{
            $area = '';
        }
        if (User::where('user_uuid',session('user')->user_uuid)->update([
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "ads"=>$input['ads']

        ])) {
            $result = $this->result('success','保存通讯地址成功!');
        }else{
            $result = $this->result('success','ERROR!保存通讯地址失败!');
        }
        return $result;
    }

    public function setAdsAdd()
    {
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        $name = $user->user_name;
        $phone = $user->user_phone;
        return view('home.user-set-ads-add',compact('name','phone'));
    }

    public function setAdsDel()
    {
        $input = Input::all();
        // 删除用户地址
        if (ads::find($input['id'])->update(['del'=>1])) {
            $result = $this->result('success','删除地址成功!');
        }else{
            $result = $this->result('fail','删除收货地址失败,请稍后再试!');
        }
        return $result;
    }

    public function setAdsEdit($id)
    {
        $ads = ads::find($id);
        return view('home.user-set-ads-edit', compact('ads'));
    }


    public function handlePid(Request $request)
    {
        $input = Input::all();
        // dd($input);
        if (!isset($input['join_pid'])) {
            $input['join_pid'] = '';
        }else{
            if (!$input['user_pid']) {
                $input['user_pid'] = '';
            }else{
                // 查询分享的用户是否是加盟商或者合伙人
                $join = join::where('uuid',$input['user_pid'])
                    ->where('status','1')
                    ->first();
                if ($join) {
                    $input['join_pid'] = $join->uuid;
                }
            }
        }
        // 检查登录状态,如果登录则开始执行逻辑.如果没登录则保存预判动作存入session
        if (session('user')) {
            $user = User::where('user_uuid',session('user')->user_uuid)->first();
            if (empty($user->join_pid)) {
                $pidJoin = $input['join_pid'];
            }else{
                $pidJoin = $user->join_pid;
            }
            if (empty($user->user_pid)) {
                $pidUser = $input['user_pid'];
            }else{
                $pidUser = $user->user_pid;
            }
            if ($user->user_rank == 0) {
                if (User::where('user_uuid',session('user')->user_uuid)
                    ->update([
                        'user_pid'=>$pidUser,
                        'join_pid'=>$pidJoin
                    ])) {
                    $user = User::where('user_uuid',session('user')->user_uuid)->first();
                    session(['user'=>$user]);
                    return redirect('/');
                }else{
                    return redirect('/');
                }
            }else{
                return redirect('/');
            }
        }else{
            // 存储session到预判动作
            session(['ACTION_USER_PID'=>$input['user_pid']]);
            session(['ACTION_JOIN_PID'=>$input['join_pid']]);
            return redirect('/');
        }
    }

    public function getUserRank()
    {
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        if ($user) {
            session(['user'=>$user]);
            $result = $this->result('success','成功!',$user->user_rank);
        }else{
            $result = $this->result('fail','查询失败,请刷新页面后再试!');
        }
        return $result;
    }
}
