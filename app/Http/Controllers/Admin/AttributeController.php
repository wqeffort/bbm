<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\attribute;
use App\Model\goods;
class AttributeController extends Controller
{
	public function list()
	{
		$attr = attribute::orderBy('attribute.id','DESC')
			->leftJoin('goods','attribute.goods_id','=','goods.id')
			->select('attribute.*','goods.goods_name')
			->get();
		return view('admin.attr-list',compact('attr'));
	}

    public function show($id)
    {
    	$attr = attribute::orderBy('attribute.id','DESC')
			->leftJoin('goods','attribute.goods_id','=','goods.id')
			->select('attribute.*','goods.goods_name')
			->get();
    	$data = attribute::where('attribute.id',$id)
    		->leftJoin('goods','attribute.goods_id','=','goods.id')
    		->select('attribute.*','goods.goods_name','goods.goods_pic')
    		->first();
    	return view('admin.attr-show',compact('attr','data'));
    }

    public function add()
    {
    	// 查询出所有的属性
    	$attr = attribute::orderBy('attr_pid','ASC')
    		->where('status','1')
    		->get();
        return view('admin.attr-add',compact('attr'));
    }

    public function create()
    {
    	$input = Input::all();
    	$attr = new attribute;
    	$attr->attr_name = $input['attr_name'];
    	$attr->attr_price = $input['attr_price'];
    	$attr->attr_point = $input['attr_point'];
    	$attr->attr_depot = $input['attr_depot'];
    	$attr->attr_pid = $input['attr_pid'];
    	$attr->goods_id = $input['goods_id'];
    	$attr->log = $input['log'];
    	if ($attr->save()) {
    		$result = $this->result('success','成功','');
    	}else{
    		$result = $this->result('fail','ERROR!属性写入数据库失败!','');
    	}
    	return $result;
    }

    public function edit($id)
    {
    	$input = Input::all();
    	if (attribute::where('id',$id)
    		->update([
    			"attr_name" => $input['attr_name'],
    			"attr_price" => $input['attr_price'],
    			"attr_point" => $input['attr_point'],
    			"attr_depot" => $input['attr_depot'],
                "attr_pid" => $input['attr_pid'],
    			"attr_buy" => $input['attr_buy'],
    			"goods_id" => $input['goods_id'],
    			"log" => $input['log']
    		])) {
    		$result = $this->result('success','修改成功!','');
    	}else{
    		$result = $this->result('fail','ERROR!修改属性失败!','');
    	}
    	return $result;
    }

    public function status($id)
    {
    	$common = new Controller;
    	if (attribute::find($id)->status) {
    		$newStatus = '0';
    	}else{
    		$newStatus = '1';
    	}
    	if (isset($newStatus)) {
    		if (attribute::where('id',$id)
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

    public function getGoods($id)
    {
    	$goods = goods::find($id);
    	if (!empty($goods)) {
    		$result = $this->result('success','成功',$goods);
    	}else{
    		$result = $this->result('fail','为查询到相应关键字的商品!','');
    	}
    	return $result;
    }

}
