<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Log;
// 加载验证码类
require_once __DIR__."/class/Code.class.php";
// 短信发送类
require_once __DIR__."/class/ChuanglanSmsApi.php";
// 引入二维码生成
require_once __DIR__.'/class/phpqrcode/phpqrcode.php';

use App\Model\set;
use App\Model\User;

use JPush\Client as JPush;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // uuid生成方法
    public function uuid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);
        $uuid = chr(123)
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);
        $uuid = substr($uuid, 1);
        $uuid = substr($uuid, 0,-1);
        return $uuid;
    }

    // 私钥Key生成方法
    public function key()
    {
        $charid = strtoupper(sha1(uniqid(mt_rand(), true)));
        $hyphen = chr(45);
        $key = chr(123)
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);
        $key = substr($key, 1);
        $key = substr($key, 0,-1);
        return $key;
    }

    // 检验Key  --------->已经关闭
    public function isKey($uuid,$key)
    {
        if (User::where('user_uuid', $uuid)
            ->where('user_key', $key)
            ->first()) {
            return true;
        }else{
            return true;
        }
    }

    /**
     * 请求返回接口封装
     * @param  [data] 返回的数据
     * @param  [status] 返回的状态
     * @return [json] 返回结果
     */
    public function result($status,$msg = '',$data = '')
    {
        if ($status == 'success') {
            $obj['status'] = 'success';
            $obj['msg'] = $msg;
            $obj['data'] = $data;
        }else{
            $obj['status'] = 'fail';
            $obj['msg'] = $msg;
        }
        return json_encode($obj);
    }

    // Curl 远程访问函数封装
    public function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        return curl_exec($ch);
    }

    // 逆坐标解析 根据经纬度获取实际位置
    public function reverseAds($lng,$lat)
    {
        // 阿里云地图API接口
        //参数解释: 纬度,经度 type 001 (100代表道路，010代表POI，001代表门址，111可以同时显示前三项
        $url = 'http://gc.ditu.aliyun.com/regeocoding?l='.$lat.','.$lng.'&type=010';
        return $url;
    }

    // 逆坐标解析 根据经纬度获取实际位置
    public function locationAds($lng,$lat)
    {
        // 腾讯地图API接口
        //参数解释: http://lbs.qq.com/webservice_v1/guide-gcoder.html
        $url = 'http://apis.map.qq.com/ws/geocoder/v1/?location='.$lat.','.$lng.'&key='.env('QQ_LBS_SERVER_KEY').'';
        return $url;
    }

    // 获取图片验证码
    public function makeCode()
    {
        $code = new \Code;
        // dd(session('code'));
        return $code->make();
    }

    // 获取文字验证码
    public function getCode()
    {
        $code = new \Code;
        // dd(session('code'));
        return $code->get();
    }


    // 二维码生成
    public function makeQrcode($url,$logo = '')
    {
        $value = $url;// 二维码连接地址
        $logo = 'http://jaclub.shareshenghuo.com/images/qr_logo.jpg';
        $errorCorrectionLevel = 'H'; // 容错级别
        $matrixPointSize = 12; // 生成图片大小
        //生成二维码图片
        $QR = "qrcode/qrcode-".rand(1000000,9999999).time().".png";
        $QRcode = new \QRcode;
        $QRcode->png($value, $QR, $errorCorrectionLevel, $matrixPointSize, 3);
        if (!empty($QR)) {
            $qrcode = $QR;
            if ($logo) {
                $QR = imagecreatefromstring(file_get_contents($QR)); 
                $logo = imagecreatefromstring(file_get_contents($logo)); 
                $QR_width = imagesx($QR);//二维码图片宽度 
                $QR_height = imagesy($QR);//二维码图片高度 
                $logo_width = imagesx($logo);//logo图片宽度 
                $logo_height = imagesy($logo);//logo图片高度 
                $logo_qr_width = $QR_width / 5; 
                $scale = $logo_width/$logo_qr_width; 
                $logo_qr_height = $logo_height/$scale; 
                $from_width = ($QR_width - $logo_qr_width) / 2; 
                //重新组合图片并调整大小 
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
                $logo_qr_height, $logo_width, $logo_height);
                $qrcode = "qrcode/qrcode-".rand(1000000,9999999).time().".png";
                imagepng($QR, $qrcode);
                return $qrcode;
            }else{
                return $qrcode;
            }
            return $QR;
        }else{
            $QR = '';
            return $QR;
        }
    }


    /**
     * 获取毫秒级别的时间戳
     */
    public function getTime()
    {
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }


    /**
     * 商品分类处理函数
     *     @param     items         需要分类的二维数组
     *     @param     $id         主键（唯一ID）
     *     @param     $attr_pid     关联主键的PID
     *  @son 可以自定义往里面插入就行
     */
    public function handleAttr($items,$id='id',$attr_pid='attr_pid',$son = 'children')
    {
        $tree = array(); //格式化的树
        $tmpMap = array();  //临时扁平数据
        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }
        foreach ($items as $item) {
            if (isset($tmpMap[$item[$attr_pid]])) {
                $tmpMap[$item[$attr_pid]]['attr'][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        unset($tmpMap);
        return $tree;
    }

    /**
     * [getSfExpressCurl 顺丰API请求方法 By Leo BBM]
     * @param  [str] $url [请求地址]
     * @param  [array] $body [参照文档]
     * @return [json]       [返回结果]
     */
    public function getSfExpressCurl($resource,$param,$type = 'rest')
    {
        $version = 'v1.0';
        // dd($token);
        $url = env('SF_URL')."/".$type."/".$version."/".$resource."/sf_appid/".env('SF_APP_ID')."/sf_appkey/".env('SF_APP_KEY');
        // dd($url);
        $ch = curl_init();
        $header[] = "Content-type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        // dd($ch);
        $obj = json_decode(curl_exec($ch));
        // var_dump($obj);

        if ($obj) {
            switch ($obj->head->code) {
                case 'EX_CODE_OPENAPI_0100':
                $result = $this->result('fail',$obj->head->message,'');
                    break;
                case 'EX_CODE_OPENAPI_0101':
                $result = $this->result('fail','APPID 不存在','');
                    break;
                case 'EX_CODE_OPENAPI_0102':
                $result = $this->result('fail','APPKEY 不存在','');
                    break;
                case 'EX_CODE_OPENAPI_0103':
                $result = $this->result('fail','访问令牌不存在','');
                    break;
                case 'EX_CODE_OPENAPI_0104':
                $result = $this->result('fail','更新令牌不存在','');
                    break;
                case 'EX_CODE_OPENAPI_0105':
                $result = $this->result('fail','访问令牌过期','');
                    break;
                case 'EX_CODE_OPENAPI_0106':
                $result = $this->result('fail','更新令牌过期','');
                    break;
                case 'EX_CODE_OPENAPI_0200':
                $result = $this->result('success','操作成功',$obj);
                    break;
                case 'EX_CODE_OPENAPI_0400':
                $result = $this->result('fail','操作失败','');
                    break;
                case 'EX_CODE_OPENAPI_0420':
                $result = $this->result('fail','不存在该订单号对应的订单信息','');
                    break;
                case 'EX_CODE_OPENAPI_0422':
                $result = $this->result('fail','交易类型不正确','');
                    break;
                case 'EX_CODE_OPENAPI_0427':
                $result = $this->result('fail','月结卡号不能为空','');
                    break;
                case 'EX_CODE_OPENAPI_0500':
                $result = $this->result('fail',$obj->head->message,'');
                    break;
                case 'EX_CODE_OPENAPI_00212':
                $result = $this->result('fail','无效帐户状态','');
                    break;
                case 'EX_CODE_OPENAPI_0300':
                $result = $this->result('fail','验证输入参数异常','');
                    break;
                case 'EX_CODE_OPENAPI_0403':
                $result = $this->result('fail','获取用户权限失败','');
                    break;
                case 'EX_CODE_OPENAPI_0404':
                $result = $this->result('fail','重复下单','');
                    break;
                case 'EX_CODE_OPENAPI_0405':
                $result = $this->result('fail','查询非客户所有订单','');
                    break;
                case 'EX_CODE_OPENAPI_0406':
                $result = $this->result('fail','生产电子运单图片失败','');
                    break;
                case 'EX_CODE_OPENAPI_0407':
                $result = $this->result('fail','未有数据生成电子运单','');
                    break;
                case 'EX_CODE_OPENAPI_0425':
                $result = $this->result('fail','订单信息有误','');
                    break;
                case 'EX_CODE_OPENAPI_0426':
                $result = $this->result('fail','调用地址筛单异常','');
                    break;
                case 'EX_CODE_OPENAPI_0444':
                $result = $this->result('fail',$obj->head->message,'');
                    break;
                case 'EX_CODE_OPENAPI_0445':
                $result = $this->result('fail','该订单号非本系统的订单或者运单号
    不存在','');
                    break;
                case 'EX_CODE_OPENAPI_0446':
                $result = $this->result('fail','该订单号尚未申请路由增量接口','');
                    break;
                default:
                $result = $this->result('fail','未知的错误','');
                    break;
            }
        }else{
            $result = $this->result('fail','ERROR!顺丰未响应请求!','');
        }
        return $result;
    }

    // 获取set的信息
    public function set($value)
    {
        $set = set::find(1)->$value;
        return $set;
    }

    // 发送短信
    public function sendSms($phone)
    {
        // dd(1);
        $clapi  = new \ChuanglanSmsApi();
        $code = mt_rand(1000,9999);
        //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
        $result = $clapi->sendSMS($phone,'【梦享家】您好，您的验证码为:'.$code.'。 如有任何疑问，请拨打客服热线：400-967-0003');

        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            // dd($output);
            if(isset($output['code']) && $output['code']=='0'){
                session(['smsCode'=>$code]);
                return true;
            }else{
                return false;
            }
        }else{
                return false;
        }
    }

    // 发送通知短信
    public function sendNotice($phone,$type,$param = '')
    {
        $clapi  = new \ChuanglanSmsApi();
        switch ($type) {
            case '1':
                $msg = '账户信息变动通知: 尊敬的梦享家，您的融通四海账户有了新的变动，请登录梦享家服务平台进行查看。如有任何疑问，请拨打客服热线：400-967-0003';
                break;
            case '2':
                $msg = '尊敬的梦享家 ：您好，您提交的提现订单'.$param.'已经提现成功，可到银行账户查询到账情况。如有任何疑问，请拨打客服热线：400 967 0003';
                break;
        }
        $result = $clapi->sendSMS($phone,$msg);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            // dd($output);
            if(isset($output['code']) && $output['code']=='0'){
                return true;
            }else{
                return false;
            }
        }else{
                return false;
        }
    }

    public function getBankCardLogo($bankCardNo)
    {
        $url = 'https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo='.$bankCardNo.'&cardBinCheck=true';
        $bankCardInfo = json_decode(file_get_contents($url));
        if ($bankCardInfo->validated) {
            $logo = 'https://apimg.alipay.com/combo.png?d=cashier&t='.$bankCardInfo->bank.'';
            return $logo;
        }else{
            return false;
        }
    }

    public function getBankCardInfo($bankCardNo)
    {
        $host = "https://api17.aliyun.venuscn.com";
        $path = "/bank-card/query";
        $method = "GET";
        $appcode = "4635460834644dec83b7ad1e47c08b2b";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "number=".$bankCardNo;
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $result = json_decode(curl_exec($curl));
        if ($result->ret == 200) {
            return $result->data;
        }else{
            return $result->msg;
        }
    }

    public function sendVarSms($param,$type)
    {
        $clapi  = new \ChuanglanSmsApi();
        switch ($type) {
            case 'isInfo':
                $msg = '账户信息变动通知:尊敬的梦享家 {$var}，恭喜您完成了实名信息认证，如有任何疑问，请拨打客服热线：400-967-0003';
                break;
            case 'addJoin';
                $msg = '账户信息变动通知: 尊敬梦享家 {$var}，恭喜您已成为梦享家的加盟用户，为了保障您的个人信息安全,请尽快修改初加盟商始密码(123456)';
                break;
            case 'express';
                $msg = '账户订单信息通知:尊敬得梦享家,您在梦享家商城的订单,订单编号:{$var}已经发货,如需了解详情,请登录梦享家进行查看,非常感谢您对梦享家的支持，如有任何疑问，请拨打客服热线：400-967-0003';
                break;
            case 'shopBalance';
                $msg = '账户信息变动通知: 尊敬的梦享家，您在商城下单成功，基本账户剩余{$var}积分,赠送积分剩余{$var}积分。如有任何疑问，请拨打客服热线：400-967-0003';
                break;
            case 'party';
                $msg = '账户信息变动通知: 尊敬的梦享家，您的股权账户发生变动，账户变动金额:{$var}元,如有任何疑问，请拨打客服热线：400-967-0003';
                break;
        }
        // print_r($param);echo "<pre>";
        // print_r($msg);
        $result = json_decode($clapi->sendVariableSMS($msg,$param));
        if ($result->code == 0) {
            return true;
        }else{
            return flase;
        }

    }

    // Excel 公共导出方法
    public function export_csv($filename,$data)   
    {   
        header("Content-type:text/csv");   
        header("Content-Disposition:attachment;filename=".$filename);   
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
        header('Expires:0');   
        header('Pragma:public');   
        echo $data;   
    }


    public function uploadImg($str,$path)
    {
        $data = explode(",", $str);
        $type = strtolower(explode(";", explode("/",$data['0'])['1'])['0']);
        $picType = array("jpg","jpeg","png","gif","bmp");
        if (in_array($type, $picType)) {
           $fileName = $path."/".date('YmdHis').mt_rand(100000,999999).'.'.$type;
           if (file_put_contents($fileName, base64_decode($data['1']))) {
                $obj['status'] = 'success';
                $obj['msg'] = '文件上传成功';
                $obj['data']['url'] = $fileName;
           }else{
                $obj['status'] = 'fail';
                $obj['msg'] = '服务器内部错误，无法保存图片！';
           }
        }else{
            $obj['status'] = 'fail';
            $obj['msg'] = '图片类型不正确，请重新选择图片上传！';
        }
        return json_encode($obj);
    }

    public function isPhone($phone) {
      /**
       * 判断字符串是否符合手机号码格式
       * 移动号段: 134,135,136,137,138,139,147,150,151,152,157,158,159,170,178,182,183,184,187,188
       * 联通号段: 130,131,132,145,155,156,170,171,175,176,185,186
       * 电信号段: 133,149,153,170,173,177,180,181,189
       * @param str
       * @return 待检测的字符串
       */

        if(preg_match("/^((13[0-9])|(14[5,7,9])|(15[^4])|(18[0-9])|(17[0,1,3,5,6,7,8]))\\d{8}$/",$phone)) {
            //验证通过
            return true;
        }else{
            //手机号码格式不对
            return false;
        }
    }

    // 发送短信
    public function smssend($phone)
    {
        // dd(1);
        $clapi  = new \ChuanglanSmsApi();
        $code = mt_rand(1000,9999);
        //设置您要发送的内容：其中“【】”中括号为运营商签名符号，多签名内容前置添加提交
        $result = $clapi->sendSMS($phone,'【梦享家】您好，您的验证码为:'.$code.'。 如有任何疑问，请拨打客服热线：400-967-0003');

        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code']) && $output['code']=='0'){
                session(['smsCode'=>$code]);
                $data['status'] = 'success';
                $data['msg'] = '短信发送成功!';
            }else{
                $data['status'] = 'fail';
                $data['msg'] = '短信发送失败,错误码:['.$output['code'].'],错误信息:'.$output['errorMsg'];
            }
        }else{
                $data['status'] = 'fail';
                $data['msg'] = '系统繁忙,短信发送失败';
        }
        return $data;
    }

    public function uuidStr($uuid)
    {
        return str_replace("-","",$uuid);
    }

    // 极光推送
    public function push($array=array(),$type="1",$title="",$text="",$jump="app",$url="",$img="")
    {
        $client = new JPush(env('JPUSH_KEY'), env('JPUSH_SECRET'), storage_path('logs/push.log'));
        $push = $client->push();
         // 配置推送的平台
        $push->setPlatform(['ios','android']); // OR ios/android
        // $push->addAllAudience('all'); // all为广播,tag标签,alias别名
        $push->setNotificationAlert('我是标题');
        $push->addAlias($array);
        // 判断消息类型
        switch ($type) {
            case '1':
                $type = '系统通知';
                break;
            case '2':
                $type = '支付消息';
                break;
            case '3':
                $type = '社区消息';
                break;
            default:
                $type = 'JaClub';
                break;
        }
        // 构造扩展消息
        $extrasArray = array(
            'tit' => $title, // 消息标题
            'txt' => $text,  // 消息内容
            'type' => $type, // 消息类型
            'img' => $img,    // 消息附加图片
            'jump' =>$jump, // 跳转方法 (web,app)
            'url' => $url, // 跳转地址
            'send_time'=>date('Y-m-d H:i:s')
        );
        $push->iosNotification([
            'title' => $type.": ".$title,
            'subtitle'=> $text,
            'alert' => '12323333',
            'sound'=>'',
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

        try{
            $push->send();
        }catch (\Exception $e) {
        }
    }
}




































