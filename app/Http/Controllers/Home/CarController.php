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
use App\Model\attribute;
use App\Model\category;
use App\Model\brand;
use App\Model\car;
use App\Model\ads;
class CarController extends Controller
{
    // 接收商品添加
    public function addCar()
    {
    	$input = Input::all();
    	$car = new car;
    	$car->goods_id = $input['goods_id'];
    	$car->attr_array = $input['attr_array']['0'];
    	$car->uuid = session('user')->user_uuid;
    	$car->goods_num = $input['goods_num'];
    	if ($car->save()) {
    		$result = $this->result('success','成功','');
    	}else{
    		$result = $this->result('fail','添加进购物车失败,请稍后再试!','');
    	}
    	return $result;
    }

    // 购物车页面
    public function car()
    {
    	if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            // $app = app('wechat.official_account');
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
            switch (session('user')->user_rank) {
                case '2':
                    $sale = 0.8;
                    break;
                case '3':
                    $sale = 0.75;
                    break;
                case '4':
                    $sale = 0.7;
                    break;
                case '5':
                    $sale = 0.65;
                    break;
                case '6':
                    $sale = 0.6;
                    break;
                default:
                    $sale = 1;
                    break;
            }
            // dd($goods);
            return view('home.car',compact('goods','totalNum','totalPrice','totalPoint','attr_depot','sale'));
        }
    }

	// 重新计算总价
	public function total($goodsStr)
	{
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
				if (in_array($value->id,explode(',',$goodsStr))) {
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
						$attr_depot += attribute::find($v)->attr_depot;
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
				}
			}
			if ($totalPrice && $totalPoint && $totalNum) {
				$data['totalPrice'] = $totalPrice;
				$data['totalPoint'] = $totalPoint;
				$data['totalNum'] = $totalNum;
				$result = $this->result('success','成功!',$data);
			} else {
				$result =$this->result('fail','获取新的总价失败!');
			}
			return $result;
			
	}
    // 将商品移除购物车
    public function setStatus()
    {
    	$input = Input::all();
    	if (car::where('id',$input['carId'])
    		->update(['status'=>'0'])) {
    		$result = $this->result('success','移除商品成功!','');
    	}else{
    		$result = $this->result('fail','系统错误,移除商品失败!','');
    	}
    	return $result;
    }


    // 购物车页面默认检查收货地址
    public function getUserAds()
    {
    	$input = Input::all();
    	// 首先检查用户是否存在收货地址
    	$ads = ads::where('uuid',session('user')->user_uuid)
    		->where('status','1')
    		->first();
    	if (empty($ads)) {
    		$text = '未查询到收货地址,请点击添加收货地址!';
    		$result = $this->result('fail','获取收货地址成功!',$text);
    	}else{
    		// 返回收货地址
    		$text = $ads->province.$ads->city.$ads->area.$ads->ads;
    		$result = $this->result('success','获取收货地址成功!',$text);
    	}
    	return $result;

    }

    // 用户收货地址列表页
    public function adsList()
    {
    	if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            // $app = app('wechat.official_account');
	    	// 获取到用户的收货地址
	    	$ads = ads::orderBy('id','ASC')
	    		->where('uuid',session('user')->user_uuid)
                ->where('del','0')
	    		->get();

    		return view('home.ads-list',compact('ads'));
    	}
    }

    // 地址管理页获取用户详细地址
    public function adsListGetlocation()
    {
    	$input = Input::all();
    	// 获取到用户目前的地址
    	$url = $this->locationAds($input['lng'],$input['lat']);
    	$adsInfo = json_decode($this->curlGet($url));
    	if ($adsInfo->status == 0) {
    		$data['text'] = $adsInfo->result->address;
    		$data['province'] = $adsInfo->result->address_component->province;
    		$data['city'] = $adsInfo->result->address_component->city;
    		$data['area'] = $adsInfo->result->address_component->district;
    		$data['ads'] = $adsInfo->result->address_component->street.$adsInfo->result->address_component->street_number;
    	}else{
    		$data['text'] = '';
    		$data['province'] = '';
    		$data['city'] = '';
    		$data['area'] = '';
    		$data['ads'] = '';
    	}
    	return json_encode($data);
    }

    // 购物车地址添加页面
    public function adsAdd()
    {
    	if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
        	$app = app('wechat.official_account');
        	$name = session('user')->user_name;
        	$phone = session('user')->user_phone;
    		return view('home.ads-add',compact('app','name','phone'));
    	}
    }

    // 接收用户添加地址
    public function addAds()
    {
    	$input = Input::all();
    	$ads = new ads;
    	$ads->uuid = session('user')->user_uuid;
    	$ads->ads = $input['ads'];
    	$ads->name = $input['name'];
    	$ads->phone = $input['phone'];
    	$text = explode('/',$input['city']."/");
    	$ads->province = $text['0'];
    	$ads->city = $text['1'];
    	$ads->area = $text['2'];
    	// 检查用户是否存在默认地址,如果没有,就直接设为默认地址
    	if (ads::where('uuid',session('user')->user_uuid)
    		->where('status','1')
    		->first()) {
    		$ads->status = 0;
    	}else{
    		$ads->status = 1;
    	}
    	if ($ads->save()) {
    		$result = $this->result('success','添加收货地址成功!','');
    	}else{
    		$result = $this->result('fail','添加收货地址失败!','');
    	}
    	return $result;
    }


    // 接收用户修改地址
    public function editAds()
    {
        $input = Input::all();
        $text = explode('/',$input['city']."/");
        if (ads::find($input['id'])->update([
            "ads"=>$input['ads'],
            "province"=>$text['0'],
            "city"=>$text['1'],
            "area"=>$text['2'],
            "name"=>$input['name'],
            "phone"=>$input['phone']
        ])) {
            $result = $this->result('success','修改收货地址成功!','');
        }else{
            $result = $this->result('fail','修改收货地址失败!','');
        }
        return $result;
    }

    // 接收用户设置默认地址
    public function adsStatus()
    {
    	$input = Input::all();
    	if (ads::where('uuid',session('user')->user_uuid)
        		->update(['status'=>'0'])) {
    		if (ads::where('id',$input['id'])
        			->update(['status'=>'1'])) {
    			$result = $this->result('success','设置默认收货地址成功!','');
    		}else{
    			$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    		}
    	}else{
    		$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    	}
  //   	DB::beginTransaction();
  //       try{
  //       	if () {
  //       		;
  //       	}else{
  //       		DB::rollBack();
  //       	}
  //           DB::commit();
           
  //       }catch (\Exception $e) {
  //           //接收异常处理并回滚
  //           DB::rollBack();
           	
		// }
		return $result;
    }






































}
