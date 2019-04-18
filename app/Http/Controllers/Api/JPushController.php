<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

// 加载Model
use App\Model\User;

use JPush\Client as JPush;
use Log;

class JPushController extends Controller
{
	public function notice($title="",$text="",$array=array(),$url="",$type="系统通知",$jump="app")
	{
		$client = new JPush(env('JPUSH_KEY'), env('JPUSH_SECRET'), storage_path('logs/push.log'));
		$push = $client->push();
		 // 配置推送的平台
        $push->setPlatform(['ios','android']); // OR ios/android
		// $push->addAllAudience('all'); // all为广播,tag标签,alias别名
		// $push->setNotificationAlert('我是标题');
		$push->addAlias($array);
		// 构造扩展消息
		$extrasArray = array(
            'tit' => $title, // 消息标题
            'txt' => $text,  // 消息内容
            'type' => $type, // 消息类型
            'img' => '',    // 消息附加图片
            'jupm' =>$jump, // 跳转方法 (view,app)
            'url' => $url // 跳转地址
        );
		$push->iosNotification([
			'title' => $type.": ".$title,
			'subtitle' => $text,
		], [
            'sound' => 'sound',
            'badge' => '+1',
            'content-available' => TRUE,
            'extras' => $extrasArray
        ]);
		$push->androidNotification($text,[
      'title'=>$type.": ".$title,
			'builder_id'=>'', // 通知栏SDK的样式
			'priority'=>'', // 通知栏的优先等级
			'style'=>'', // 默认为 0，还有 1，2，3 可选，用来指定选择哪种通知栏样式，其他值无效。有三种可选分别为 bigText=1，Inbox=2，bigPicture=3。
			'alert_type'=>'', // 可选范围为 -1～7 ，对应 Notification.DEFAULT_ALL = -1 或者 Notification.DEFAULT_SOUND = 1, Notification.DEFAULT_VIBRATE = 2, Notification.DEFAULT_LIGHTS = 4 的任意 “or” 组合。默认按照 -1 处理。
			'inbox'=>'', // 当 style = 2 时可用， json 的每个 key 对应的 value 会被当作文本条目逐条展示。支持 api 16 以上的 rom
			'intent'=>'', // 使用 intent 里的 url 指定点击通知栏后跳转的目标页面
			'extras'=>$extrasArray,
		]);
		$push->options(array(
              // sendno: 表示推送序号，纯粹用来作为 API 调用标识，
              // API 返回时被原样返回，以方便 API 调用方匹配请求与返回
              // 这里设置为 100 仅作为示例

              // 'sendno' => 100,

              // time_to_live: 表示离线消息保留时长(秒)，
              // 推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。
              // 默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到
              // 这里设置为 1 仅作为示例

              'time_to_live' => 1,

              // apns_production: 表示APNs是否生产环境，
              // True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送生产环境

              'apns_production' => false

              // big_push_duration: 表示定速推送时长(分钟)，又名缓慢推送，把原本尽可能快的推送速度，降低下来，
              // 给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送
              // 这里设置为 1 仅作为示例

              // 'big_push_duration' => 1
        ));

        $push->send();
	}
}
