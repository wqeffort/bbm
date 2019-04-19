<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\ja_community;
use App\Model\ja_zan;
use App\Model\ja_comment;
use App\Model\ja_photo;
use App\Model\app_set;
use App\Model\ja_mark;

class IndexController extends Controller
{
    public function version()
    {
        // 获取配置信息
        $set = app_set::where('status', '1')
            ->select('ios_version', 'ios_link', 'ios_desc', 'android_version', 'android_link', 'android_desc')
            ->first();
        if ($set) {
            $result = $this->result('success', '获取成功!', $set);
        } else {
            $result = $this->result('fail', '维护中,请稍后再试!');
        }

        return $result;
    }

    /**
     * 判断商城是否显示.
     *
     *	@author wqeffort <wqeffort@sina.com>
     *
     * @return bool
     */
    public function isDisplayMall()
    {
        // 获取配置信息
        $info = app_set::where('status', '1')
        ->select('ios_version', 'android_version', 'shop_status')
        ->orderBy('created_at', 'DESC')
        ->first();

        if ($info) {
            if ($info['shop_status'] == 1) {
                $result = $this->result('success', '获取成功!', 'yes');
            } else {
                $result = $this->result('success', '获取成功!', 'no');
            }
        } else {
            $result = $this->result('fail', '维护中,请稍后再试!');
        }

        return $result;
    }

    public function sms()
    {
        $input = Input::all();
        if (isset($input['phone'])) {
            if ($this->sendSms($input['phone'])) {
                $data['code'] = session('smsCode');
                $data['phone'] = $input['phone'];
                // dd(1);
                $result = $this->result('success', '发送成功!', $data);
            } else {
                // dd(2);
                $result = $this->result('fail', '发送失败!');
            }
        } else {
            $result = $this->result('fail', '参数缺失,请传入用户手机号码');
        }

        return $result;
    }

    public function smsContent()
    {
        $input = Input::all();
        if ($this->sendNotice($input['phone'], 2, $input['price'])) {
            $result = $this->result('success', '短信发送成功!');
        } else {
            $result('fail', 'Error!短信发送失败');
        }

        return $result;
    }

    /**
     * [login/ 登录接口].
     *
     * @param phone
     * @param type option(sms,password)
     * @param value
     * @param token
     *
     * @return json
     */
    public function login()
    {
        $input = Input::all();
        switch ($input['type']) {
            case 'sms':
                if ($input['value'] == session('smsCode')) {
                    $user = User::where('user_phone', $input['phone'])->first();
                    if ($user) {
                        // 修改登录的Key
                        $key = $this->key();
                        if (User::where('user_phone', $input['phone'])
                                ->update([
                                    'user_key' => $key,
                                ])) {
                            $user = User::where('user_phone', $input['phone'])->first();
                            $result = $this->result('success', '获取数据成功!', $user);
                        } else {
                            $result = $this->result('fail', '更新用户Key失败!');
                        }
                    } else {
                        $result = $this->result('fail', '获取数据失败,未查询到用户!');
                    }
                } else {
                    $result = $this->result('fail', '短信验证码验证失败!');
                }
                break;
            case 'password':
                $user = User::where('user_phone', $input['phone'])->first();
                if ($user) {
                    if (empty($user->password)) {
                        $result = $this->result('fail', '账户密码未设置,请使用短信验证码登录!');
                    } else {
                        if (Crypt::decrypt($user->password) == $input['value']) {
                            $key = $this->key();
                            if (User::where('user_phone', $input['phone'])->update([
                                    'user_key' => $key,
                                ])) {
                                $user = User::where('user_phone', $input['phone'])->first();
                                if ($user) {
                                    $result = $this->result('success', '获取数据成功!', $user);
                                } else {
                                    $result = $this->result('fail', '获取数据失败!');
                                }
                            } else {
                                $result = $this->result('fail', '更新用户Key失败');
                            }
                        } else {
                            $result = $this->result('fail', '密码错误!');
                        }
                    }
                } else {
                    $result = $this->result('fail', '未找到该账户!');
                }
                break;
                default:
                    $result = $this->result('fail', '未知的类型参数type!');
                    break;
        }

        return $result;
    }

    // 第一次注册发送验证码
    public function oneSendSms()
    {
        $input = Input::all();
        if (User::where('user_phone', $input['phone'])->first()) {
            $result = $this->result('fail', '该手机号码已经注册,请直接通过短信验证登录');
        } else {
            if ($this->sendSms($input['phone'])) {
                $data['code'] = session('smsCode');
                $data['phone'] = $input['phone'];
                // dd(1);
                $result = $this->result('success', '发送成功!', $data);
            } else {
                // dd(2);
                $result = $this->result('fail', '发送失败!');
            }
        }

        return $result;
    }

    /**
     * [register/ 注册平台账号].
     *
     * @param phone 用户手机号码
     * @param code  短信验证码
     * @param password 用户设置的密码
     * @param token
     *
     * @return [json] [成功后返回用户信息]
     */
    public function register()
    {
        $input = Input::all();
        // return session('smsCode');
        // session(['smsCode'=>'123456']);
        if ($input['code'] == session('smsCode')) {
            // 检查电话号码
            $user = User::where('user_phone', $input['phone'])->first();
            if ($user) {
                $result = $this->result('fail', '该手机码号已经存在,请直接通过短信验证登录!');
            } else {
                $user = new User();
                $user->user_uuid = $this->uuid();
                $user->user_name = $input['phone'];
                $user->user_nickname = $input['phone'];
                $user->password = Crypt::encrypt($input['password']);
                $user->user_pic = 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132';
                $user->user_key = $this->key();
                $user->user_phone = $input['phone'];
                if ($user->save()) {
                    $result = $this->result('success', '注册成功!', $user);
                } else {
                    $result = $this->result('fail', 'ERROR!注册失败,未知错误!');
                }
            }
        } else {
            $result = $this->result('fail', '短信验证码错误!', session('smsCode'));
        }

        return $result;
    }

    public function test1()
    {
        $input = Input::all();
        session(['phone' => $input['phone']]);
        $data['phone'] = session('phone');

        return json_encode($data);
    }

    public function test2()
    {
        $data['phone'] = session('phone');

        return json_encode($data);
    }

    public function weChatOauth()
    {
        // $input = Input::all();
        // // 接收信息
        // if ($input['unionid']) {
        // 	// 查询UnionID是否被注册
        // 	$user = array();
        // 	$user['info'] = User::where('user_unionid', $input['unionid'])->first();
        // 	if ($user['info']) {
        // 		// 账户存在,则直接进行登录.检查AppOpenID是否存在.不存在则进行补全
        // 		if ($user['info']->app_openid) {
        // 			if ($user['info']->user_phone) {
        // 				$user['action'] = 'login';
        // 				$result = $this->result('success','登录成功!',$data);
        // 			}else{
        // 				$user['action'] = 'phone';
        // 				$result = $this->result('success','登录成功!',$data);
        // 			}
        // 		}else{
        // 			if (User::where('user_unionid',$input['unionid'])->update([
        // 				"app_openid"=>$input['openid']
        // 			])) {
        // 				if ($user['info']->user_phone) {
        // 					$user['action'] = 'login';
        // 					$result = $this->result('success','登录成功!',$data);
        // 				}else{
        // 					$user['action'] = 'phone';
        // 					$result = $this->result('success','登录成功!',$data);
        // 				}
        // 			}else{
        // 				$result = $this->result('fail','Error!补全OPENID失败!');
        // 			}
        // 		}
        // 	}else{
        // 		// 进行快捷注册流程!
        // 		$user['']
        // 		$result = $this->result('','')
        // 	}
        // }else{
        // 	$result = $this->result('fail','UnionID缺失,请重新进行授权登录!');
        // }
    }

    /**
     * [setUserInfo 设置用户信息].
     *
     * @param pic 用户头像,路径
     * @param nickname 用户昵称
     * @param sex 用户性别
     *
     * @return 返回用户信息json
     */
    public function setUserInfo()
    {
        $input = Input::all();
        if ($input['pic'] == '') {
            if ($input['sex'] == 1) {
                $input['pic'] == 'images/sex_man.png';
            } else {
                $input['pic'] == 'images/sex_woman.png';
            }
        }
        if ($this->isKey($input['uuid'], $input['key'])) {
            if (User::where('user_uuid', $input['uuid'])->update([
                'user_pic' => $input['pic'],
                'user_nickname' => $input['nickname'],
                'user_sex' => $input['sex'],
            ])) {
                $user = User::where('user_uuid', $input['uuid'])->first();
                $result = $this->result('success', '设置用户信息成功!', $user);
            } else {
                $result = $this->result('fail', 'ERROR,设置用户信息失败!');
            }
        } else {
            $result = $this->result('fail', '登录状态过期,验证失败,请重新登录!');
        }

        return $result;
    }

    /**
     * [setUserPic 用户头像上传].
     *
     * @param string Base64图片
     *
     * @return json 成功则返回data
     */
    public function setUserPic()
    {
        $input = Input::all();
        // dd($input);
        if (empty($input['str'])) {
            $result = $this->result('fail', '未获取到图片信息!');
        } else {
            $path = 'images/headimg';
            $data = explode(',', $input['str']);
            $type = strtolower(explode(';', explode('/', $data['0'])['1'])['0']);
            $picType = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
            if (in_array($type, $picType)) {
                $filename = date('YmdHis').mt_rand(100000, 999999).'.'.$type;
                $filepath = $path.'/'.$filename;
                if (file_put_contents($filepath, base64_decode($data['1']))) {
                    $ossPath = 'community/image/head/';
                    $oss = new Oss();
                    $oss->upload($ossPath.$filename, $filepath);
                    $info['url'] = 'https://jaclub.oss-cn-shenzhen.aliyuncs.com/'.$ossPath.$filename;
                    $result = $this->result('success', '文件上传成功!', $info);
                } else {
                    $result = $this->result('fail', '服务器内部错误，无法保存图片!');
                }
            } else {
                $result = $this->result('fail', '图片类型不正确，请重新选择图片上传!');
            }
        }

        return $result;
    }

    public function uploadFile(Request $request)
    {
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
                $data['url'] = 'https://jaclub.oss-cn-shenzhen.aliyuncs.com/'.$ossPath.$filename;
                $result = $this->result('success', '上传成功!', $data);
            } else {
                $result = $this->result('fail', '图片上传失败！!');
            }
        } else {
            $result = $this->result('fail', '没有选择图片!');
        }

        return $result;
    }

    // 多图上传
    public function uploadFileMore(Request $request)
    {
        $file = $_FILES;
        $path = 'images/ccc';
        $data = array();
        $info = array();
        if (count($file) >= 1) {
            foreach ($file as $key => $value) {
                // dd($value["error"]);
                // 验证文件类型
                if ($value['type'] != 'image/jpeg' || $value['type'] != 'image/png' || $value['type'] != 'image/jpg' || $value['type'] != 'image/gif') {
                    // 如果文件大小超过1M
                    if ($value['size'] < 1 * 1024 * 1024) {
                        $extension = explode('image/', $value['type'])['1'];
                        $filename = date('YmdHis').mt_rand(100000, 999999).'.'.$extension;
                        $filepath = $path.'/'.date('Ymd');
                        if (!file_exists($filepath)) {
                            mkdir($filepath, 0777, true);
                        }
                        if (move_uploaded_file($value['tmp_name'], $filepath.'/'.$filename)) {
                            $ossPath = 'community/image/';
                            $oss = new Oss();
                            $oss->upload($ossPath.$filename, $filepath.'/'.$filename, ['options' => $value['type']]);
                            $info['file'] = $value['name'];
                            $info['url'] = $ossPath.$filename;
                            $data[] = $info;
                            $result = $this->result('success', '上传成功', $data);
                        }
                    }
                }
            }
            // 限制尺寸大小
        } else {
            $result = $this->result('fail', '没有选择图片!');
        }

        return $result;
    }

    /**
     * 获取个人信息.
     *
     * @param uuid [uuid] // 自己的uuid
     * @param to   [uuid] // 要查询的uuid
     * @param key  [key]
     *
     * @return json
     */
    // 获取个人信息页面信息
    public function viewUserInfo()
    {
        $input = Input::all();
        $data = array();
        $data['info'] = User::where('user_uuid', $input['to'])
            ->select('user_nickname', 'user_pic')
            ->first();
        $mark = ja_mark::where('uuid', $input['uuid'])
            ->where('to', $input['to'])
            ->where('status', '1')
            ->first();
        if ($mark) {
            $data['info']['mark'] = $mark->mark;
        } else {
            $data['info']['mark'] = '';
        }
        $data['photo'] = ja_photo::orderBy('id', 'DESC')
            ->where('uuid', $input['to'])
            ->where('status', '1')
            ->take(10)
            ->get();
        $data['count'] = array();
        $data['count']['community'] = ja_community::where('uuid', $input['to'])
            ->where('status', '1')
            ->get()
            ->count();
        $data['count']['photo'] = ja_photo::where('uuid', $input['to'])
            ->where('status', '1')
            ->get()
            ->count();
        $data['count']['zan'] = ja_zan::where('uuid', $input['to'])
            ->where('status', '1')
            ->get()
            ->count();

        // 获取最新的一条社区消息
        $community['content'] = ja_community::orderBy('id', 'DESC')
            ->where('uuid', $input['to'])
            ->where('status', '1')
            ->first();
        if ($community['content']) {
            $community['zan'] = ja_zan::where('community_id', $community['content']->id)
                ->where('status', '1')
                ->get()
                ->count();
            $community['comment'] = ja_comment::where('community_id', $community['content']->id)
                ->where('status', '1')
                ->get()
                ->count();
        } else {
            $community['content'] = [
                'id' => '',
                'uuid' => '',
                'text' => '',
                'lat' => '',
                'lng' => '',
                'remind' => '',
                'poi' => '',
                'poi_text' => '',
                'type' => '',
                'secret' => '',
                'extend' => '',
                'zan_count' => '',
                'status' => '',
                'created_at' => '',
                'updated_at' => '',
            ];
            $community['zan'] = 0;
            $community['comment'] = 0;
        }
        $data['community'] = $community;

        return $this->result('success', '成功!', $data);
    }
}
