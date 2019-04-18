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
use App\Model\ad;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\bank;
use App\Model\goods;
use App\Model\card;
use App\Model\collection;
use App\Model\log_point_user;
use Log;
class JachatController extends Controller
{
    /**
	 * 登录页面
	 */
	public function index($phone)
	{
		if (session('user')) {
            session(['jachat'=>true]);
			$ad = ad::where('id','<','15')
                ->where('id','>','9')
                ->where('status','1')
                ->get();
            // dd(session('user'));
            return view('home.index',compact('ad'));
		} else {
			return view('jachat.login', compact('phone'));
		}
    }

	/**
	 * 登录验证
	 */
	public function login()
	{
		$input = Input::all();
		if ($input['code'] == session('smsCode')) {
			$user = User::where('user_phone',$input['phone'])->first();
			if ($user) {
                if ($user->user_pic) {
                    session(['user'=>$user]);
                    session(['jachat'=>true]);
                    $result = $this->result('success','登录成功!');
                } else {
                    if (User::where('user_phone',$input['phone'])->update([
                        'user_pic'=>'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132',
                        'user_nickname'=>'梦享家新注册用户'
                        ])) {
                        User::where('user_phone',$input['phone'])->first();
                        session(['user'=>$user]);
                        session(['jachat'=>true]);
                        $result = $this->result('success','登录成功!');
                    } else {
                        $result = $this->result('fail','资料无法补全,请从微信端先登录!');
                    }
                }
			}else{
				$user = new User;
				$user->user_phone = $input['phone'];
				$user->password = Crypt::encrypt('123456');
				$user->user_uuid = $this->uuid();
				$user->user_nickname = '梦享家新注册用户';
				$user->user_pic = 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132';
				if ($user->save()) {
                    session(['user'=>$user]);
                    session(['jachat'=>true]);
					$result = $this->result('success','自动注册成功!');
				} else {
					$result = $this->result('fail','注册失败,数据存储失败!');
				}
			}
		} else {
			$user = User::where('user_phone',$input['phone'])->first();
			if ($user) {
				if ($user->password) {
					if ($input['password'] == Crypt::decrypt($user->password)) {
                        session(['user'=>$user]);
                        session(['jachat'=>true]);
						// dd(session('user'));
						$result = $this->result('success','登录成功!');
					} else {
						$result = $this->result('fail','登录密码错误,请重新输入,或者使用验证码登录');
					}
				} else {
					$result = $this->result('fail','该账号未设置登录密码,请使用验证码登录方式进行登录.');
				}
			} else {
				$result = $this->result('fail','账号错误,请尝试使用验证码登录!');
			}
		}
		return $result;
	}
}
