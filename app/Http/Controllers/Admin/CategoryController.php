<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\category;
class CategoryController extends Controller
{
    public function list()
    {
    	$cate = category::orderBy('id','ASC')
    		->get();
    	return view('admin.cate-list', compact('cate'));
    }

    public function show($id)
    {
    	$data = category::find($id);
    	$cate = category::orderBy('id','ASC')
    		->where('category_pid','0')
    		->get();
    	return view('admin.category-show',compact('data','cate'));
    }

    public function add()
    {
    	$cate = category::orderBy('id','ASC')
    		->where('category_pid', '0')
    		->get();

    	return view('admin.category-add',compact('cate'));
    }

    public function create()
    {
    	$input = Input::all();
    	$common = new Controller;
    	$cate = new category;
    	$cate->category_name = $input['category_name'];
    	$cate->category_pic = $input['category_pic'];
    	$cate->category_title = $input['category_title'];
    	$cate->category_pid = $input['category_pid'];
    	$cate->log = $input['log'];
    	if ($cate->save()) {
    		$result = $common->result('success','添加分类成功!','');
    	}else{
    		$result = $common->result('fail','ERROR!未能存储数据,添加分类失败!','');
    	}
    	return $result;
    }

    public function edit($id)
    {
    	$input = Input::all();
    	$common = new Controller;
    	if (category::where('id',$id)
    		->update([
    			"category_name" => $input['category_name'],
    			"category_pic" => $input['category_pic'],
    			"category_title" => $input['category_title'],
    			"category_pid" => $input['category_pid'],
    			"log" => $input['log']
    		])) {
    		$result = $common->result('success','分类编辑成功!','');
    	}else{
    		$result = $common->result('fail','ERROR!未能存储数据,分类编辑失败!','');
    	}
    	return $result;
    }
    public function status($id)
    {
    	$common = new Controller;
    	if (category::find($id)->status) {
    		$newStatus = '0';
    	}else{
    		$newStatus = '1';
    	}
    	if (isset($newStatus)) {
    		if (category::where('id',$id)
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
