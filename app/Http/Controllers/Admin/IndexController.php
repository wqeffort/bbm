<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\log_login;
use App\Model\join;

class IndexController extends Controller
{
    // 每次请求生成一个独立的二维码,扫码获取二维码中加密字符串,通过客户端用户使用进行传送至后台验证.
    public function index()
    {
        if (session('admin')) {
            $admin = session('admin');

            return view('admin.home', compact('admin'));
        } else {
            $common = new Controller();
            // 生成登录的Key
            $key = $common->key();
            // 储存Key进行中转
            $logLogin = new log_login();
            $logLogin->key = $key;
            if ($logLogin->save()) {
                // 生成独立二维码
                $qrcode = $common->makeQrcode('http://'.env('HTTP_HOST').'/admin/login/'.$key);

                return view('admin.login', compact('qrcode', 'key'));
            } else {
                dd('生成登录Key失败!');
            }
        }
    }

    public function status()
    {
        // 检查是否存在登录状态
        if (session('admin')) {
            $result = $this->result('success', '正常!');
        } else {
            $result = $this->result('fail', '登录过期!');
        }

        return $result;
    }

    public function info()
    {
        $userAll = User::get();
        $memberAll = User::where('user_rank', '>', '0')->get()->count();
        $joinAll = join::where('protocol', '1')->get()->count();
        $springAll = join::where('protocol', '2')->get()->count();
        $rank_2 = User::where('user_rank', '2')->get()->count();
        $rank_3 = User::where('user_rank', '3')->get()->count();
        $rank_4 = User::where('user_rank', '4')->get()->count();
        $rank_5 = User::where('user_rank', '5')->get()->count();

        return view('admin.info', compact('userAll', 'memberAll', 'joinAll', 'springAll', 'rank_2', 'rank_3', 'rank_4', 'rank_5'));
    }

    public function login($key)
    {
        $input = Input::all();
        $common = new Controller();
        if (session('user') == '') {
            return redirect('/');
        } else {
            // 轮询验证Key和登录身份
            // 验证用户是否是管理员身份
            if (admin::where('uuid', session('user')->user_uuid)
                ->where('status', '1')
                ->first()) {
                if (log_login::where('key', $key)->update(['uuid' => session('user')->user_uuid, 'status' => '1'])) {
                    $result = $common->result('status', '授权登录成功!', '');
                } else {
                    $result = $common->result('fail', 'ERROR!未成功写入登录日志,请重新再试!', '');
                }
            } else {
                $result = $common->result('fail', '您没有管理权限,请勿重复挑衅!', '');
            }

            return $result;
        }
    }

    public function loginAuth($key)
    {
        $common = new Controller();
        $loginInfo = log_login::where('key', $key)
            ->where('status', '1')
            ->first();
        if ($loginInfo) {
            $admin = admin::where('admin.uuid', $loginInfo->uuid)
                ->leftJoin('user', 'user.user_uuid', '=', 'admin.uuid')
                ->where('admin.status', '1')
                ->where('user.status', '1')
                ->first();
            if ($admin) {
                session(['admin' => $admin]);
                $result = $common->result('success', '登录成功', 'admin');
            } else {
                $result = $common->result('fail', '登录失败,请稍后再试', '');
            }
        } else {
            $result = $common->result('fail', '未轮询到登录结果,继续轮询!', '');
        }

        return $result;
    }

    public function loginOut()
    {
        $common = new Controller();
        session(['admin' => '']);
        $result = $common->result('success', '注销成功,正在为您跳转!', '');

        return $result;
    }
}
