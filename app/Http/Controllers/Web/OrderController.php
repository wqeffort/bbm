<?php

namespace App\Http\Controllers\Web;
// 引用订单处理控制器
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\JPushController;

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

class OrderController extends Controller
{
    public function index($value='')
    {
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        $data = order::orderBy('order.id','DESC')
            ->where('order.uuid',session('user')->user_uuid)
            ->leftJoin('goods','goods.id','=','order.goods_id')
            ->select('order.*','goods.goods_name','goods.goods_pic')
            ->get()
            ->groupBy('num');
        $order = array();
        foreach ($data as $key => $value) {
            $cc = array();
            $total = 0;
            foreach ($value as $k => $v) {
                $total += $v->point;
                $cc[] = $v;
            }
            $cc['total'] = $total;
            $order[] = $cc;
        }
        // dd($order);
        return view('view.order',compact('user','order'));
    }

    public function makeOrder()
    {
    	$input = Input::all();
    	// 生成唯一订单编号
    	$num = date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,8);
    	$i = 0;
    	foreach ($input['data'] as $key => $value) {
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
	  						$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了!,请在购物车删除该属性的商品再进行结算');
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
		  						$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')没货了!,请在购物车删除该商品再进行结算');
		  						return $result;die;
		  					}
		  				}else{
		  					$result = $this->result('fail','该属性的商品('.$goods->goods_name." ".$attr->attr_name.')已经不存在,请在购物车删除该商品再进行结算');
		  					return $result;die;
		  				}
	  				}
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
	  			$order->uuid = session('user')->user_uuid;
	  			$order->status = 0;
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


    public function setOrder($num)
    {
    	$data = order::where('num',$num)
    		->where('uuid',session('user')->user_uuid)
            ->where('status','0')
    		->get();
        if ($data->isEmpty()) {
            return redirect('view/order');
        }else{
            $order = array();
            $total = 0;
            $total_num = 0;
            foreach ($data as $key => $value) {
                $goods = goods::find($value->goods_id);
                $value['goods_img'] = $goods->goods_pic;
                $value['goods_name'] = $goods->goods_name;
                $total += $value->point;
                $total_num += $value->goods_num;
                $pay_type = $value->type;
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
                $order[] = $value;
            }
            $ads = ads::where('uuid',session('user')->user_uuid)
                ->where('status','1')
                ->where('del','0')
                ->first();
            $user = User::where('user_uuid',session('user')->user_uuid)->first();
            return view('view.order-no',compact('num','user','order','ads','total','total_num','pay_type'));
        }
    }

    // 用户提交订单.录入地址
    public function editAds()
    {
        $input = Input::all();
        // dd($input);
        if (order::where('num',$input['num'])->update([
            "ads"=>$input['ads'],
            "mark"=>$input['mark']
        ])) {
            $result = $this->result('success','成功!');
        }else{
            $result = $this->result('fail','提交备注信息和地址ID失败!');
        }
        return $result;
    }

    public function viewOrder($num)
    {
        $this->push(array($num),'2','商城积分购物扣费成功!','尊敬的梦享家俱乐部会员,您的积分抵扣成功,当前剩余基本积分: 1 积分,赠送积分:2 积分,赠送积分:3 积分','web','view/order/info/'.$num.'','http://'.env('HTTP_HOST').'/img/logo_m.png');

        $data = order::where('num',$num)
            ->where('uuid',session('user')->user_uuid)
            ->where('status','1')
            ->get();
        if ($data->isEmpty()) {
            return redirect('view/order');
        }else{
            $order = array();
            $total = 0;
            $total_num = 0;
            foreach ($data as $key => $value) {
                $goods = goods::find($value->goods_id);
                $value['goods_img'] = $goods->goods_pic;
                $value['goods_name'] = $goods->goods_name;
                $total += $value->point;
                $total_num += $value->goods_num;
                $pay_type = $value->type;
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
                $order[] = $value;
            }
            $ads = ads::find($data['0']->ads);
            $user = User::where('user_uuid',session('user')->user_uuid)->first();
            return view('view.order-no',compact('num','user','order','ads','total','total_num','pay_type'));
        }
    }



    // 用户请求微信支付
    public function wechat()
    {
        $input = Input::all();
        $payment = new PaymentController;
        return $payment->wechat($input['num'],session('user')->user_uuid,$input['type']);
    }

    // 用户请求积分支付
    public function point()
    {
        $input = Input::all();
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        if ($input['type'] == '1') {
            // 密码支付
            if (empty($user->password)) {
                $result = $this->result('fail','您的账号尚未设置支付密码,请跳转个人中心设置!');
            }else{
                if (Crypt::decrypt($user->password) == $input['password']) {
                    // 处理订单
                    $result = $this->handleOrder(2,$input['num']);
                }else{
                    $result = $this->result('fail','支付密码不正确,请重新输入!');
                }
            }
        }else{
            // 手机验证码支付
            if (empty($user->user_phone)) {
                $result = $this->result('fail','您的账号尚未设置手机号码,请跳转个人中心设置!');
            }else{
                if (session('smsCode') == $input['code']) {
                    // 处理订单
                    $result = $this->handleOrder(2,$input['num']);
                }else{
                    $result = $this->result('fail','短信验证码不正确,请重新输入!');
                }
            }
        }
        return $result;
    }

    // 订单处理控制器
    public function handleOrder($type,$num,$transaction_id = '')
    {
        // 获取到订单
        $order = order::where('num',$num)
            // ->where('uuid',session('user')->user_uuid)
            ->where('status','0')
            ->where('end','0')
            ->get();
        $total = $order->sum('point');
        // Log::warning($order);
        $user= User::where('user_uuid',$order['0']->uuid)->first();
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
                        $this->push(array($this->uuidStr($user->user_uuid)),'2','商城购物支付成功!','亲爱的用户,我们已经收到了您的订单,尽快为您进行发货,运单详情请查看订单中心.温馨提示:充值积分在购物更划算哟!','web','http://'.env('HTTP_HOST').'view/order/info/'.$num.'','http://'.env('HTTP_HOST').'/img/logo_m.png');
                        $result = $this->result('success','商城购物支付成功!','亲爱的用户,我们已经收到了您的订单,尽快为您进行发货,运单详情请查看订单中心.温馨提示:充值积分在购物更划算哟!');
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
                        User::where('user_uuid',session('user')->user_uuid)
                            ->update([
                                'user_point'=>$newPoint,
                                'user_point_give'=>$newPointGive,
                                'user_point_open'=>$newPointOpen
                            ]);
                        order::where('num',$num)
                            ->where('status','0')
                            ->where('uuid',session('user')->user_uuid)
                            ->update([
                                'status'=>'1',
                                'type'=>'2'
                            ]);
                        $log = new log_point_user;
                        $log->uuid = session('user')->user_uuid;
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
                        $this->push(array($this->uuidStr($user->user_uuid)),'2','商城积分购物扣费成功!','尊敬的梦享家俱乐部会员,您的积分抵扣成功,当前剩余基本积分: '.$newPoint.' 积分,赠送积分:'.$newPointGive.' 积分,赠送积分:'.$newPointOpen.'积分','web','http://'.env('HTTP_HOST').'view/order/info/'.$num.'','http://'.env('HTTP_HOST').'/img/logo_m.png');
                        $result = $this->result('success','抵扣成功,当前剩余基本积分: '.$newPoint.' 积分,赠送积分:'.$newPointGive.' 积分,赠送积分:'.$newPointOpen.'积分');
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
}
