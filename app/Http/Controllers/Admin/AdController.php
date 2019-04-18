<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\order;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\goods;
use App\Model\article;
use App\Model\article_cate;
use Log;
class AdController extends Controller
{
    // 广告管理
    public function list()
    {
    	// 查询出所有的广告
    	$ad = ad::orderBy('id','DESC')
    		->get();

    	return view('admin.ad-list',compact('ad'));
    }

    public function show($id)
    {
    	$ad = ad::find($id);
     	return view('admin.ad-show',compact('ad'));
    }


    public function edit($id)
    {
    	$input = Input::all();
    	$common = new Controller;
    	if (ad::where('id',$id)
    		->update([
    			"title" => $input['title'],
    			"img" => $input['img'],
    			"alt" => $input['alt'],
    			"url" => $input['url'],
    			"log" => $input['log']
    		])) {
    		$result = $common->result('success','广告编辑成功!','');
    	}else{
    		$result = $common->result('fail','ERROR!未能存储数据,广告编辑失败!','');
    	}
    	return $result;
    }

    public function status($id)
    {
    	$common = new Controller;
    	if (ad::find($id)->status) {
    		$newStatus = '0';
    	}else{
    		$newStatus = '1';
    	}
    	if (isset($newStatus)) {
    		if (ad::where('id',$id)
    			->update(['status'=>$newStatus])) {
    			$result = $common->result('success','修改成功!','');
    		}else{
    			$result = $common->result('fail','ERROR!修改状态失败!','');
    		}
    	}else{
    		$result = $common->result('fail','修改状态失败!','');
    	}
    	return $result;
    }

}
