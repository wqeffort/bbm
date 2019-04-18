<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\brand;
class BrandController extends Controller
{
    public function list()
    {
    	$brand = brand::orderBy('id','DESC')
    		->paginate(10);
    	return view('admin.brand-list',compact('brand'));
    }

    public function show($id)
    {
    	$brand = brand::find($id);
     	return view('admin.brand-show',compact('brand'));
    }

    public function add()
    {
    	return view('admin.brand-add');
    }

    public function create()
    {
    	$input = Input::all();
    	$common = new Controller;
    	$cate = new brand;
    	$cate->brand_name = $input['brand_name'];
    	$cate->brand_pic = $input['brand_pic'];
    	$cate->brand_title = $input['brand_title'];
    	$cate->log = $input['log'];
    	if ($cate->save()) {
    		$result = $common->result('success','添加品牌成功!','');
    	}else{
    		$result = $common->result('fail','ERROR!未能存储数据,添加品牌失败!','');
    	}
    	return $result;
    }

    public function edit($id)
    {
    	$input = Input::all();
    	$common = new Controller;
    	if (brand::where('id',$id)
    		->update([
    			"brand_name" => $input['brand_name'],
    			"brand_pic" => $input['brand_pic'],
    			"brand_title" => $input['brand_title'],
    			"log" => $input['log']
    		])) {
    		$result = $common->result('success','品牌编辑成功!','');
    	}else{
    		$result = $common->result('fail','ERROR!未能存储数据,品牌编辑失败!','');
    	}
    	return $result;
    }
    public function status($id)
    {
    	$common = new Controller;
    	if (brand::find($id)->status) {
    		$newStatus = '0';
    	}else{
    		$newStatus = '1';
    	}
    	if (isset($newStatus)) {
    		if (brand::where('id',$id)
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
