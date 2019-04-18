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
use App\Model\log_point_user;
use Log;
class PaymentController extends Controller
{
	// 接收积分抵扣
    public function pointPayment($num)
    {
        $input = Input::all();
    	// 获取到订单,查询出需要抵扣的积分
    	$order = order::where('num',$num)
    		->where('status','0')
    		->where('uuid',session('user')->user_uuid)
    		->get();
        $orderPoint = intval($order->sum('point') * $order['0']->sale);
        $ads = ads::where('uuid',session('user')->user_uuid)
            ->where('status','1')
            ->first()
            ->id;
    	// 获取到用户的最新数据
    	$user = User::where('user_uuid',session('user')->user_uuid)
    		->where('status','1')
    		->first();
        if ($user->user_point_open >= $orderPoint) {
            $newUserPointOpen = $user->user_point_open - $orderPoint;
            $newUserPoint = $user->user_point;
            $newUserPointGive = $user->user_point_give;
        }else{
            if ($user->user_point_open + $user->user_point_give >= $orderPoint) {
                $newUserPointOpen = 0;
                $newUserPointGive = $user->user_point_give + $user->user_point_open - $orderPoint;
                $newUserPoint = $user->user_point;
            }else{
                $newUserPointOpen = 0;
                $newUserPointGive = 0;
                $newUserPoint = $user->user_point + $user->user_point_give + $user->user_point_open - $orderPoint;
            }
        }
        // 写入属性销售
            foreach ($order as $key => $value) {
                $attr = explode(',', $value->goods_attr);
                foreach ($attr as $k => $v) {
                    attribute::find($v)->increment("attr_buy",$value->goods_num);
                }
            }

        // dd($newPoint);
    	if ($newUserPoint >= 0) {
    		DB::beginTransaction();
	        try{
	        	User::where('user_uuid',session('user')->user_uuid)
	    			->update([
                        'user_point'=>$newUserPoint,
                        'user_point_give'=>$newUserPointGive,
                        'user_point_open'=>$newUserPointOpen
                    ]);
	    		order::where('num',$num)
		    		->where('status','0')
		    		->where('uuid',session('user')->user_uuid)
		    		->update([
                        'status'=>'1',
                        'ads'=>$ads,
                        'mark'=>$input['mark']
                    ]);
                $log = new log_point_user;
                $log->uuid = session('user')->user_uuid;
                $log->point = $user->user_point;
                $log->new_point = $newUserPoint;
                $log->type = 1;
                $log->point_give = $user->user_point_give;
                $log->new_point_give = $newUserPointGive;
                $log->point_open = $user->user_point_open;
                $log->new_point_open = $newUserPointOpen;
                $log->status = 1;
                $log->add = 2;
                $log->save();
	            DB::commit();
	           $result = $this->result('success','抵扣成功,当前剩余基本积分: '.$newUserPoint.' 积分,赠送积分:'.$newUserPointGive.' 积分,赠送积分:'.$newUserPointOpen.'积分');
	        }catch (\Exception $e) {
	            //接收异常处理并回滚
	            DB::rollBack();
	           	$result = $this->result('fail','ERROR!系统繁忙,购买失败','');
			}
    	}else{
    		$result = $this->result('fail','当前积分不足以支付!','');
    	}
    	return $result;
    }
}
