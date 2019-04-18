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

class SearchController extends Controller
{
    public function userInfo()
    {
    	$input = Input::all();
    	if (is_numeric($input['text'])) {
    		$data = User::where('user_phone',$input['text'])
    			->select('user_pic','user_phone','user_rank','user_uuid','user_name','user_nickname','user_sex','created_at','updated_at')
    			->get();
    	}else{
    		if (strlen($input['text']) > 20) {
    			$data = User::where('user_uuid','like','%'.$input['text'].'%')
    				->select('user_pic','user_phone','user_rank','user_uuid','user_name','user_nickname','user_sex','created_at','updated_at')
    				->get();
    		}else{
    			$data = User::where('user_name','like','%'.$input['text'].'%')
    				->select('user_pic','user_phone','user_rank','user_uuid','user_name','user_nickname','user_sex','created_at','updated_at')
    				->get();
    		}
    	}
    	if ($data->isNotEmpty()) {
    		$result = $this->result('success','获取数据成功!',$data);
    	}else{
    		$result = $this->result('fail','未获取到数据');
    	}
    	return $result;
    }
}
