<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\goods;
use Log;
class OrderController extends Controller
{
    // 接收购物车添加订单
	public function page($goods)
	{
		// session(['user'=>'']);
        // 判断session是否存在,如果不存在则跳转Oauth
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            // $app = app('wechat.official_account');
			// 核算价格
			$carInfo = car::where('car.status','1')
				->where('car.uuid',session('user')->user_uuid)
				// ->whereIn('car.id',[$goods])
				->where('car.end','0')
				->leftJoin('goods','car.goods_id','=','goods.id')
				->where('goods.status','1')
				->select('goods.goods_price','goods.goods_point','car.*')
				->get();
			// dd($carInfo);
			// 生成唯一单号(20位)
			// 数据写入订单表
			$sale = 1;
			$num = date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,8);
			foreach ($carInfo as $key => $value) {
				if (in_array($value->id,explode(',',$goods))) {
					$attrPrice = 0;
					$attrPoint = 0;
					foreach (explode(',',$value->attr_array) as $k => $v) {
						// dd($v);
						$attrPrice += attribute::find($v)->attr_price;
						$attrPoint += attribute::find($v)->attr_point;
						// dd($attrPrice);
					}
					$order = new order;
					$order->num = $num;
					$order->point = ($value->goods_point + $attrPoint) * $value->goods_num;
					$order->price = ($value->goods_price + $attrPrice) * $value->goods_num;
					$order->goods_num = $value->goods_num;
					$order->type = 2;
					switch (session('user')->user_rank) {
		                case '2':
		                    $sale = '0.8';
		                    break;
		                case '3':
		                    $sale = '0.75';
		                    break;
		                case '4':
		                    $sale = '0.7';
		                    break;
		                case '5':
		                    $sale = '0.65';
		                    break;
		                case '6':
		                    $sale = '0.6';
		                    break;
		                default:
		                    $sale = '1';
		                    break;
		            }
		            $order->sale = $sale;
					$order->goods_id = $value->goods_id;
					$order->goods_attr = $value->attr_array;
					$order->uuid = session('user')->user_uuid;
					$order->save();
				}
			}
			// 修改购物车商品状态
			foreach (explode(',',$goods) as $key => $value) {
				car::where('status','1')
					->where('uuid',session('user')->user_uuid)
					->where('id',$value)
					->update(['end'=>'1']);
			}
			// 获取用户的收货地址
			$ads = ads::where('uuid',session('user')->user_uuid)
				->where('status','1')
				->first();
			// 重新获取到订单 // 防止用户刷新页面后无法取值
			$numInfo = order::orderBy('id','DESC')
				->where('uuid',session('user')->user_uuid)
				->where('status','0')
				->first();
			if ($numInfo) {
				$num = $numInfo->num;
			}else{
				$num = false;
			}
			$orderInfo = order::where('num',$num)
				->where('uuid',session('user')->user_uuid)
				->where('status','0')
				->get();
			$totalPrice = 0;
			$totalPoint = 0;
			$totalNum = 0;
			foreach ($orderInfo as $key => $value) {
				$totalPrice += $value->price;
				$totalPoint += $value->point;
				$totalNum += $value->goods_num;
			}
			if ($num) {
				return view('home.pay',compact('totalPrice','totalPoint','totalNum','num','ads','sale'));
			}else{
				return redirect('/order/send/no');
			}
			
        }
	}

	// 二次请求支付页面
	public function pageAg($num)
	{
		// session(['user'=>'']);
        // 判断session是否存在,如果不存在则跳转Oauth
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $common = new Controller;
            // $app = app('wechat.official_account');

			// 获取用户的收货地址
			$ads = ads::where('uuid',session('user')->user_uuid)
				->where('status','1')
				->first();
			$sale = 1;
			switch (session('user')->user_rank) {
		                case '2':
		                    $sale = '0.8';
		                    break;
		                case '3':
		                    $sale = '0.75';
		                    break;
		                case '4':
		                    $sale = '0.7';
		                    break;
		                case '5':
		                    $sale = '0.65';
		                    break;
		                case '6':
		                    $sale = '0.6';
		                    break;
		                default:
		                    $sale = '1';
		                    break;
		            }
			$orderInfo = order::where('num',$num)
				->where('uuid',session('user')->user_uuid)
				->where('status','0')
				->get();
			$totalPrice = 0;
			$totalPoint = 0;
			$totalNum = 0;
			foreach ($orderInfo as $key => $value) {
				$totalPrice += $value->price;
				$totalPoint += $value->point;
				$totalNum += $value->goods_num;
			}
			return view('home.pay-ag',compact('totalPrice','totalPoint','totalNum','num','ads','sale'));
        }
	}


	// 订单中心页面
	public function list()
	{
		if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
        	$common = new Controller;
            $app = app('wechat.official_account');
			// 获取用户的所有订单
			$data = order::orderBy('status','ASC')
				->where('order.uuid',session('user')->user_uuid)
				->rightJoin('goods','goods.id','=','order.goods_id')
				->orderBy('order.id','DESC')
				->select('goods.goods_name','goods.goods_pic','order.*')
				->get()->groupBy('num');
			// dd($data);
			// $sale = 1;
			// switch (session('user')->user_rank) {
		 //                case '2':
		 //                    $sale = '0.8';
		 //                    break;
		 //                case '3':
		 //                    $sale = '0.75';
		 //                    break;
		 //                case '4':
		 //                    $sale = '0.7';
		 //                    break;
		 //                case '5':
		 //                    $sale = '0.65';
		 //                    break;
		 //                case '6':
		 //                    $sale = '0.6';
		 //                    break;
		 //                default:
		 //                    $sale = '1';
		 //                    break;
		 //            }
			foreach ($data as $key => $value) {
				$totalPrice = 0;
				$totalPoint = 0;
				// dd($value);
				foreach ($value as $v) {
					$totalPrice += $v->price;
					$totalPoint += $v->point;
					$type = $v->type;
					$orderNum = $v->num;
					$time = $v->created_at;
					$status = $v->status;
					$express = $v->express_status;
					$sale = $v->sale;
				}
				$value->sale = $sale;
				$value->payType = $type;
				$value->totalPrice = $totalPrice;
				$value->totalPoint = $totalPoint;
				$value->orderNum = $orderNum;
				$value->created_at = $time;
				$value->status = $status;
				$value->express = $express;
				$order[] = $value;
			}
			// dd($order);
			return view('home.order-list',compact('app','order','sale'));
		}
	}

	public function delete($num)
	{
		if (order::where('num',$num)
			->where('uuid',session('user')->user_uuid)
			->delete()) {
			$result = $this->result('success','删除成功!');
		} else {
			$result = $this->result('fail','删除未支付订单失败,未知错误!');
		}
		return $result;
	}
}
