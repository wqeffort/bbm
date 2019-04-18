<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\goods;
use App\Model\attribute;
use App\Model\car;
use App\Model\ads;
use App\Model\log_point_user;
use Log;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\JPushController;
use App\Http\Controllers\ExpressController;

class OrderController extends Controller
{
	/**
	 * [makeOrder 生成订单]
	 * @param  uuid
	 * @param  data array
	 * @return [json] [成功返回orderNum]
	 */

    // 订单性能太差.,暂未有时间处理,后期进行修改!
   	public function makeOrder()
   	{
   		$input = Input::all();
        $num = date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,8);
        $i = 0;
        // 先进行检查商品库存
        foreach (json_decode($input['data']) as $key => $value) {
            // 修正订单数量
            car::find($value['0'])->update([
                "goods_num"=>$value['1']
            ]);
            // 获取到购物车中的商品信息
            $car = car::find($value['0']);
            // 获取到商品信息
            $goods = goods::find($car->goods_id);
            // 检查商品
            if ($goods->status) {
                // 获取到属性信息
                $total_attr = 0;
                if (is_numeric($car->attr_array)) {
                    $attr = attribute::find($car->attr_array);
                    if ($attr->status == 1) {
                        // 检查库存
                        if (($attr->attr_depot - $attr->attr_buy - $car->goods_num) >= 0) {
                            $total_attr = $attr->attr_price;
                        }else{
                            $result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了!请在购物车删除该属性的商品再进行结算');
                            return $result;die;
                        }
                    }else{
                        $result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')已经不存在,请在购物车删除该属性的商品再进行结算');
                        return $result;die;
                    }
                }else{
                    foreach (explode(',', $car->attr_array) as $k => $v) {
                        $attr = attribute::find($v);
                        if ($attr->status == 1) {
                            // 检查库存
                            if (($attr->attr_depot - $attr->attr_buy - $car->goods_num) >= 0) {
                                $total_attr += $attr->attr_price;
                            }else{
                                $result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了,请在购物车删除该商品再进行结算');
                                return $result;die;
                            }
                        }else{
                            $result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')已经不存在,请在购物车删除该商品再进行结算');
                            return $result;die;
                        }
                    }
                }

            }else{
                $result = $this->result('fail','商品('.$goods->goods_name.')已经下架,请在购物车删除该商品再进行结算');
            }
        }


        // dd($input);
    	// 生成唯一订单编号
    	foreach (json_decode($input['data']) as $key => $value) {
  			// 获取到购物车中的商品信息
  			$car = car::find($value['0']);
  			// 获取到商品信息
  			$goods = goods::find($car->goods_id);
  			// 检查商品
  			if ($goods->status) {
  				// 获取到属性信息
	  			$total_attr = 0;
	  			if (is_numeric($car->attr_array)) {
	  				$attr = attribute::find($car->attr_array);
	  				if ($attr->status == 1) {
	  					// 检查库存
	  					if (($attr->attr_depot - $attr->attr_buy - $car->goods_num) >= 0) {
	  						$total_attr = $attr->attr_price;
	  					}else{
	  						$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了,请在购物车删除该属性的商品再进行结算');
	  						return $result;die;
	  					}
	  				}else{
	  					$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')已经不存在,请在购物车删除该属性的商品再进行结算');
	  					return $result;die;
	  				}
	  			}else{
	  				foreach (explode(',', $car->attr_array) as $k => $v) {
	  					$attr = attribute::find($v);
	  					if ($attr->status == 1) {
		  					// 检查库存
		  					if (($attr->attr_depot - $attr->attr_buy - $car->goods_num) >= 0) {
		  						$total_attr += $attr->attr_price;
		  					}else{
		  						$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了,请在购物车删除该商品再进行结算');
		  						return $result;die;
		  					}
		  				}else{
		  					$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')已经不存在,请在购物车删除该商品再进行结算');
		  					return $result;die;
		  				}
	  				}
	  			}
                $user = User::where('user_uuid',$input['uuid'])->first();
                switch ($user->user_rank) {
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
	  			// 商品加入订单
	  			$order = new order;
	  			$order->num = $num;
	  			$order->point = ($goods->goods_point + $total_attr) * $car->goods_num;
	  			$order->price = ($goods->goods_price + $total_attr) * $car->goods_num;
	  			$order->goods_id = $goods->id;
	  			$order->goods_num = $car->goods_num;
	  			$order->goods_attr = $car->attr_array;
	  			$order->type = 2;
	  			$order->uuid = $input['uuid'];
	  			$order->status = 0;
                $order->sale = $sale;
	  			$order->end = 0;
	  			if ($order->save()) {
	  				car::find($value['0'])->update([
    					"end"=>1
    				]);
    				$i ++;
	  			}
  			}else{
  				$result = $this->result('fail','商品('.$goods->goods_name.')已经下架,请在购物车删除该商品再进行结算');
  			}
    	}
    	if ($i) {
    		$result = $this->result('success','成功!',$num);
    	}else{
    		$result = $this->result('fail','生成订单失败,请稍后再试!');
    	}
    	return $result;
   	}

   	/**
   	 * [showOrder 显示订单]
   	 * @param  uuid
   	 * @param  orderNum 订单编号
   	 * @return [json]
   	 */
   	public function showOrder()
   	{
   		$input = Input::all();
   		$data = order::where('num',$input['orderNum'])
    		->where('uuid',$input['uuid'])
            ->where('status','0')
    		->get();
        $user = User::where('user_uuid',$input['uuid'])->first();
        if ($data->isEmpty()) {
            $result = $this->result('fail','获取订单信息失败,请稍后再试!');
        }else{
        	$order = array();
        	$info = array();
            $total = 0;
            $total_num = 0;
            foreach ($data as $key => $value) {
                $goods = goods::find($value->goods_id);
                $value['goods_img'] = $goods->goods_pic;
                $value['goods_name'] = $goods->goods_name;
                $total += $value->point;
                $total_num += $value->goods_num;
                $pay_type = $value->type;
                $value['unit_price'] = $value->point / $value->goods_num;
                $attr = array();
                if (is_numeric($value->goods_attr)) {
                    array_push($attr,$value->goods_attr);
                }else{
                    $attr = explode(',', $value->goods_attr);
                }
                $temp = array();
                foreach ($attr as $k => $v) {
                    $temp[] = attribute::find($v)->attr_name;
                }
                $value['attr'] = $temp;
                $info[] = $value;
            }

            // $ads = ads::where('uuid',$input['uuid'])
            //     ->where('status','1')
            //     ->where('del','0')
            //     ->first();
            if ($value->ads) {
                $ads = ads::find($value->ads);
            }else{
                $ads = ads::where('uuid',$input['uuid'])
                    ->where('status','1')
                    ->first();
            }

            $user = User::where('user_uuid',$input['uuid'])
            	->select('user_name','user_nickname','user_phone','user_pic','user_point','user_point_give','user_point_open','user_rank')
            	->first();

            $order['order_num'] = $input['orderNum'];
            $order['user'] = $user;
            if ($ads) {
                $order['ads'] = $ads;
            }else{
                $order['ads'] = array(
                    "id"=>intval(0),
                    "uuid"=>'',
                    "province"=>'',
                    "city"=>'',
                    "area"=>'',
                    "ads"=>'',
                    "name"=>'',
                    "phone"=>'',
                    "status"=>'',
                    "del"=>'',
                    "created_at"=>'',
                    "updated_at"=>''
                );
            }
            $order['total'] = $total;
            $order['total_num'] = $total_num;
            $order['pay_type'] = $pay_type;
            $order['info'] = $info;
            switch ($user->user_rank) {
                case '2':
                    $order['sale'] = '0.8';
                    break;
                case '3':
                    $order['sale'] = '0.75';
                    break;
                case '4':
                    $order['sale'] = '0.7';
                    break;
                case '5':
                    $order['sale'] = '0.65';
                    break;
                case '6':
                    $order['sale'] = '0.6';
                    break;
                default:
                    $order['sale'] = '1';
                    break;
            }
            $result = $this->result('success','获取数据成功!',$order);
        }
        return $result;
   	}

    /**
     * [handleOrder 处理订单]
     * @param  uuid
     * @param  num
     * @param ads
     * @param type
     * @param mark
     * @param password 支付密码 可选值
     * @return [json]
     */
    public function handleOrder()
    {
        $input = Input::all();
        $user = User::where('user_uuid',$input['uuid'])->first();
        switch ($user->user_rank) {
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
        // 核验并修改订单
        if (order::where('num', $input['num'])
            ->where('uuid', $input['uuid'])
            ->where('status','0')
            ->update([
                "mark"=>$input['mark'],
                "type"=>$input['type'],
                "ads"=>$input['ads'],
                "sale"=>$sale
            ])) {
            switch ($input['type']) {
                case '1':
                    $input = Input::all();
                    $payment = new PaymentController;
                    return $payment->wechat($input['num'],$input['uuid'],$input['type']);
                    break;
                case '2':
                    $input = Input::all();
                    $user = User::where('user_uuid',$input['uuid'])->first();
                        // 密码支付
                    if (empty($user->cash_password)) {
                        $result = $this->result('fail','您的账号尚未设置支付密码,请跳转个人中心设置!');
                    }else{
                        if (Crypt::decrypt($user->cash_password) == $input['password']) {
                            // 处理订单
                            $result = $this->callBackOrder(2,$input['num']);
                        }else{
                            $result = $this->result('fail','支付密码不正确,请重新输入!');
                        }
                    }
                    return $result;
                    break;
            }
        }else{
            $result = $this->result('fail','核验订单失败,请稍后再试!');
        }
        return $result;
    }


    public function callBackOrder($type,$num,$transaction_id = '')
    {
        // 获取到订单
        $order = order::where('num',$num)
            ->where('status','0')
            ->where('end','0')
            ->get();
        $user= User::where('user_uuid',$order['0']->uuid)->first();
        switch ($user->user_rank) {
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
        $total = intval($order->sum('point') * $sale);
        // Log::warning($order);
        // 处理商品属性的库存
        foreach ($order as $key => $value) {
            // 判断是否是多属性
            if (is_numeric($value->goods_attr)) {
                attribute::find($value->goods_attr)->increment('attr_buy',$value->goods_num);
            }else{
                foreach (explode(',', $value->goods_attr) as $k => $v) {
                    attribute::find($v)->increment('attr_buy',$value->goods_num);
                }
            }
        }
        switch ($type) {
            // 微信支付
            case '1':
                // 修改订单
                DB::beginTransaction();
                    try{
                        order::where('num',$num)
                            ->where('status','0')
                            ->update([
                                'status'=>'1',
                                'type'=>'1',
                                'transaction_id'=>$transaction_id
                            ]);
                        DB::commit();
                        // 发起推送
                        $this->push(array($this->uuidStr($user->user_uuid)),'2','商城购物支付成功!','亲爱的用户,我们已经收到了您的订单,尽快为您进行发货,运单详情请查看订单中心.温馨提示:充值积分再购物更划算哟!','web','http://'.env('HTTP_HOST').'view/order/info/'.$num.'','http://'.env('HTTP_HOST').'/img/logo_m.png');
                        $result = $this->result('success','商城购物支付成功!','亲爱的用户,我们已经收到了您的订单,尽快为您进行发货,运单详情请查看订单中心.温馨提示:充值积分再购物更划算哟!');
                    }catch (\Exception $e) {
                        //接收异常处理并回滚
                        DB::rollBack();
                        $result = $this->result('fail','ERROR!系统繁忙,购买失败','');
                    }
                break;
            // 积分支付
            case '2':
                if ($user->user_point_open >= $total) {
                    $newPoint = $user->user_point;
                    $newPointGive = $user->user_point_give;
                    $newPointOpen = $user->user_point_open - $total;
                }elseif (($user->user_point_open + $user->user_point_give) >= $total) {
                    $newPoint = $user->user_point;
                    $newPointGive = $user->user_point_give + $user->user_point_open - $total;
                    $newPointOpen = 0;
                }else{
                    $newPoint = $user->user_point + $user->user_point_give + $user->user_point_open - $total;
                    $newPointGive = 0;
                    $newPointOpen = 0;
                }
                if ($newPoint >= 0) {
                    // 扣分写入日志
                    // 修改用户积分
                    // 修改订单
                    DB::beginTransaction();
                    try{
                        User::where('user_uuid',$user->user_uuid)
                            ->update([
                                'user_point'=>$newPoint,
                                'user_point_give'=>$newPointGive,
                                'user_point_open'=>$newPointOpen
                            ]);
                        order::where('num',$num)
                            ->where('status','0')
                            ->where('uuid',$user->user_uuid)
                            ->update([
                                'status'=>'1',
                                'type'=>'2',
                                'sale'=>$sale
                            ]);
                        $log = new log_point_user;
                        $log->uuid = $user->user_uuid;
                        $log->point = $user->user_point;
                        $log->new_point = $newPoint;
                        $log->type = 1;
                        $log->point_give = $user->user_point_give;
                        $log->new_point_give = $newPointGive;
                        $log->point_open = $user->user_point_open;
                        $log->new_point_open = $newPointOpen;
                        $log->status = 1;
                        $log->add = 2;
                        $log->save();
                        DB::commit();
                        // 发起推送
                        $this->push(array($this->uuidStr($user->user_uuid)),'2','商城积分购物扣费成功!','尊敬的梦享家俱乐部会员,您的积分抵扣成功,当前剩余基本积分: '.$newPoint.' 积分,赠送积分:'.$newPointGive.' 积分,开拓积分:'.$newPointOpen.'积分','web','http://'.env('HTTP_HOST').'view/order/info/'.$num.'','http://'.env('HTTP_HOST').'/img/logo_m.png');
                        $result = $this->result('success','抵扣成功,当前剩余基本积分: '.$newPoint.' 积分,赠送积分:'.$newPointGive.' 积分,开拓积分:'.$newPointOpen.'积分');
                    }catch (\Exception $e) {
                        //接收异常处理并回滚
                        DB::rollBack();
                        $result = $this->result('fail','ERROR!系统繁忙,购买失败','');
                    }
                }else{
                    $result = $this->result('fail','您当前积分不足以支付商品金额,请充值后再进行购买!');
                }
                break;
            // 支付宝支付
            case '3':
                # code...
                break;
        }
        return $result;
    }

    /**
     * [selectOrder 订单查询接口]
     * @param  num
     * @return json
     */
    public function selectOrder()
    {
        $input = Input::all();
        if (order::where('num',$input['num'])
            ->where('status','1')
            ->first()) {
            $result = $this->result('success','订单支付成功!');
        }else{
            $result = $this->result('fail','订单未支付成功!');
        }
        return $result;
    }

    /**
     * [all 获取所有订单]
     * @param uuid
     * @return [json]
     */
    public function all()
    {
        $input = Input::all();
        $user = User::where('user_uuid',$input['uuid'])->first();
        $data = order::orderBy('order.id','DESC')
            ->where('order.uuid',$input['uuid'])
            ->leftJoin('goods','goods.id','=','order.goods_id')
            ->select('order.*','goods.goods_name','goods.goods_pic','goods.status as goodsStatus')
            ->get()
            ->groupBy('num');
        $order = array();
        foreach ($data as $key => $value) {
            $cc = array();
            $total = 0;
            foreach ($value as $k => $v) {
                if (is_numeric($v->goods_attr)) {
                    $v->goods_attr .= attribute::find($v->goods_attr)->attr_name;
                }else{
                    foreach (explode(',', $v->goods_attr) as $ke => $va) {
                        $v->goods_attr .= attribute::find($va)->attr_name."  ";
                    }
                }
                $total += $v->point;
                $id = $v->id;
                $type = $v->type;
                $num = $v->num;
                $express = $v->express;
                $express_status = $v->express_status;
                $mark = $v->mark;
                $status = $v->status;
                $end = $v->end;
                $time = $v->created_at->toDateTimeString();
                $cc['order'][] = $v;
            }
            $cc['total'] = $total;
            $cc['pay'] = intval($total * $v->sale);
            $cc['end'] = $end;
            $cc['status'] = $status;
            $cc['id'] = $id;
            $cc['type'] = $type;
            $cc['num'] = $num;
            $cc['express'] = $express;
            $cc['express_status'] = $express_status;
            $cc['mark'] = $mark;
            $cc['time'] = $time;
            $order[] = $cc;
        }
        return $this->result('success','获取成功!',$order);
    }

    /**
     * [agree 确认订单]
     * @param uuid
     * @param num 订单编号
     * @return [type] [description]
     */
    public function agree()
    {
        $input = Input::all();
        if (order::where('num',$input['num'])
            ->where('uuid',$input['uuid'])
            ->where('status','1')
            ->where('end','1')
            ->update([
                'end'=>2
            ])) {
            $result = $this->result('success','确认收货成功!');
        }else{
            $result = $this->result('fail','确认收货失败!');
        }
        return $result;
    }

    /**
     * [orderExpress 查询物流信息]
     * @param num
     * @return [json]
     */
    public function orderExpress()
    {
        $input = Input::all();
        $express = new ExpressController;
        // 获取到订单信息和订单编号
        $order = order::where('num',$input['num'])
            ->whereNotNull('express')
            ->first();
        if ($order) {
            if ($order->express && $order->ads) {
                $expressNum = explode(',',$order->express);
                return $express->info($expressNum['0'],ads::find($order->ads)->phone);
            }else{
                return $this->result('fail','获取物流信息失败!');
            }
        }else{
            return $this->result('fail','获取物流信息失败!');
        }
    }
}



