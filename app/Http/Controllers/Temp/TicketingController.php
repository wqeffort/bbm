<?php

namespace App\Http\Controllers\Temp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\goods;
use App\Model\attribute;
use App\Model\ticket;
use App\Model\ticket_order;
use App\Model\log_point_user;
use App\Model\card;
class TicketingController extends Controller
{
    public function ticket()
    {
		// dd(1);
    	$app = app('wechat.official_account');
    	$ticket = ticket::get();
    	$t1 = ticket::where('desk','1')->first();
    	$t2 = ticket::where('desk','2')->first();
    	$t3 = ticket::where('desk','3')->first();
    	$t5 = ticket::where('desk','5')->first();
    	$t6 = ticket::where('desk','6')->first();
    	$t7 = ticket::where('desk','7')->first();
    	$t8 = ticket::where('desk','8')->first();
    	$t9 = ticket::where('desk','9')->first();
    	$t10 = ticket::where('desk','10')->first();
    	$t11 = ticket::where('desk','11')->first();
    	$t12 = ticket::where('desk','12')->first();
    	$t13 = ticket::where('desk','13')->first();
    	$t15 = ticket::where('desk','15')->first();
    	$t16 = ticket::where('desk','16')->first();
    	$t17 = ticket::where('desk','17')->first();
    	$t18 = ticket::where('desk','18')->first();
    	$t19 = ticket::where('desk','19')->first();
    	$t20 = ticket::where('desk','20')->first();
    	$t21 = ticket::where('desk','21')->first();
    	$t22 = ticket::where('desk','22')->first();
    	$t23 = ticket::where('desk','23')->first();
    	$t25 = ticket::where('desk','25')->first();
    	$t26 = ticket::where('desk','26')->first();
    	$t27 = ticket::where('desk','27')->first();
    	$t28 = ticket::where('desk','28')->first();
    	$t29 = ticket::where('desk','29')->first();
    	$t30 = ticket::where('desk','30')->first();
    	$t31 = ticket::where('desk','31')->first();
    	$t32 = ticket::where('desk','32')->first();
    	$t33 = ticket::where('desk','33')->first();
    	$t35 = ticket::where('desk','35')->first();
    	$t36 = ticket::where('desk','36')->first();
    	$t37 = ticket::where('desk','37')->first();
    	$t38 = ticket::where('desk','38')->first();
    	$t39 = ticket::where('desk','39')->first();
    	$t50 = ticket::where('desk','50')->first();
    	$t51 = ticket::where('desk','51')->first();
    	$t52 = ticket::where('desk','52')->first();
    	$t53 = ticket::where('desk','53')->first();
    	$t55 = ticket::where('desk','55')->first();
    	return view('home.ticket',compact('app', 'ticket','t1','t2','t3','t5','t6','t7','t8','t9','t10','t11','t12','t13','t15','t16','t17','t18','t19','t20','t20','t21','t22','t23','t25','t26','t27','t28','t29','t30','t31','t32','t33','t35','t36','t37','t38','t39','t50','t51','t52','t53','t55'));
    }

    public function add()
    {
    	$input = Input::all();
    	// 查询出该桌子的剩余座位
    	$ticket = ticket::where('desk', $input['desk'])->first();
    	if ($input['type'] == 'all') {
    		if ($ticket->id == 1 || $ticket->id == 2 || $ticket->id == 3 || $ticket->id == 4 || $ticket->id == 5 || $ticket->id == 6 || $ticket->id == 7 || $ticket->id == 8) {
    			if ($ticket->buy == '1' || $ticket->buy == '0') {
    				$isBuy = true;
    			}else{
    				$isBuy = false;
    			}
    		}else{
    			if ($ticket->buy == '0') {
    				$isBuy = true;
    			}else{
    				$isBuy = false;
    			}
    		}
    		if ($isBuy) {
    			$order = new ticket_order;
    			$order->order_num = date('YmdHis').rand('100000','999999');
    			$order->uuid = session('user')->user_uuid;
    			$order->ticket_id = $ticket->id;
    			$order->num = 11;
    			$order->price = $ticket->price * 10;
    			if ($input['pay'] == 'price') {
    				$order->type = 1;
    			}else{
    				$order->type = 2;
    			}
    			if ($order->save()) {
    				$result = $this->result('success','成功!',$order);
    			}else{
    				$result = $this->result('fail','未能储存数据,请刷新后再试!');
    			}
    		}else{
    			$result = $this->result('fail','该桌子已经卖出过座位!');
    		}
    	}else{
    		if ($ticket->id < 9) {
    			$result = $this->result('fail','v1~v9 只能整桌一同购买!');
    		}else{
    			if (($ticket->surplus - $ticket->buy) >= $input['num']) {
	    			$order = new ticket_order;
	    			$order->order_num = date('YmdHis').rand('100000','999999');
	    			$order->uuid = session('user')->user_uuid;
	    			$order->ticket_id = $ticket->id;
	    			$order->num = $input['num'];
	    			$order->price = $ticket->price * $input['num'];
	    			if ($input['pay'] == 'price') {
	    				$order->type = 1;
	    			}else{
	    				$order->type = 2;
	    			}
	    			if ($order->save()) {
	    				$result = $this->result('success','成功!',$order);
	    			}else{
	    				$result = $this->result('fail','未能储存数据,请刷新后再试!');
	    			}
	    		}else{
	    			$result = $this->result('fail','座位不足,请更换桌子后再试!');
	    		}
    		}
    	}
    	return $result;
    }

    public function pay()
    {
    	$input = Input::all();
    	$order = ticket_order::where('order_num',$input['num'])->first();
    	// 检查是否还有空位
    	$ticket = ticket::find($order->ticket_id);
    	if (($ticket->buy + ($order->price / $ticket->price)) <= 11) {
    		if ($order->status == 0 && session('user')->user_uuid == $order->uuid) {
	    		$user = User::where('user_uuid',$order->uuid)
	    			->first();
	    		if ($user) {
	    			if (empty($user->cash_password)) {
	    				$result = $this->result('fail','未设置支付密码,请到个人中心,信息管理中设置支付密码!');
	    			}else{
	    				if (Crypt::decrypt($user->cash_password) == $input['password']) {
	    					if (($user->user_point_give + $user->user_point) >= $order->price) {
	    						if ($user->user_point_give >= $order->price) {
	    							$newPoint = $user->user_point;
	    							$newPointGive = $user->user_point_give - $order->price;
	    						}else{
	    							$newPoint = ($user->user_point_give + $user->user_point) - $order->price;
	    							$newPointGive = 0;
	    						}
	    						// 进行入库动作
	    						DB::beginTransaction();
				                try{
				                    // 修改订单状态
				                    ticket_order::where('order_num',$input['num'])
				                    	->update([
				                    		"mark"=>$input['mark'],
				                    		"status"=>1,
				                    		"name"=>$input['name'],
				                    		"phone"=>$input['phone']
				                    	]);
				                    // 修座位售卖数
				                    if ($order->price / $ticket->price == 10) {
				                    	ticket::where('id',$order->ticket_id)
				                    	->update(['buy'=>11]);
				                    }else{
				                    	ticket::where('id',$order->ticket_id)
				                    	->update([
				                    		'buy'=>$ticket->buy + ($order->price / $ticket->price)
				                    	]);
				                    }
				                    // 扣除用户积分
				                    User::where('user_uuid',session('user')->user_uuid)->update([
				                    	"user_point"=>$newPoint,
				                    	"user_point_give"=>$newPointGive
				                    ]);
				                    // 写入用户积分日志
				                    $log = new log_point_user;
				                    $log->uuid = $user->user_uuid;
				                    $log->point = $user->user_point;
				                    $log->point_give = $user->user_point_give;
				                    $log->type = 1;
				                    $log->new_point = $newPoint;
				                    $log->new_point_give = $newPointGive;
				                    $log->status = 1;
				                    $log->add = 2;
				                  	$log->save();
				                    // 写入卡券包
				                    $card = new card;
				                    $card->type = 1;
				                    $card->num = $order->order_num;
				                    $card->uuid = $user->user_uuid;
				                    $card->status = 1;
                                    if ($order->price / $ticket->price == 10) {
                                        $card->times = 11;
                                    }else{
                                        $card->times = $order->price / $ticket->price;
                                    }
                                    $card->end_time = "2018-09-03 00:00:00";
				                    $card->save();
				                    DB::commit();
				                    $result = $this->result('success','购买成功,工作人员稍后将会邮寄票据给您!');
				                }catch (\Exception $e) {
				                    //接收异常处理并回滚
				                    DB::rollBack();
				                    $result = $this->result('fail','ERROR!系统繁忙,购买失败,请稍后再试!');
				                }
	    					}else{
	    						$result = $this->result('fail','当前积分不足以抵扣订单金额!');
	    					}
	    				}else{
	    					$result = $this->result('fail','支付密码错误,请重新输入!');
	    				}
	    			}
	    		}
	    	}else{
	    		$result = $this->result('fail','该订单已经支付,无需重复支付!');
	    	}
    	}else{
    		$result = $this->result('fail','座位数不足,请刷新页面后重试!');
    	}
    	return $result;
    }

    public function isCard($num)
    {
        // dd($num);
        $info = card::where('num',explode(',', $num)['1'])->where('status','1')->first();
        if ($info) {
            if ($info->end == 1) {
                $result = $this->result('fail','该电子票据已经兑换过了,请勿重复兑换!');
            }else{
                $result = $this->result('success','该电子票据当前剩余未兑换数量为:'.$info->times.'张,请问是否现在兑换!',$info);
            }
        }else{
            $result = $this->result('fail','未查询到票务信息!');
        }
        return $result;
    }

    public function isCardPost($num)
    {
        if (card::where('num',explode(',', $num)['1'])->update(['end'=>'1'])) {
            $result = $this->result('success','电子票据兑换成功!');
        }else{
            $result = $this->result('fail','兑换失败,未知的错误!');
        }
        return $result;
    }
}
