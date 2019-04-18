<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use Illuminate\Http\Response;
use Log;
class IndexController extends Controller
{
    public function index()
    {
        // dd(1);
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            // dd($app);
            session(['app'=>$app]);
            // dd($app->oauth->scopes(['snsapi_userinfo'])->redirect());
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            // dd($response);
            return $response;
        }else{
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            // 查询出广告
            $ad = ad::where('id','<','15')
                ->where('id','>','9')
                ->where('status','1')
                ->get();
            // dd(session('user'));
            return view('home.index',compact('app','ad'));
        }
    }

    public function getReverseAds()
    {
        // $input = Input::all();
        // $lat = $input['lat'];
        // $lng = $input['lng'];
        // $common = new Controller;
        // $result =  json_decode($common->curlGet($common->reverseAds($lng,$lat)));
        // if ($result->addrList['0']->status == '1') {
        //     // 截断字符串
        //     $str = explode(",", $result->addrList['0']->admName);
        //     $result = $common->result('success','成功',$str['1']);
        // }else{
            $result = $common->result('success','请求失败,未获取到城市名称','');
        // }
        return $result;
    }

    public function isUserInfo()
    {
        $input = Input::all();
        // dd($input);
        $common = new Controller;
        $user = User::where('user_uuid',$input['uuid'])
            ->first();

        if (empty($user->user_phone)) {
            $result = $common->result('fail','请补全您的用户信息','');
        }else{
            $result = $common->result('success','成功','');
        }
        return $result;
    }

    public function addUserInfoPhone()
    {
        // 添加账号认领过程
        $input = Input::all();
        if (session('smsCode') == $input['code']) {
            // 重写手机号码进行验证判断
            $res = User::where('user_phone',$input['phone'])
                ->first();
            // dd($res);
            if ($res) {
                if (empty($res->user_openid)) {
                    if ($res->old == 1) {
                        DB::beginTransaction();
                        try {
                            // 用户资料写入老用户
                            User::where('user_uuid',$res->user_uuid)
                                ->update([
                                    "user_nickname"=>session('user')->user_nickname,
                                    "user_pic"=>session('user')->user_pic,
                                    "user_city"=>session('user')->user_city,
                                    "user_openid"=>session('user')->user_openid
                                ]);
                            // 删除新得数据列
                            User::destroy(session('user')->id);
                            DB::commit();
                            // 业务逻辑
                            $info = User::where('user_phone',$input['phone'])
                                ->first();
                            session(['user'=>$info]);
                            $result = $this->result('success','补全手机号码成功!','');
                        }catch (\Exception $e) {
                                // 业务逻辑
                                $result = $this->result('fail','ERROR!系统错误,补全手机号码失败','');
                                //接收异常处理并回滚
                                DB::rollBack();
                        }
                    }else{
                        $result = $this->result('fail','ERROR!手机号码已经存在','');
                    }
                }else{
                    if (User::where('user_uuid',session('user')->user_uuid)
                            ->update(['user_phone'=>$input['phone']])) {
                        $result = $this->result('success','补全手机号码成功!','');
                    }else{
                        $result = $this->result('fail','ERROR!系统错误,无法补全手机号码','');
                    }
                }
            }else{
                if (User::where('user_uuid',session('user')->user_uuid)
                            ->update(['user_phone'=>$input['phone']])) {
                    $result = $this->result('success','补全手机号码成功!','');
                }else{
                    $result = $this->result('fail','ERROR!系统错误,无法补全手机号码','');
                }
            }
        }else{
            $result = $this->result('fail','ERROR!验证码错误!','');
        }
        return $result;
    }

    public function addUserInfoPhonePage()
    {
        return view('home.send-code');
    }
    public function sendsmsCode() {
        $input = Input::all();
        $respond = $this->smssend($input['phone']);
        return json_encode($respond);
    }
}
