<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\log_point_up;
use Illuminate\Http\Response;
use Log;
class WechatController extends Controller
{
    public function server()
    {
        /**
         * 处理微信的请求消息
         *
         * @return string
         */

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 Ja Club !";
        });
        return $app->server->serve();
    }

    public function callBack()
    {
        $input = Input::all();
        $app = app('wechat.official_account');
        // session(['app'=>$app]);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $info = $user->original;
        // dd($info);
        if (empty($info)) {
            return redirect('/');
        }else{
            // 获取到用户信息后进行判断是否入库
            $userInfo = user::where('user_openid',$info['openid'])
                ->first();
            if (empty($userInfo)) {
                if (session('ACTION_USER_PID')) {
                    $userPid = session('ACTION_USER_PID');
                }else{
                    $userPid = '';
                }
                if (session('ACTION_JOIN_PID')) {
                    $joinPid = session('ACTION_JOIN_PID');
                }else{
                    $joinPid = '';
                }
                // 进行入库操作 颁发UUID
                $user = new User;
                $uuid = $this->uuid();
                $user->user_uuid = $uuid;
                $user->user_openid = $info['openid'];
                $user->user_nickname = $info['nickname'];
                $user->user_city = $info['country']." ".$info['province']." ".$info['city'];
                $user->user_pic = $info['headimgurl'];
                $user->user_sex = $info['sex'];
                $user->user_unionid = $info['unionid'];
                // 写入关系绑定
                if ($uuid == $userPid) {
                    $user->user_pid = '';
                }else{
                    $user->user_pid = $userPid;
                }
                if ($uuid == $joinPid) {
                    $user->join_pid = '';
                }else{
                    $user->join_pid = $joinPid;
                }
                session(['user'=>$user]);
                if ($user->save()) {
                    session(['ACTION_JOIN_PID'=>'']);
                    session(['ACTION_USER_PID'=>'']);
                    if (session('url')) {
                        return redirect(session('url'));
                    }else{
                        return redirect('/');
                    }
                }else{
                    return redirect('/');
                }
            }else{

                if ($userInfo->user_rank == 0) {
                    // 预判动作
                    if ($userInfo->user_pid) {
                        $userPid = $userInfo->user_pid;
                    }else{
                        if (session('ACTION_USER_PID')) {
                            if ($userInfo->user_uuid == session('ACTION_USER_PID')) {
                                $userPid = '';
                            }else{
                                $userPid = session('ACTION_USER_PID');
                            }
                        }else{
                            $userPid = '';
                        }
                    }

                    if ($userInfo->join_pid) {
                        $joinPid = $userInfo->join_pid;
                    }else{
                        if (session('ACTION_JOIN_PID')) {
                            if ($userInfo->user_uuid == session('ACTION_JOIN_PID')) {
                                $joinPid = '';
                            }else{
                                $joinPid = session('ACTION_JOIN_PID');
                            }
                        }else{
                            $joinPid = '';
                        }
                    }
                }else{
                    if ($userInfo->user_pid) {
                        $userPid = $userInfo->user_pid;
                    }else{
                        $userPid = '';
                    }
                    if ($userInfo->join_pid) {
                        $joinPid = $userInfo->join_pid;
                    }else{
                        $joinPid = '';
                    }
                    $userPid = $userPid;
                    $joinPid = $joinPid;
                }
                // session(['user'=>$userInfo]);
                // 更新用户信息
                // $pidInfo = User::where('user_uuid',session('ACTION_PID'))->first();
                // DB::beginTransaction();
                // try {
                    User::where('user_openid',$info['openid'])
                        ->update([
                            'user_pic'=>$info['headimgurl'],
                            'user_nickname'=>$info['nickname'],
                            'user_pid'=>$userPid,
                            'user_unionid'=>$info['unionid'],
                            'join_pid'=>$joinPid
                        ]);
                    $user = User::where('user_openid',$info['openid'])
                        ->first();
                    // Log::error($user);
                    session(['user'=>$user]);
                    // // 添加积分
                    // $newPoint = $pidInfo->user_point + $this->set('add_extension');
                    // User::where('user_uuid',session('ACTION_PID'))->update([
                    //     "user_point"=>$newPoint
                    // ]);
                    // // 日志记录
                    // $logPointUp = new log_point_up;
                    // $logPointUp->uuid = session('ACTION_PID');
                    // $logPointUp->point = $newPoint;
                    // $logPointUp->type = '1';
                    // $logPointUp->save();
                    DB::commit();
                    // 业务逻辑
                    session(['ACTION_JOIN_PID'=>'']);
                    session(['ACTION_USER_PID'=>'']);
                    // session(['ACTION_PID'=>'']);
                // }catch (\Exception $e) {
                //     // 业务逻辑
                //     //接收异常处理并回滚
                //     DB::rollBack();
                // }
            }
            if (empty(session('url'))) {
                return redirect('/');
            }else{
                return redirect(session('url'));
            }
        }
    }

}
