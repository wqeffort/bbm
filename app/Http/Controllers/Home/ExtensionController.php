<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\order;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\goods;
use App\Model\log_point_up;
use Log;
class ExtensionController extends Controller
{
	// 用户推广页面
    public function info()
    {
    	if (empty(session('user'))) {
    		// 储存pid,先验证是否在正确,是否存在自己绑定自己的情况
    		if (user::where('user_uuid',$uuid)->first() && $uuid != sesson('user')->user_uuid) {
    			session(['ACTION_PID'=>$uuid]);
    		}
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
	    	// 查询出用户的资料
	    	$app = app('wechat.official_account');
	    	// dd(session('user'));
	        $user = User::where('user_uuid',session('user')->user_uuid)
	            ->first();
	        // 查询出下级用户群
	        $second = User::where('user_pid',session('user')->user_uuid)
	        	->where('status','1')
	        	->get();
	        $url = "http://".env('HTTP_HOST')."/extension/handle/".$user->user_uuid;
	    	$qrcode = $this->makeQrcode($url);
	       	return view('home.extension-info',compact('app','user','second','url','qrcode'));
	    }
    }



    public function handle($uuid)
    {
    	if (empty(session('user'))) {
    		// 储存pid,先验证是否在正确,是否存在自己绑定自己的情况
    		if (user::where('user_uuid',$uuid)->first() && $uuid != sesson('user')->user_uuid) {
    			session(['ACTION_PID'=>$uuid]);
    		}
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
        	// 验证UUID是否存在
	    	if ($info = User::where('user_uuid',$uuid)->first()) {
	    		// 检查用户的pid是否为空
	    		$user = User::where('user_uuid',session('user')->user_uuid)
	    			->first();
	    		if (empty($user->user_pid)) {
	    			DB::beginTransaction();
	                try {
	                	User::where('user_uuid',session('user')->user_uuid)
	    				->update([
	    					"user_pid"=>$uuid
	    				]);
	                	// 添加积分
	                	$newPoint = $info->user_point + $this->set('add_extension');
	                	User::where('user_uuid',$uuid)->update([
	                		"user_point"=>$newPoint
	                	]);
	                	// 日志记录
	                	$logPointUp = new log_point_up;
	                	$logPointUp->uuid = $uuid;
	                	$logPointUp->point = $newPoint;
	                	$logPointUp->type = '1';
	                	$logPointUp->save();
						DB::commit();
						// 业务逻辑
					}catch (\Exception $e) {
						// 业务逻辑
						session(['ACTION_PID'=>$uuid]);
						//接收异常处理并回滚
						DB::rollBack();
					}
	    		}
	    	}
        }
        return redirect('/');
    }
}
