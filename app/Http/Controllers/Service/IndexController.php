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

use App\Http\Controllers\Api\Oss;

class IndexController extends Controller
{
    public function index()
    {
    	$admin = session('admin');
        // session(['admin'=>'']);
    	return view('service.index',compact('admin'));
    }

    public function login()
    {
    	return view('service.login');
    }

    public function loginPost()
    {
    	$input = Input::all();
    	$user = User::where('user_phone',$input['username'])->first();
    	if ($user) {
    		if ($user->password) {
    			if (Crypt::decrypt($user->password) == $input['password']) {
	    			$admin = admin::where('uuid',$user->user_uuid)
	    				->first();
	    			if ($admin) {
	    				if ($admin->status) {
	    					session(['admin'=>$user]);
	    					$result = $this->result('success','登录成功!');
	    				}else{
	    					$result = $this->result('fail','您的管理员权限已被禁用!');
	    				}
	    			}else{
	    				$result = $this->result('fail','您不是管理员,无权登录后台管理系统!');
	    			}
	    		}else{
	    			$result = $this->result('fail','密码错误,请重新输入');
	    		}
    		}else{
    			$result = $this->result('fail','当前账户未设置密码!');
    		}
    	}else{
    		$result = $this->result('fail','当前用户未找到,请确认您输入的用户名是否正确!');
    	}
    	return $result;
    }

    public function info()
    {
        return view('service.pages.info');
    }

    public function img(Request $request)
    {
        if ($request->hasFile('file'))
        {
            $file = $request->file('file');
            // dd($file);
            $realPath = $file->getRealPath();

            $obj=array();
            if($file-> isValid()){
                $mime = $file->getMimeType();
                $path = 'images/images';
                $extension = $file->getClientOriginalExtension(); //上传文件的后缀.
                $extension=$extension? $extension:"png";
                $filename = date('YmdHis').mt_rand(100000,999999).'.'.$extension;
                $move = $file -> move(base_path().'/public/'.$path,$filename);
                $filepath = $path."/".$filename;
                $ossPath = 'service/';
                $oss = new Oss;
                $oss->upload($ossPath.$filename, $filepath,['options'=>$mime]);
                $data['url'] = "https://jaclub.oss-cn-shenzhen.aliyuncs.com/".$ossPath.$filename;
                $result = $this->result('success','上传成功!',$data);
            }else{
                $result = $this->result('fail','图片上传失败！!');
            }
        }else{
            $result = $this->result('fail','没有选择图片!');
        }
        return $result;
    }
}
