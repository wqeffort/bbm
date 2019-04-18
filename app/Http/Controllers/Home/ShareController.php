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
use App\Model\set;
use App\Model\join;
use App\Model\log_point_up;
use Log;
class ShareController extends Controller
{
	// 接收分享后的回调(分享到个人)
    public function appMessage()
    {
    	// 查询今天是否分享过, 分享到个人,一共十次
    	$logInfo = log_point_up::where('uuid',session('user'))
    		->get()->count();
    	if ($logInfo > 9) {
    		$result = $this->result('fail','您今天已经没有分享奖励次数了,目前已经分享了'.($logInfo).'次了','');
    	}else{
    		$user = User::where('user_uuid',session('user')->user_uuid)
    			->first();
    		$shares = 9 - $logInfo;
    		$newPoint = $this->set('add_share_appmessage') + $user->user_point;
    		DB::beginTransaction();
            try {
            	User::where('user_uuid',$user->user_uuid)
                    ->update([
                        'user_point'=>$newPoint
                    ]);
                // 日志记录
                $logPointUp = new log_point_up;
                $logPointUp->uuid = $user->user_uuid;
                $logPointUp->point = $newPoint;
                $logPointUp->type = '2';
                $logPointUp->save();
                DB::commit();
                // 业务逻辑
                $result = $this->result('success','积分增加,'.$this->set('add_share_appmessage').',今天剩余分享奖励次数为:'.$shares.'次','');
            }catch (\Exception $e) {
                // 业务逻辑
                $result = $this->result('success','分享成功,因系统繁忙,积分未增加','');
                //接收异常处理并回滚
                DB::rollBack();
            }
    	}
    	return $result;
    }

    public function shareQrcodePost()
    {
        // 制作名片
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        $join = join::where('uuid',session('user')->user_uuid)->first();
        if ($join) {
        	$url = "http://".env('HTTP_HOST')."/handlePid/".$user->user_uuid."/".$user->user_uuid;
        }else{
        	if ($user->join_pid) {
	            $url = "http://".env('HTTP_HOST')."/handlePid/".$user->user_uuid."/".$user->join_pid;
	        }else{
	            $url = "http://".env('HTTP_HOST')."/handlePid/".$user->user_uuid."/596A043D-664B-5A5A-7F54-3C74B9E332F6";
	        }
        }
        $qrcode = $this->makeQrcode($url,$user->user_pic);
        if ($qrcode) {
            $data['qrcode'] = 'http://'.env('HTTP_HOST')."/".$qrcode;
            $data['url'] = $url;
            $result = $this->result('success','成功!',$data);
        }else{
            $result = $this->result('fail','获取二维码失败,请稍后再试!');
        }
        return $result;
    }
    public function shareQrcode()
    {
        return view('home.share-sale');
    }
}
