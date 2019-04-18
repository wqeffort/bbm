<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Log;

// 加载Model
use App\Model\User;
use App\Model\goods;
use App\Model\attribute;
use App\Model\collection;
use App\Model\order;

class GoodsController extends Controller
{
    public function goods()
    {
    	$input = Input::all();
    	$data = array();
    	$data['goods'] = goods::where('goods.status','1')
 			->where('goods.id',$input['goodsId'])
 			->leftJoin('category','goods.category_id','=','category.id')
 			->where('category.status','1')
 			->rightJoin('brand','goods.brand_id','=','brand.id')
 			->where('brand.status','1')
 			->select('goods.*','brand.brand_name','brand.brand_pic','category.category_name','category.category_pic')
 			->first();
        if ($data['goods']) {
            $collection = collection::where('uuid',$input['uuid'])
                ->where('goods_id',$input['goodsId'])
                ->first();
            if ($collection) {
                if ($collection->status) {
                    $data['collection'] = true;
                }else{
                    $data['collection'] = false;
                }
            }else{
                $data['collection'] = false;
            }
            goods::find($input['goodsId'])->increment('view');
            // $gallery = explode('|',$data['goods']->goods_gallery);
            // foreach ($gallery as $key => $value) {
            //  $data['gallery'][] = "http://".env('HTTP_HOST')."/".$value;
            // }
            $data['gallery'][] = "http://".env('HTTP_HOST')."/".$data['goods']->goods_pic;
            // 获取商品的属性值
            $data['attr'] = attribute::orderBy('attr_pid','DESC')
                ->where('status','1')
                ->where('goods_id',$input['goodsId'])
                ->get();
            if ($data['attr']->isNotEmpty()) {
                $data['attr'] = $this->handleAttr($data['attr']->toArray());
            }
            // 计算商品库存
            $data['depot'] = attribute::where('goods_id',$input['goodsId'])
                ->where('status','1')
                ->get()
                ->sum('attr_depot');

            // 检查商品是否收藏

            $collectionRes = collection::where('uuid', $input['uuid'])
                ->where('status','1')
                ->where('goods_id',$input['goodsId'])
                ->first();
            if ($collectionRes) {
                $data['collection'] = true;
            }else{
                $data['collection'] = false;
            }

            // 计算销售件数
            $data['sell'] = order::where('goods_id',$input['goodsId'])
                ->where('status','!=','0')
                ->get()->sum('goods_num');


            return $this->result('success','获取数据成功!',$data);
        }else{
            return $this->result('fail','未查询到该商品!');
        }
    }

    public function search()
    {
    	$input = Input::all();
    	$data = goods::orderBy('id','DESC')
    		->where('status','1')
    		->where('goods_name','like','%'.$input['text'].'%')
    		->select('id','goods_name','goods_pic','goods_point','goods_title','view','is_hot','is_new')
    		->get();
        $goods = array();
        foreach ($data as $key => $value) {
            // dd($value);
            $attr = attribute::where('goods_id',$value->id)->get();
            $sell = $attr->sum('attr_buy');
            $value->sell = $sell;
            $goods[] = $value;
        }
        // dd($goods);
    	return $this->result('success','搜索商品成功!',$goods);
    }

    public function cate()
    {
    	$input = Input::all();
    	$data = goods::orderBy('id','DESC')
    		->where('status','1')
    		->where('category_id',$input['cateId'])
    		->select('id','goods_name','goods_pic','goods_point','goods_title','view','is_hot','is_new')
    		->get();
        $goods = array();
        foreach ($data as $key => $value) {
            // dd($value);
            $attr = attribute::where('goods_id',$value->id)->get();
            $sell = $attr->sum('attr_buy');
            $value->sell = $sell;
            $goods[] = $value;
        }
    	return $this->result('success','获取商品成功!',$goods);
    }

    public function brand()
    {
    	$input = Input::all();
    	$data = goods::orderBy('id','DESC')
    		->where('status','1')
    		->where('brand_id',$input['brandId'])
    		->select('id','goods_name','goods_pic','goods_point','goods_title','view','is_hot','is_new')
    		->get();
        $goods = array();
        foreach ($data as $key => $value) {
            // dd($value);
            $attr = attribute::where('goods_id',$value->id)->get();
            $sell = $attr->sum('attr_buy');
            $value->sell = $sell;
            $goods[] = $value;
        }
    	return $this->result('success','获取商品成功!',$goods);
    }

    public function collection()
    {
        $input = Input::all();
        $data = collection::where('uuid',$input['uuid'])
            ->leftJoin('goods','goods.id','=','collection.goods_id')
            ->select('goods.id','goods.goods_pic','goods.goods_name','goods.goods_point','goods.status')
            ->where('goods.status','1')
            ->where('collection.status','1')
            ->get();
        return $this->result('success','获取收藏商品成功!',$data);
    }

    public function collectionAdd()
    {
        $input = Input::all();
        $status = collection::where('uuid',$input['uuid'])
            ->where('goods_id',$input['goodsId'])
            ->first();
        if ($status) {
            if ($status->status) {
                $result = $this->result('fail','您已收藏过此商品!');
            }else{
                if (collection::where('uuid',$input['uuid'])
            ->where('goods_id',$input['goodsId'])
            ->update(['status'=>1])) {
                    $result = $this->result('success','添加收藏成功!');
                }else{
                    $result = $this->result('fail','添加商品数据失败!');
                }
            }
        }else{
            $collection = new collection;
            $collection->goods_id = $input['goodsId'];
            $collection->uuid = $input['uuid'];
            $collection->status = 1;
            if ($collection->save()) {
                $result = $this->result('success','添加收藏成功!');
            }else{
                $result = $this->result('fail','添加商品数据失败!');
            }
        }
        return $result;
    }

    public function collectionDel()
    {
        $input = Input::all();
        $collection = collection::where('uuid',$input['uuid'])
            ->where('goods_id',$input['goodsId'])
            ->first();
        if ($collection) {
            if (collection::find($collection->id)->update(['status'=>0])) {
                $result = $this->result('success','操作成功!');
            }else{
                $result = $this->result('fail','商品移除收藏夹失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','商品移除收藏夹失败,请稍后再试!');
        }
        return $result;
    }
}
