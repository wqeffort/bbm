<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\Store\OrderController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

// 加载Model
use App\Model\User;
use App\Model\order;
use Log;

class PaymentController extends Controller
{
	/**
	 * [wechat 微信APP支付 ]
	 * @param [token] [token]
	 * @param [uuid] [uuid]
	 * @param [key] [key]
	 * @param [type] [超市订单:pay_goods]
	 * @param [price] [价格]
	 * @param [num] [订单编号]
	 * 其余用到再定
	 */
    public function wechat($num,$uuid,$type)
    {
    	$input = Input::all();
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
        // $total = intval($order->sum('point'));

    	$wechatpay = app('wechatpay.app');
		$wechatpay->setBody('梦享家会员商城购物');
		$wechatpay->setOutTradeNo($num);

		$wechatpay->setTotalFee($total * 100);
		$wechatpay->setAttach($uuid."|".$type."|".$num);

		$result = $wechatpay->prepare();
		if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
		    $prepayId = $result['prepay_id'];
		    // return $wechatpay->configForPayment($prepayId);
		    return $this->result('success','成功!',$wechatpay->configForPayment($prepayId));
		}else{
		    // return '微信回调失败；请求错误信息：'.$result['return_msg'].'；业务错误信息：'.$result['err_code_des'];
		    return $this->result('fail','微信回调失败；请求错误信息'.$result['return_msg'].'；业务错误信息：'.$result['err_code_des']);
		}
    }

    public function wechatNotify()
    {
    	// dd("http://".env('HTTP_HOST','localhost')."/notify/wechat");
    	// 判断通知类型。
	    $response = app('wechatpay.app')->handleNotify(function ($notify, $successful) {
	    	Log::notice($notify);
	        $out_trade_no = $notify->out_trade_no;//商户订单号
	        $transaction_id = $notify->transaction_id;//微信订单号
	        // 处理订单
	        if ($successful && $notify->result_code == "SUCCESS" && $notify->return_code == "SUCCESS") {
	            //todo 处理支付成功，，，
	           Log::warning($notify);
	           $handle = new OrderController;
	           $handle->callBackOrder('1',$out_trade_no,$transaction_id);
			}
		});
	    return Response($response);
    }
}
