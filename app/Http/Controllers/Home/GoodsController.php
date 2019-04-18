<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Response;

// 加载Model
use App\Model\User;
use App\Model\goods;
use App\Model\order;
use App\Model\attribute;
use App\Model\category;
use App\Model\brand;
use App\Model\collection;
use Log;
class GoodsController extends Controller
{
	// 获取单个商品详情
 	public function index($goodsId)
 	{
 		if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            $goods = goods::where('goods.status','1')
 			->where('goods.id',$goodsId)
 			->leftJoin('category','goods.category_id','=','category.id')
 			->where('category.status','1')
 			->rightJoin('brand','goods.brand_id','=','brand.id')
 			->where('brand.status','1')
 			->select('goods.*','brand.brand_name','brand.brand_pic','category.category_name','category.category_pic')
 			->first();

	 		$goodsGallery = explode("|", $goods->goods_gallery);
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
	 		return view('home.goods',compact('goods','goodsGallery','attr','app','collection','depot','sell'));
        }
 	}

 	public function test($goodsId)
 	{
 		if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            $app = app('wechat.official_account');
            $goods = goods::where('goods.id',$goodsId)
 			->leftJoin('category','goods.category_id','=','category.id')
 			->where('category.status','1')
 			->rightJoin('brand','goods.brand_id','=','brand.id')
 			->where('brand.status','1')
 			->select('goods.*','brand.brand_name','brand.brand_pic','category.category_name','category.category_pic')
 			->first();

	 		$goodsGallery = explode("|", $goods->goods_gallery);
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
	 		return view('home.goods',compact('goods','goodsGallery','attr','app','collection','depot','sell'));
        }
 	}

 	// 获取属性列表的数组值
 	public function getArrayAttr()
 	{
 		$input = Input::all();
 		// dd($input);
 		$data = array();
 		$data['attrPrice'] = 0;
 		$data['attrPoint'] = 0;
 		$data['depot'] = 0;
 		foreach ($input['array'] as $key => $value) {
 			$data['attrPrice'] += attribute::find($value)->attr_price;
 			$data['attrPoint'] += attribute::find($value)->attr_point;
 			$depot = attribute::find($value);
 			$data['depot'] += $depot->attr_depot - $depot->attr_buy;
 			$data['attrName'][] = attribute::find($value)->attr_name;
 		}
 		if (empty($data)) {
 			$result = $this->result('fail','请求获取属性数据失败!','');
 		}else{
 			$result = $this->result('success','成功!',$data);
 		}
 		return $result;
 	}

 	// 添加商品收藏
 	public function addCollection()
 	{
 		$input = Input::all();
 		$res = collection::where('goods_id',$input['goodsId'])
 			->where('uuid',session('user')->user_uuid)
 			->first();
 		if ($res) {
 			if ($res->status == 1) {
 				if (collection::where('goods_id',$input['goodsId'])
 						->where('uuid',session('user')->user_uuid)
 						->update(['status'=>0])) {
 					$result = $this->result('success','取消收藏商品成功!','');
 				}else{
 					$result = $this->result('fail','ERROR!收藏失败,未知的系统错误!','');
 				}
 			}else{
 				if (collection::where('goods_id',$input['goodsId'])
 						->where('uuid',session('user')->user_uuid)
 						->update(['status'=>1])) {
 					$result = $this->result('success','收藏商品成功!','');
 				}else{
 					$result = $this->result('fail','ERROR!收藏失败,未知的系统错误!','');
 				}
 			}
 		}else{
 			$collection = new collection;
	 		$collection->goods_id = $input['goodsId'];
	 		$collection->uuid = session('user')->user_uuid;
	 		if ($collection->save()) {
	 			$result = $this->result('success','收藏商品成功!','');
	 		}else{
	 			$result = $this->result('fail','ERROR!收藏失败,未知的系统错误!','');
	 		}
 		}
 		return $result;
 	}

 	public function listTest($type,$data)
 	{
 		$app = app('wechat.official_account');
 		if ($type == 'brand') {
 			$goods = goods::orderBy(\DB::raw('RAND()'))
 				->where('status','1')
 				->where('brand_id',$data)
 				->take(10)
 				->get();
 		}elseif ($type == 'search') {
 			$goods = goods::orderBy('id','DESC')
 				->where('status','1')
 				->where('goods_name','like','%'.$data.'%')
 				->get();
 		}else{
 			$goods = goods::orderBy(\DB::raw('RAND()'))
				->where('status','1')
				->where('brand_id','6')
 				->take(10)
 				->get();
 		}
 		if ($goods->isEmpty()) {
 			$goods = goods::orderBy(\DB::raw('RAND()'))
				->where('status','1')
 				->take(10)
 				->get();
 		}
 		return view('home.goods-list',compact('app','goods'));
 	}

 	public function list($type,$data)
 	{
 		$app = app('wechat.official_account');
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
 		return view('home.goods-list',compact('app','goods','page','last','data'));
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
