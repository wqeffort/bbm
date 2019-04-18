<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\goods;
use App\Model\attribute;
use App\Model\collection;
use App\Model\order;
use App\Model\car;

use Log;

class GoodsController extends Controller
{
    public function index($goodsId)
    {

            $goods = goods::where('goods.status','1')
 			->where('goods.id',$goodsId)
 			->leftJoin('category','goods.category_id','=','category.id')
 			->where('category.status','1')
 			->rightJoin('brand','goods.brand_id','=','brand.id')
 			->where('brand.status','1')
 			->select('goods.*','brand.brand_name','brand.brand_pic','category.category_name','category.category_pic')
 			->first();

	 		// 获取商品的属性值
	 		$attr = attribute::orderBy('attr_pid','DESC')
	 			->where('status','1')
	 			->where('goods_id',$goodsId)
	 			->get();
	 		if ($attr->isNotEmpty()) {
	 			$attr = $this->handleAttr($attr->toArray());
	 		}
	 		// dd($attr);
	 		// 计算商品库存
	 		$depot = attribute::where('goods_id',$goodsId)
	 			->where('status','1')
	 			->get()
	 			->sum('attr_depot');

	 		// 检查商品是否收藏
	 		$collectionRes = collection::where('uuid', session('user')->user_uuid)
	 			->where('status','1')
	 			->where('goods_id',$goodsId)
	 			->first();
	 		if ($collectionRes) {
	 			$collection = true;
	 		}else{
	 			$collection = false;
			}
			// 计算销售件数
			$sell = order::where('goods_id',$goodsId)
				->where('status','!=','0')
				->get()->sum('goods_num');
	 		// dd($attr);

    	return view('view.goods',compact('goods','goodsGallery','attr','app','depot','sell','collection'));
	}

	public function getAttr($id)
	{
		$attr = attribute::orderBy('attr_pid','DESC')
	 			->where('status','1')
	 			->where('goods_id',$id)
	 			->get();
	 	if ($attr->isNotEmpty()) {
	 		$attr = $this->handleAttr($attr->toArray());
	 	}
	 	$result = $this->result('success','成功!',$attr);
	 	return $result;
	}














	public function addCart($goodsId)
	{
		$input = Input::all();
		$cart = new car;
		$cart->goods_id = $goodsId;
		$cart->attr_array = implode(',',$input['attr']);
		$cart->uuid = session('user')->user_uuid;
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

	public function cart()
	{
		// 获取用户购物车内的商品
		$goodsInfo = car::orderBy('car.id','DESC')
            ->leftJoin('goods','goods.id','=','car.goods_id')
			->where('car.uuid',session('user')->user_uuid)
			->where('car.status','1')
			->where('car.end','0')
			->where('goods.status','1')
			->select('car.*','goods.goods_name','goods.goods_pic','goods.goods_price','goods.goods_point')
            ->get();
		// dd($goodsInfo);
		// 获取属性参数后的价格
		$goods = array();
		$totalNum = 0;
		$totalPrice = 0;
		$totalPoint = 0;
		foreach ($goodsInfo as $key => $value) {
			// 分解字符串为数组
			$attrArray = explode(",", $value->attr_array);
			$attrPrice = 0;
			$attrPoint = 0;
			$onAttr = array();
			$attr_depot = 0;
			foreach ($attrArray as $k => $v) {
				$attrPrice += attribute::find($v)->attr_price;
				$attrPoint += attribute::find($v)->attr_point;
				$onAttr[] = attribute::find($v)->attr_name;
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
            $totalNum += $value->goods_num;
            $totalPrice += ($value->goods_price + $attrPrice) * $value->goods_num;
            $totalPoint += ($value->goods_point + $attrPoint) * $value->goods_num;
            $goods[] = $value;
		}
		// dd($goods);

		return view('view.cart',compact('goods','totalNum','totalPrice','totalPoint','attr_depot'));
	}

	public function removeCart($id)
	{
		if (car::find($id)->update(['status'=>0])) {
			$result = $this->result('success','移除购物车成功!');
		}else{
			$result = $this->result('fail','移除购物车失败,请稍后再试!');
		}
		return $result;
	}

	public function list($type,$data)
 	{
 		if ($type == 'brand') {
 			$goods = goods::orderBy('id','DESC')
 				->where('status','1')
 				->where('brand_id',$data)
 				->paginate(10);
 			$page = $goods->currentPage();
 			$last = $goods->lastPage();

 		}elseif ($type == 'search') {
 			$goods = goods::orderBy('id','DESC')
 				->where('status','1')
 				->where('goods_name','like','%'.$data.'%')
 				->paginate(10);
 			$page = $goods->currentPage();
 			$last = $goods->lastPage();
 		}else{
 			$goods = goods::orderBy('id','DESC')
				->where('status','1')
				->where('brand_id','6')
 				->paginate(10);
 			$page = $goods->currentPage();
 			$last = $goods->lastPage();
 		}
 		if ($goods->isEmpty()) {
 			$goods = goods::orderBy(\DB::raw('RAND()'))
				->where('status','1')
 				->paginate(10);
 			$page = $goods->currentPage();
 			$last = $goods->lastPage();
 		}
 		return view('view.goods-list',compact('goods','page','last','data'));
 	}

 	public function listData($type,$data,$page)
 	{
 		switch ($type) {
 			case 'brand':
 				$goods = goods::orderBy('id','DESC')
 				->where('status','1')
 				->where('brand_id',$data)
 				->offset($page*10)
 				->limit(10)
 				->get();
 				break;
 			case 'search':
 				$goods = goods::orderBy('id','DESC')
 				->where('status','1')
 				->where('goods_name','like','%'.$data.'%')
 				->offset($page*10)
 				->limit(10)
 				->get();
 				break;
 			default:
 				$goods = goods::orderBy('id','DESC')
				->where('status','1')
				->where('brand_id','6')
 				->offset($page*10)
 				->limit(10)
 				->get();
 				break;
 		}

 		if ($goods->isNotEmpty()) {
 			$result = $this->result('success','成功!',$goods);
 		}else{
 			$result = $this->result('fail','失败');
 		}
 		return $result;
 	}
}
