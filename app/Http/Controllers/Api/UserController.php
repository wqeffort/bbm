<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Log;
// 加载Model
use App\Model\User;
use App\Model\log_point_user;

class UserController extends Controller
{
    //  user info set

    /**
     * [setNiceName 设置昵称].
     *
     * @param uuid
     * @param nickname
     */
    public function setNickname($value = '')
    {
        $input = Input::all();
        if (User::where('user_uuid', $input['uuid'])->update([
            'user_nickname' => $input['nickname'],
        ])) {
            $result = $this->result('success', '设置成功!');
        } else {
            $result = $this->result('fail', '设置失败,请稍后再试!');
        }

        return $result;
    }

    /**
     * [setGeneralInfo 设置通用信息].
     *
     * @param uid
     * @param name
     * @param uid_a
     * @param uid_b
     * @param sex
     */
    public function setGeneralInfo($value = '')
    {
        // code...
    }

    /**
     * [setPassword 设置登录密码].
     *
     * @param uuid
     * @param password
     */
    public function setPassword($value = '')
    {
        $input = Input::all();
        if (User::where('user_uuid', $input['uuid'])->update([
            'password' => Crypt::encrypt($input['password']),
        ])) {
            $user = User::where('user_uuid', $input['uuid'])->first();
            $result = $this->result('success', '设置成功!', $user);
        } else {
            $result = $this->result('fail', '设置失败,请稍后再试!');
        }

        return $result;
    }

    /**
     * [setCashPassword 设置提现密码].
     *
     * @param uuid
     * @param password
     */
    public function setCashPassword($value = '')
    {
        $input = Input::all();
        if (User::where('user_uuid', $input['uuid'])->update([
            'cash_password' => Crypt::encrypt($input['password']),
        ])) {
            $user = User::where('user_uuid', $input['uuid'])->first();
            $result = $this->result('success', '设置成功!', $user);
        } else {
            $result = $this->result('fail', '设置失败,请稍后再试!');
        }

        return $result;
    }

    /**
     * [setAds 设置通讯地址].
     *
     * @param uuid
     * @param province 省
     * @param city 市
     * @param area 区
     * @param ads 详细地址
     */
    public function setAds($value = '')
    {
        $input = Input::all();
        if (User::where('user_uuid', $input['uuid'])->update([
            'province' => $input['province'],
            'city' => $input['city'],
            'area' => $input['area'],
            'ads' => $input['ads'],
        ])) {
            $result = $this->result('success', '设置成功!');
        } else {
            $result = $this->result('fail', '设置失败,请稍后再试!');
        }

        return $result;
    }

    /**
     * [setBirthday 设置生日].
     *
     * @param uuid
     * @param birthday Ymd
     */
    public function setBirthday($value = '')
    {
        $input = Input::all();
        if (User::where('user_uuid', $input['uuid'])->update([
            'user_birthday' => $input['birthday'],
        ])) {
            $result = $this->result('success', '设置成功!');
        } else {
            $result = $this->result('fail', '设置失败,请稍后再试!');
        }

        return $result;
    }

    /**
     * [setPic 设置头像].
     *
     * @param string $value [description]
     */
    public function setPic(Request $request)
    {
        $input = Input::all();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // dd($file);
            $realPath = $file->getRealPath();

            $obj = array();
            if ($file->isValid()) {
                $mime = $file->getMimeType();
                $path = 'images/headimg';
                $extension = $file->getClientOriginalExtension(); //上传文件的后缀.
                $extension = $extension ? $extension : 'png';
                $filename = date('YmdHis').mt_rand(100000, 999999).'.'.$extension;
                $move = $file->move(base_path().'/public/'.$path, $filename);
                $filepath = $path.'/'.$filename;
                $ossPath = 'community/image/head/';
                $oss = new Oss();
                $oss->upload($ossPath.$filename, $filepath, ['options' => $mime]);
                $url = 'https://jaclub.oss-cn-shenzhen.aliyuncs.com/'.$ossPath.$filename;
                if (User::where('user_uuid', $input['uuid'])->update([
                    'user_pic' => $url,
                ])) {
                    $obj['url'] = $url;
                    $result = $this->result('success', '头像设置成功!', $obj);
                } else {
                    $result = $this->result('fail', '头像设置失败,请稍后再试!');
                }
            } else {
                $result = $this->result('fail', '图片上传失败！!');
            }
        } else {
            $result = $this->result('fail', '没有选择图片!');
        }

        return $result;
    }

    /**
     * [getUserPoint 获取用户积分].
     *
     * @param  uuid [用户uuid]
     *
     * @return [json] [成功返回数据,失败不返回data]
     */
    public function getUserPoint($value = '')
    {
        // 获取用户的积分详情
        $input = Input::all();
        $point = User::where('user_uuid', $input['uuid'])
            ->select('user_point', 'user_point_give', 'user_point_open')
            ->first();
        if ($point) {
            $result = $this->result('success', '成功!', $point);
        } else {
            $result = $this->result('fail', '获取信息失败,请稍后重试!');
        }

        return $result;
    }

    // get point log
    public function getPointLog()
    {
        $input = Input::all();
        $log = log_point_user::orderBy('id', 'DESC')
            ->where('uuid', $input['uuid'])
            ->where('created_at', 'like', date('Y-m').'%')
            ->where('status', '1')
            ->get();

        return $this->result('success', '获取成功!', $log);
    }

    // get log on the time
    public function getPointLogOnTheTime()
    {
        $input = Input::all();
        $log = log_point_user::orderBy('id', 'ASC')
            ->where('uuid', $input['uuid'])
            // ->where('status','1')
            ->where('created_at', 'like', $input['year'].'-'.$input['month'].'%')
            ->get();
        if ($log->isNotEmpty()) {
            $result = $this->result('success', '查询成功!', $log);
        } else {
            $result = $this->result('fail', '未获取到数据');
        }

        return $result;
    }

    // get log on the time for android
    public function getPointLogOnTheTimeForAndroid()
    {
        $input = Input::all();
        $data = log_point_user::orderBy('id', 'ASC')
            ->where('uuid', $input['uuid'])
            // ->where('status','1')
            ->where('created_at', 'like', $input['year'].'-'.$input['month'].'%')
            ->get();
        if ($data->isNotEmpty()) {
            $log = array();
            foreach ($data as $key => $value) {
                $log[$key]['id'] = $value->id;
                $log[$key]['add'] = $value->add;
                $log[$key]['status'] = $value->status;
                $log[$key]['mark'] = $value->mark;
                $log[$key]['point']['old'] = $value->point;
                $log[$key]['point']['new'] = $value->new_point;
                $log[$key]['point']['gap'] = $value->new_point - $value->point;
                $log[$key]['point']['created_at'] = $value->created_at->toDateTimeString();
                $log[$key]['point']['type'] = $value->type;
                $log[$key]['point_give']['old'] = $value->point_give;
                $log[$key]['point_give']['new'] = $value->new_point_give;
                $log[$key]['point_give']['gap'] = $value->new_point_give - $value->point_give;
                $log[$key]['point_give']['created_at'] = $value->created_at->toDateTimeString();
                $log[$key]['point_give']['type'] = $value->type;
                $log[$key]['point_open']['old'] = $value->point_open;
                $log[$key]['point_open']['new'] = $value->new_point_open;
                $log[$key]['point_open']['gap'] = $value->new_point_open - $value->point_open;
                $log[$key]['point_open']['created_at'] = $value->created_at->toDateTimeString();
                $log[$key]['point_open']['type'] = $value->type;
            }
            $result = $this->result('success', '查询成功!', $log);
        } else {
            $result = $this->result('fail', '未获取到数据');
        }

        return $result;
    }
}
