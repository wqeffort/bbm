<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\goods;
use App\Model\category;
use App\Model\brand;
use App\Model\order;
use App\Model\attribute;
class GoodsController extends Controller
{
    public function list()
    {
    	$data = goods::orderBy('goods.id','DESC')
            ->leftJoin('brand','brand.id','=','goods.brand_id')
            ->select('goods.*','brand.brand_pic')
    		->get();
        // dd($data);
        if (!$data->isEmpty()) {
                $goods = new \stdClass();
            foreach ($data as $key => $value) {
                $value['depot'] = attribute::where('goods_id',$value->id)->get()->sum('attr_depot');
                $value['buy'] = order::where('goods_id',$value->id)
                    ->where('status','1')
                    ->get()->sum('goods_num');
                $items[] = $value;
            }
            $goods->items = $items;
        }
        // dd($goods);
    	return view('admin.goods-list',compact('goods'));
    }

    public function show($id)
    {
        // 查询出所有的分类
        $category = category::orderBy('id','ASC')
            ->where('status','1')
            ->where('category_pid','!=','0')
            ->get();
        // 查询出所有的品牌
        $brand = brand::orderBy('id','ASC')
            ->where('status','1')
            ->get();
        $goods = goods::where('goods.id',$id)
            ->leftJoin('brand','brand.id','=','goods.brand_id')
            ->rightJoin('category','category.id','=','goods.category_id')
            ->select('goods.*','category.category_name','brand.brand_name')
            ->first();
        return view('admin.goods-show',compact('goods','category','brand'));
    }

    public function add()
    {
    	// 查询出所有的分类
        $category = category::orderBy('id','ASC')
            ->where('status','1')
            ->where('category_pid','!=','0')
            ->get();
        // 查询出所有的品牌
        $brand = brand::orderBy('id','ASC')
            ->where('status','1')
            ->get();
        return view('admin.goods-add',compact('category','brand'));
    }

    public function create()
    {
    	$input = Input::all();
        // dd($input);
        $goods = new goods;
        $goods->goods_name = $input['goods_name'];
        $goods->goods_pic = $input['goods_pic'];
        $goods->goods_title = $input['goods_title'];
        $goods->goods_price = $input['goods_price'];
        $goods->goods_point = $input['goods_point'];
        $goods->category_id = $input['category_id'];
        $goods->goods_gallery = implode("|", $input['goods_gallery']);
        $goods->goods_desc = $input['goods_desc'];
        $goods->brand_id = $input['brand_id'];
        $goods->goods_search = $input['goods_search'];
        $goods->goods_desc = $input['goods_desc']['0'];
        $goods->goods_server = $input['goods_desc']['1'];
        $goods->log = $input['log'];
        if ($goods->save()) {
            $result = $this->result('success','上传商品成功!','');
        }else{
            $result = $this->result('fail','ERROR!录入商品数据失败!','');
        }
        return $result;
    }

    public function edit($id)
    {
        $input = Input::all();
        if (goods::where('id',$id)
            ->update([
                "goods_name" => $input['goods_name'],
                "goods_pic" => $input['goods_pic'],
                "goods_title" => $input['goods_title'],
                "goods_price" => $input['goods_price'],
                "goods_point" => $input['goods_point'],
                "category_id" => $input['category_id'],
                "goods_gallery" => implode("|", $input['goods_gallery']),
                "goods_desc" => $input['goods_desc'],
                "brand_id" => $input['brand_id'],
                "goods_search" => $input['goods_search'],
                "goods_desc" => $input['goods_desc']['0'],
                "goods_server" => $input['goods_desc']['1'],
                "log" => $input['log']
            ])) {
            $result = $this->result('success','商品信息修改成功!','');
        }else{
            $result = $this->result('fail','ERROR!修改商品失败!','');
        }
        return $result;
    }
    public function status($id)
    {
    	$common = new Controller;
    	if (goods::find($id)->status) {
    		$newStatus = '0';
    	}else{
    		$newStatus = '1';
    	}
    	if (isset($newStatus)) {
    		if (goods::where('id',$id)
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

    public function isNew($id)
    {
        $common = new Controller;
        if (goods::find($id)->is_new) {
            $newStatus = '0';
        }else{
            $newStatus = '1';
        }
        if (isset($newStatus)) {
            if (goods::where('id',$id)
                ->update(['is_new'=>$newStatus])) {
                $result = $common->result('success','修改成功!','');
            }else{
                $result = $common->result('fail','ERROR!修改状态失败!','');
            }
        }else{
            $result = $common->result('fail','修改状态失败!','');
        }
        return $result;
    }

    public function isHot($id)
    {
        $common = new Controller;
        if (goods::find($id)->is_hot) {
            $newStatus = '0';
        }else{
            $newStatus = '1';
        }
        if (isset($newStatus)) {
            if (goods::where('id',$id)
                ->update(['is_hot'=>$newStatus])) {
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
