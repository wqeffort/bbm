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

class AdminController extends Controller
{
    public function index()
    {
    	$admin = admin::orderBy('id','DESC')
    		->leftJoin('user','user.user_uuid','=','admin.uuid')
    		->select('admin.*','user.user_name','user.user_nickname','user.user_uuid','user.user_pic','user.user_phone')
    		->paginate(20);
    	return view('service.pages.admin.list', compact('admin'));
    }

    // 修改管理员状态
    public function status($id)
    {
    	$admin = admin::where('uuid',session('admin')->user_uuid)->first();
    	if ($admin->rank >= 9) {
			$info = admin::find($id);
	    	if ($info->status) {
	    		$newStatus = 0;
	    	}else{
	    		$newStatus = 1;
	    	}
	    	if (admin::find($id)->update([
	    		"status"=>$newStatus
	    	])) {
	    		$result = $this->result('success','修改成功!');
	    	}else{
	    		$result = $this->result('fail','修改失败,请稍后再试!');
	    	}
    	}else{
    		$result = $this->result('fail','修改失败,您的权限不够!');
    	}
    	return $result;
    }

    // 删除管理员
    public function del($id)
    {
    	$admin = admin::where('uuid',session('admin')->user_uuid)->first();
    	if ($admin->rank >= 9) {
    		$info = admin::find($id);
    		if ($info->rank >= 9) {
    			$result = $this->result('fail','高级权限的管理员禁止删除!');
    		}else{
    			if (admin::find($id)->delete()) {
    				$result = $this->result('success','删除成功!');
    			}else{
    				$result = $this->result('fail','删除失败,请稍后再试!');
    			}
    		}
    	}else{
    		$result = $this->result('fail','修改失败,您的权限不够!');
    	}

    	return $result;
    }

    // 添加管理员
    public function add()
    {
    	return view('service.pages.admin.add');
    }

    public function addPost()
    {
    	$input = Input::all();
    	if (admin::where('uuid',$input['uuid'])->first()) {
    		$result = $this->result('fail','该管理员已经存在,请勿重复添加!');
    	}else{
    		// 检查用户是否实名
    		if (User::where('user_uuid',$input['uuid'])->first()->user_name) {
    			// 添加进入管理员列表
	    		$admin = new admin;
	    		$admin->uuid = $input['uuid'];
	    		$admin->rank = $input['rank'];
	    		$admin->cate = $input['cate'];
	    		$admin->password = Crypt::encrypt($input['password']);
				if ($admin->save()) {
					$result = $this->result('success','添加管理员成功!');
				}else{
					$result('fail','添加管理员失败,请稍后再试!');
				}
    		}else{
    			$result = $this->result('fail','该用户不能添加为管理员,请先进行实名认证!');
    		}
    	}
    	return $result;
    }




}
