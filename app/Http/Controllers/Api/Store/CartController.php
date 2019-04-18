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
use App\Model\car;

class CartController extends Controller
{
	public function index()
	{
		$input = Input::all();
		// 获取用户购物车内的商品
		$goodsInfo = car::orderBy('car.id','DESC')
            ->leftJoin('goods','goods.id','=','car.goods_id')
			->where('car.uuid',$input['uuid'])
			->where('car.status','1')
			->where('car.end','0')
			->where('goods.status','1')
			->select('car.*','goods.goods_name','goods.goods_pic','goods.goods_price','goods.goods_point')
            ->get();
		// dd($goodsInfo);
		// 获取属性参数后的价格
		$cart = array();
		$cart['totalNum'] = 0;
		$cart['totalPrice'] = 0;
		$cart['totalPoint'] = 0;
		// dd($goodsInfo);
		foreach ($goodsInfo as $key => $value) {
			// 分解字符串为数组
			$attrArray = explode(",", $value->attr_array);
			$attrPrice = 0;
			$attrPoint = 0;
			$onAttr = array();
			// $onAttr_id = array();
			$attr_depot = 0;
			foreach ($attrArray as $k => $v) {
				$attrPrice += attribute::find($v)->attr_price;
				$attrPoint += attribute::find($v)->attr_point;
				// $onAttr_id[] = $v;
				$onAttr[$k]['name'] = attribute::find($v)->attr_name;
				$onAttr[$k]['id'] = $v;
				$depot = attribute::find($v);
				$attr_depot += $depot->attr_depot - $depot->attr_buy;
			}
			if ($value->goods_num > $attr_depot) {
				$value->attrDepot = false;
			}else{
            	$value->attrDepot = true;
            }
            $value->total = $value->goods_price + $attrPrice;
            $value->attrPrice = $attrPrice;
            $value->onAttr = $onAttr;
            // $value->onAttr_id = $onAttr_id;
            $cart['totalNum'] += $value->goods_num;
            $cart['totalPrice'] += ($value->goods_price + $attrPrice) * $value->goods_num;
            $cart['totalPoint'] += ($value->goods_point + $attrPoint) * $value->goods_num;
            $cart['goods'][] = $value;

		}
		return $this->result('success','获取成功!',$cart);
	}

    public function addCart()
    {
    	$input = Input::all();
		$cart = new car;
		$cart->goods_id = $input['goodsId'];
		$cart->attr_array = implode(',',json_decode($input['attr']));
		$cart->uuid = $input['uuid'];
		$cart->goods_num = $input['num'];
		$cart->status = 1;
		$cart->end = 0;
		if ($cart->save()) {
			$result = $this->result('success','加入购物车成功!');
		}else{
			$result = $this->result('fail','加入购物车失败,请稍后再试!');
		}
		return $result;
    }

    public function delCart()
    {
    	$input = Input::all();
    	if (car::find($input['cartId'])->update(['status'=>0])) {
			$result = $this->result('success','移除购物车成功!');
		}else{
			$result = $this->result('fail','移除购物车失败,请稍后再试!');
		}
		return $result;
    }


}
