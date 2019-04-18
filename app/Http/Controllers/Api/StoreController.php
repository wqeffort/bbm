<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

use App\Model\User;
use App\Model\ad;
use App\Model\goods;
use App\Model\app_banner;
use App\Model\app_ad;


class StoreController extends Controller
{
    public function index()
    {
    	$input = Input::all();
    	session(['user'=>'user_uuid']);
    	$data = array();
        $data['banner'] = app_banner::orderBy('id','DESC')
            ->where('status','1')
            ->get();
    	$data['ad'] = app_ad::orderBy('id','DESC')
    		->where('status','1')
    		->get();
    	// 获取主页推荐商品
    	// 获取新品
		$data['new'] = goods::orderBy('goods.updated_at','DESC')
	        ->where('goods.status','1')
            ->where('goods.is_new','1')
            ->get();
		// 获取热销
		$data['hot'] = goods::orderBy('goods.updated_at','DESC')
	        ->where('goods.status','1')
            ->where('goods.is_hot','1')
            ->get();

    	return $this->result('success','获取接口数据成功!',$data);
    }
}
