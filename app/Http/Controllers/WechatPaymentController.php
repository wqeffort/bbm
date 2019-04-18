<?php
/**********************************************************
  .--,       .--,
 ( (  \.---./  ) )
  '.__/o   o\__.'
     {=  ^  =}
      >  -  <
     /       \
    //       \\
   //|   .   |\\
   "'\       /'"_.-~^`'-.
      \  _  /--'         `
    ___)( )(___
   (((__) (__)))    高山仰止,景行行止.虽不能至,心向往之。
#         ┌─┐       ┌─┐
#      ┌──┘ ┴───────┘ ┴──┐
#      │                 │
#      │       ───       │
#      │  ─┬┘       └┬─  │
#      │                 │   神兽保佑
#      │       ─┴─       │   代码无BUG!
#      │                 │
#      └───┐         ┌───┘
#          │         │
#          │         │
#          │         │
#          │         └──────────────┐
#          │                        │
#          │                        ├─┐
#          │                        ┌─┘
#          │                        │
#          └─┐  ┐  ┌───────┬──┐  ┌──┘
#            │ ─┤ ─┤       │ ─┤ ─┤
#            └──┴──┘       └──┴──┘
**********************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

use App\Model\User;
use App\Model\order;
use App\Model\goods;
use App\Model\ads;
use App\Model\recharge;
use App\Model\log_point_down;
use App\Model\log_point_up;
use App\Model\order_wx;
use App\Model\log_point_join;
use App\Model\log_point_user;
use App\Model\join;
use App\Model\log_pay;
use Log;
class WechatPaymentController extends Controller
{

    public function shopOrder($orderNum)
    {
    	$order = order::where('num',$orderNum)
    		->where('status','0')
    		->where('uuid',session('user')->user_uuid)
    		->get();
    	$user = User::where('user_uuid',session('user')->user_uuid)->first();
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
    	$total = $order->sum('point') * $sale;
    	// $total = $order->sum('point');
    	$wechatpay = app('wechatpay.jsapi');
		$wechatpay->setBody('JOYOUS ASPIRATION CLUB 商品购买');
		$wechatpay->setAttach('shop');
		$wechatpay->setOutTradeNo($orderNum);
		$wechatpay->setTotalFee($total * 100);
		$wechatpay->setOpenid(session('user')->user_openid);//公众号openid获取参考微信网页授权

		$result = $wechatpay->prepare();
		// dd($result);
		if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
		    $prepayId = $result['prepay_id'];
		    //WeixinJSBridge
		    $json = $wechatpay->configForPayment($prepayId);
		    $succ_url = 'http://'.env('HTTP_HOST')."/order/send/no";//支付成功回调地址
		    $fail_url = 'http://'.env('HTTP_HOST')."/user";//支付失败回调地址
		    $data = $wechatpay->bridgeHandle($json,$succ_url,$fail_url);
		    // dd($data);
		    return new Response($data);
		}else{
		    $msg = '微信回调失败；请求错误信息：'.$result['return_msg'].'；业务错误信息：'.$result['err_code_des'];
		    return new Response($msg);
		}
    }

    public function recharge($price)
    {
    	$input = Input::all();
    	$wechatpay = app('wechatpay.jsapi');
		$wechatpay->setBody('JOYOUS ASPIRATION CLUB 积分充值');
		$wechatpay->setAttach('recharge');
		$orderNum = "cz".date('YmdHis').rand('100000','999999');
		$wechatpay->setOutTradeNo($orderNum);
		$wechatpay->setTotalFee($price * 100);
		$wechatpay->setOpenid(session('user')->user_openid);//公众号openid获取参考微信网页授权

		$result = $wechatpay->prepare();
		// dd($result);
		if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
		    $prepayId = $result['prepay_id'];
		    //WeixinJSBridge
		    $json = $wechatpay->configForPayment($prepayId);
		    $succ_url = 'http://'.env('HTTP_HOST')."/recharge";//支付成功回调地址
		    $fail_url = 'http://'.env('HTTP_HOST')."/user";//支付失败回调地址
		    $data = $wechatpay->bridgeHandle($json,$succ_url,$fail_url);
		    // dd($data);
		    return new Response($data);
		}else{
		    $msg = '微信回调失败；请求错误信息：'.$result['return_msg'].'；业务错误信息：'.$result['err_code_des'];
		    return new Response($msg);
		}
    }

    public function member($price)
    {
    	$input = Input::all();
    	$wechatpay = app('wechatpay.jsapi');
		$wechatpay->setBody('JOYOUS ASPIRATION CLUB 会籍购买');
		$wechatpay->setAttach('member');
		$orderNum = "member".date('YmdHis').rand('1000','9999');
		$wechatpay->setOutTradeNo($orderNum);
		$wechatpay->setTotalFee($price * 100);
		$wechatpay->setOpenid(session('user')->user_openid);//公众号openid获取参考微信网页授权

		$result = $wechatpay->prepare();
		// dd($result);
		if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
		    $prepayId = $result['prepay_id'];
		    //WeixinJSBridge
		    $json = $wechatpay->configForPayment($prepayId);
		    $succ_url = 'http://'.env('HTTP_HOST')."/user";//支付成功回调地址
		    $fail_url = 'http://'.env('HTTP_HOST');//支付失败回调地址
		    $data = $wechatpay->bridgeHandle($json,$succ_url,$fail_url);
		    // dd($data);
		    return new Response($data);
		}else{
		    $msg = '微信回调失败；请求错误信息：'.$result['return_msg'].'；业务错误信息：'.$result['err_code_des'];
		    return new Response($msg);
		}
    }

    public function callBack()
    {
    	// 判断通知类型。
	    $response = app('wechatpay.jsapi')->handleNotify(function ($notify, $successful) {
	    	// Log::warning($notify);
	    	// Log::warning($successful);
	        $out_trade_no   = $notify->out_trade_no;//商户订单号
	        $transaction_id = $notify->transaction_id;//微信订单号
	        if($successful && $notify->result_code == "SUCCESS" && $notify->return_code == "SUCCESS"){
	        	if (order_wx::where('transaction_id',$transaction_id)->first()) {
	        		return true;
	        	}else{
                    $user = User::where('user_openid',$notify->openid)->first();
                    // $notify->total_fee = 100000;
		        	switch ($notify->attach) {
		        		case 'shop':
					        $ads = ads::where('uuid',$user->user_uuid)
				            ->where('status','1')
				            ->first()
				            ->id;
				            if (order::where('num',$notify->out_trade_no)
						    	->where('status','0')
						    	->where('uuid',$user->user_uuid)
						    	->update(['status'=>'1','ads'=>$ads,'type'=>'1'])) {
				            	$result = true;
				            }
		        		break;
		        		case 'recharge':
		        			switch ($notify->total_fee) {
		        				case '100000':
		        					$point = 1000;
		        					break;
		        				case '500000':
		        					$point = 5000;
		        					break;
		        				case '1000000':
		        					$point = 10000;
		        					break;
		        				case '2000000':
		        					$point = 20000;
		        					break;
		        				case '5000000':
		        					$point = 50000;
		        					break;
		        				case '10000000':
		        					$point = 100000;
		        					break;
		        				default:
		        					$point = 0;
		        					break;
		        			}
		        			// 查询用户信息

		        			// 新的积分
		        			$newPoint = $user->user_point + $point;
		        			// Log::warning($newPoint);
		        			// 写入积分增加日志
	                    	$log_point_up = new log_point_up;
	                    	$log_point_up->uuid = $user->user_uuid;
	                    	$log_point_up->point = $point;
	                    	$log_point_up->type = 3;
	                    	// 写入充值日志
	                    	$recharge = new recharge;
	                    	$recharge->price = $notify->total_fee/100;
	                    	$recharge->uuid = $user->user_uuid;
	                    	$recharge->type = 1;
	                    	$recharge->num = $out_trade_no;
	                    	// 写入用户积分日志
	                    	$userLog = new log_point_user;
	                    	$userLog->uuid = $user->user_uuid;
	                    	$userLog->point = $user->user_point;
	                    	$userLog->point_give = $user->user_point_give;
	                    	$userLog->point_open = $user->user_point_open;
	                    	$userLog->type = 15;
	                    	$userLog->new_point = $newPoint;
	                    	$userLog->new_point_give = $user->user_point_give;
	                    	$userLog->new_point_open = $user->user_point_open;
	                    	$userLog->status = 1;
	                    	$userLog->add = 1;

		        			DB::beginTransaction();
	                    	try{
	                    		$log_point_up->save();
	                    		$userLog->save();
	                    		$recharge->save();
	                    		User::where('user_uuid',$user->user_uuid)
	                    			->update(['user_point'=>$newPoint]);
	                    		// 检查是否有上级加盟商
	                    		if ($user->join_pid) {
	                    			if (join::where('uuid',$user->join_pid)
	                    				->where('type','1')->first()) {
	                    				// 派发奖励到合伙人总部
	                    				$join = join::where('uuid',$user->join_pid)
	                    					->where('type','1')
	                    					->first();
		                    			if ($join) {
		                    				$join = join::where('uuid','596A043D-664B-5A5A-7F54-3C74B9E332F6')
	                    					->where('type','1')
	                    					->first();
		                    				$newPointJoin = $join->point + ($notify->total_fee / 100 * 0.3);
		                    				join::where('uuid','596A043D-664B-5A5A-7F54-3C74B9E332F6')->update([
		                    					"point"=>$newPointJoin
		                    				]);
		                    				$joinLog = new log_point_join;
		                    				$joinLog->uuid = '596A043D-664B-5A5A-7F54-3C74B9E332F6';
		                    				$joinLog->point = $join->point;
		                    				$joinLog->new_point = $newPointJoin;
		                    				$joinLog->to = $user->user_uuid;
		                    				$joinLog->status = 1;
		                    				$joinLog->type = 22;
		                    				$joinLog->save();
		                    			}
	                    			}else{
	                    				// 奖励加盟商消费的百分之25
		                    			$join = join::where('uuid',$user->join_pid)->first();
		                    			if ($join) {
		                    				$newPointFund = $join->point_fund + ($notify->total_fee / 100 * 0.25);
		                    				join::where('uuid',$user->join_pid)->update([
		                    					"point_fund"=>$newPointFund
		                    				]);
		                    				$joinLog = new log_point_join;
		                    				$joinLog->uuid = $user->join_pid;
		                    				$joinLog->point_fund = $join->point_fund;
		                    				$joinLog->new_point_fund = $newPointFund;
		                    				$joinLog->to = $user->user_uuid;
		                    				$joinLog->status = 1;
		                    				$joinLog->type = 12;
		                    				$joinLog->save();
		                    			}
	                    			}
	                    		}
	                    		$order_wx = new order_wx;
	                    		$order_wx->out_trade_no = $out_trade_no;
	                    		$order_wx->transaction_id = $transaction_id;
	                    		$order_wx->total_fee = $notify->total_fee / 100;
	                    		$order_wx->status = 1;
	                    		$order_wx->uuid = $user->user_uuid;
	                    		$order_wx->type = 1;
	                    		$order_wx->save();
	                    		DB::commit();
	                        	$result = true;
			                    }catch (\Exception $e) {
			                    //接收异常处理并回滚
			                    DB::rollBack();
			                        $result = false;
			                    }
		        			// 日志记录积分变动
		        			break;
		        		case 'member':
		        			switch ($notify->total_fee) {
		        				case '100000':
		        					$rank = 1;
		        					$pointGive = 1000;
		        					$temp_join = 1;
		        					break;
		        				case '1000000':
		        					$rank = 2;
		        					$pointGive = 0;
		        					$temp_join = $user->temp_join;
		        					break;
		        			}
		        			
		        			// 新的积分
		        			$newPoint = $user->user_point + $notify->total_fee/100;
		        			$newPointGive = $user->user_point_give + $pointGive;
		        			$log_point_up = new log_point_up;
		        			$log_point_up->uuid = $user->user_uuid;
		        			$log_point_up->point = $notify->total_fee/100;
		        			$log_point_up->type = 4;

		        			// 写入用户积分日志
	                    	$userLog = new log_point_user;
	                    	$userLog->uuid = $user->user_uuid;
	                    	$userLog->point = $user->user_point;
	                    	$userLog->point_give = $user->user_point_give;
	                    	$userLog->type = 15;
	                    	$userLog->new_point = $newPoint;
	                    	$userLog->new_point_give = $newPointGive;
	                    	$userLog->status = 1;
	                    	$userLog->add = 1;


		        			DB::beginTransaction();
	                    	try{
	                    		$log_point_up->save();
	                    		if ($user->rank_start) {
		        					$rank_start = $user->rank_start;
		        				}else{
		        					$rank_start = time();
		        				}
		        				if ($user->join_pid) {
		        					if (join::where('uuid',$user->join_pid)
	                    				->where('type','1')->first()) {
	                    				// 派发奖励到合伙人总部
	                    				$join = join::where('uuid',$user->join_pid)
	                    					->where('type','1')
	                    					->first();
		                    			if ($join) {
		                    				$join = join::where('uuid','596A043D-664B-5A5A-7F54-3C74B9E332F6')
	                    					->where('type','1')
	                    					->first();
		                    				$newPointJoin = $join->point + ($notify->total_fee / 100 * 0.3);
		                    				join::where('uuid','596A043D-664B-5A5A-7F54-3C74B9E332F6')->update([
		                    					"point"=>$newPointJoin
		                    				]);
		                    				$joinLog = new log_point_join;
		                    				$joinLog->uuid = '596A043D-664B-5A5A-7F54-3C74B9E332F6';
		                    				$joinLog->point = $join->point;
		                    				$joinLog->new_point = $newPointJoin;
		                    				$joinLog->to = $user->user_uuid;
		                    				$joinLog->status = 1;
		                    				$joinLog->type = 23;
		                    				$joinLog->save();
		                    			}
	                    			}else{
	                    				// 奖励加盟商消费的百分之25
		                    			$join = join::where('uuid',$user->join_pid)->first();
		                    			if ($join) {
		                    				$newPointFund = $join->point_fund + ($notify->total_fee / 100 * 0.25);
		                    				join::where('uuid',$user->join_pid)->update([
		                    					"point_fund"=>$newPointFund
		                    				]);
		                    				$joinLog = new log_point_join;
		                    				$joinLog->uuid = $user->join_pid;
		                    				$joinLog->point_fund = $join->point_fund;
		                    				$joinLog->new_point_fund = $newPointFund;
		                    				$joinLog->to = $user->user_uuid;
		                    				$joinLog->status = 1;
		                    				$joinLog->type = 12;
		                    				$joinLog->save();
		                    			}
	                    			}
	                    		}
	                    		User::where('user_uuid',$user->user_uuid)
	                    			->update([
	                    				'user_point'=>$newPoint,
	                    				'user_rank'=>$rank,
	                    				'user_point_give'=>$newPointGive,
	                    				'rank_start'=>$rank_start,
	                    				'temp_join'=>$temp_join
	                    			]);
	                    		$order_wx = new order_wx;
	                    		$order_wx->out_trade_no = $out_trade_no;
	                    		$order_wx->transaction_id = $transaction_id;
	                    		$order_wx->total_fee = $notify->total_fee / 100;
	                    		$order_wx->status = 1;
	                    		$order_wx->uuid = $user->user_uuid;
	                    		$order_wx->type = 2;
	                    		$order_wx->save();

	                    		DB::commit();
	                        	$result = true;
			                    }catch (\Exception $e) {
			                    //接收异常处理并回滚
			                    DB::rollBack();
			                        $result = false;
			                 }
		        			break;
		        		default:
		        			$result = false;
		        			break;
		        	}
	        	}
	        }else{
	        	$result = false;
	        }
	    });
	    if ($result) {
			return new Response($response);
		}
    }
}
