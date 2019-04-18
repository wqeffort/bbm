<?php

namespace App\Http\Controllers\Api\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\ja_mark;
class JachatController extends Controller
{
	public function getUrl($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$data);
        $output = curl_exec($curl);
        $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        $result['data'] = json_decode($output);
        return $result;
	}


	public function postUrl($url,$data){
	        $data  = json_encode($data);
	        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	        curl_close($curl);
	        $result['data'] = json_decode($output);
	        return $result;
	}


	public function putUrl($url,$data){
	    $data = json_encode($data);
	    $ch = curl_init(); //初始化CURL句柄
	    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
	    curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
	    $output = curl_exec($ch);
	    $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    $result['data'] = json_decode($output);
	    return $result;
	}

	public function delUrl($url,$data){
	    $data  = json_encode($data);
	    $ch = curl_init();
	    curl_setopt ($ch,CURLOPT_URL,$put_url);
	    curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	    $output = curl_exec($ch);
	    $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    $result['data'] = json_decode($output);
	    return $result;
	}

	public function patchUrl($url,$data){
	    $data  = json_encode($data);
	    $ch = curl_init();
	    curl_setopt ($ch,CURLOPT_URL,$url);
	    curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);     //20170611修改接口，用/id的方式传递，直接写在url中了
	    $output = curl_exec($ch);
	    $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    $result['data'] = json_decode($output);
	    return $result;
	}

    // 获取Token方法
    public function getToken()
    {
    	$data = array();
    	$data['grant_type'] = 'client_credentials';
    	$data['client_id'] = getenv('APP_CHAT_ID');
    	$data['client_secret'] = getenv('APP_CHAT_SECRET');
    	$url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/token";
    	$result = $this->postUrl($url,$data);
    	if ($result['code'] == 200) {
    		session(['chat_token'=>$result['data']->access_token]);
    		return $result['data']->access_token;
    	}else{
    		return false;
    	}
    }

    // public function uuidStr($uuid)
    // {
    // 	return str_replace("-","",$uuid);
    // }

    /**
     * [chat/user/add 注册IM]
     * @param uuid
     * @param key
     * @return josn
     */
    public function addUser()
    {
    	$input = Input::all();
    	$data = array();
    	$data['username'] = strtolower($this->uuidStr($input['uuid']));
    	$data['password'] = "123456";
        $chat['chatId'] = $this->uuidStr($input['uuid']);
        if ($this->isKey($input['uuid'],$input['key'])) {
            // 获取用户昵称
            $user = User::where('user_uuid',$input['uuid'])->first();
            if ($user) {
                if (empty($user->user_nickname)) {
                    $data['nickname'] = $user->user_phone;
                }else{
                    $data['nickname'] = $user->user_nickname;
                }
            }
            if (session('chat_token')) {
                $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users";
                $respond = $this->postUrl($url,$data);
                if ($respond['code'] == 200) {
                    $result = $this->result('success','注册成功!',$chat);
                }else{
                    if ($respond['data']->error == 'duplicate_unique_property_exists') {
                        // dd($respond);
                        $result = $this->result('success','获取信息成功!',$chat);
                    }else{
                        $result = $this->result('fail',$respond['data']->error_description);
                    }
                }
            }else{
                $token = $this->getToken();
                if ($token) {
                    session(['chat_token'=>$token]);
                    $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users";
                    $respond = $this->postUrl($url,$data);
                    if ($respond['code'] == 200) {
                        $result = $this->result('success','注册成功!',$chat);
                    }else{
                        if ($respond['data']->error == 'duplicate_unique_property_exists') {
                            $result = $this->result('success','获取信息成功!',$chat);
                        }else{
                            $result = $this->result('fail',$respond['data']->error_description);
                        }
                    }
                }else{
                    $result = $this->result('fail','获取token失败!');
                }
            }
        }else{
            $result = $this->result('fail','状态过期,请重新登录!');
        }
    	return $result;
    }


    /**
     * [selectUserUuid 根据chatID和手机号码查询用户的uuid]
     * @param uuid
     * @param key
     * @param token
     * @param keyword  查询的字符串
     * @return [json] [成功则返回data]
     */
    public function selectUserUuid()
    {
        $input = Input::all();
        if ($this->isKey($input['uuid'],$input['key'])) {
            if ($this->isPhone($input['keyword'])) {
                $user = User::where('user_phone',$input['keyword'])
                    ->whereNotNull('user_key')
                    ->select('user_pic','user_uuid','user_nickname','user_id')
                    ->first();
                if ($user) {
                    $user->user_uuid = $this->uuidStr($user->user_uuid);
                    $result = $this->result('success','查询成功!',$user);
                }else{
                    $result = $this->result('fail','未查询到用户!');
                }
            }else{
                $user = User::where('user_id',$input['keyword'])
                    ->whereNotNull('user_key')
                    ->select('user_pic','user_uuid','user_nickname','user_id','user_rank','user_sex')
                    ->first();
                if ($user) {
                    $user->user_uuid = $this->uuidStr($user->user_uuid);
                    $result = $this->result('success','查询成功!',$user);
                }else{
                    $result = $this->result('fail','未查询到用户!');
                }
            }
        }else{
            $result = $this->result('fail','登录状态过期,验证失败,请重新登录!');
        }
        return $result;
    }

    /**
     * [friendAll 获取用户所有好友]
     * @param token
     * @param uuid
     * @param key
     * @return [json]
     */
    public function friendAll()
    {
        $input = Input::all();
        if ($this->isKey($input['uuid'],$input['key'])) {
            $chatId = $this->uuidStr($input['uuid']);
            if (session('chat_token')) {
                $headers = array();
                $headers[]  =  "Accept:application/json";
                $headers[]  =  "Authorization: Bearer ".session('chat_token');
                $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users/".$chatId."/contacts/users";
                $respond = $this->getUrl($url,$headers);
                if ($respond['code'] == 200) {
                    $data = array();
                    foreach ($respond['data']->data as $key => $value) {
                        $uuid = substr_replace(substr_replace(substr_replace(substr_replace(strtoupper($value),'-',8,0),'-',13,0),'-',18,0),'-',23,0);
                        $user = User::where('user_uuid',$uuid)->first();
                        if ($user) {
                            $info['uuid'] = $this->uuidStr($user->user_uuid);
                            $info['img'] = $user->user_pic;
                            $info['user_nickname'] = $user->user_nickname;
                            $mark = ja_mark::where('uuid',$input['uuid'])
                                ->where('to',$user->user_uuid)
                                ->where('status','1')
                                ->first();
                            if ($mark) {
                                $info['mark'] = $mark->mark;
                            }else{
                                $info['mark'] = '';
                            }
                            $data[] = $info;
                        }
                    }
                    $result = $this->result('success','获取好友列表成功!',$data);
                }else{
                    $result = $this->result('fail','ERROR!接口请求失败!');
                }
            }else{
                $token = $this->getToken();
                $headers = array();
                $headers[]  =  "Accept:application/json";
                $headers[]  =  "Authorization: Bearer ".session('chat_token');
                $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users/".$chatId."/contacts/users";
                $respond = $this->getUrl($url,$headers);
                if ($respond['code'] == 200) {
                    $data = array();
                    foreach ($respond['data']->data as $key => $value) {
                        $uuid = substr_replace(substr_replace(substr_replace(substr_replace(strtoupper($value),'-',8,0),'-',13,0),'-',18,0),'-',23,0);
                        $user = User::where('user_uuid',$uuid)->first();
                        if ($user) {
                            $info['uuid'] = $this->uuidStr($user->user_uuid);
                            $info['img'] = $user->user_pic;
                            $info['user_nickname'] = $user->user_nickname;
                            $mark = ja_mark::where('uuid',$input['uuid'])
                                ->where('to',$user->user_uuid)
                                ->where('status','1')
                                ->first();
                            if ($mark) {
                                $info['mark'] = $mark->mark;
                            }else{
                                $info['mark'] = '';
                            }
                            $data[] = $info;
                        }
                    }
                    $result = $this->result('success','获取好友列表成功!',$data);
                }else{
                    $result = $this->result('fail','ERROR!接口请求失败!');
                }
            }
        }else{
            $result = $this->result('fail','登录状态过期,验证失败,请重新登录!');
        }
        // dd($data);
        return $result;
    }

    /**
     * [friendAll 获取用户所有好友--提醒列表]
     * @param token
     * @param uuid
     * @return [json]
     */
    public function friendList()
    {
        $input = Input::all();
        $chatId = $this->uuidStr($input['uuid']);
        if (session('chat_token')) {
            $headers = array();
            $headers[]  =  "Accept:application/json";
            $headers[]  =  "Authorization: Bearer ".session('chat_token');
            $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users/".$chatId."/contacts/users";
            $respond = $this->getUrl($url,$headers);
            if ($respond['code'] == 200) {
                $data = array();
                foreach ($respond['data']->data as $key => $value) {
                    $uuid = substr_replace(substr_replace(substr_replace(substr_replace(strtoupper($value),'-',8,0),'-',13,0),'-',18,0),'-',23,0);
                    $user = User::where('user_uuid',$uuid)->first();
                    if ($user) {
                        $info['uuid'] = $user->user_uuid;
                        $info['img'] = $user->user_pic;
                        $info['user_nickname'] = $user->user_nickname;
                        $mark = ja_mark::where('uuid',$input['uuid'])
                            ->where('to',$user->user_uuid)
                            ->where('status','1')
                            ->first();
                        if ($mark) {
                            $info['mark'] = $mark->mark;
                        }else{
                            $info['mark'] = '';
                        }
                        $data[] = $info;
                    }
                }
                $result = $this->result('success','获取好友列表成功!',$data);
            }else{
                $result = $this->result('fail','ERROR!接口请求失败!');
            }
        }else{
            $token = $this->getToken();
            $headers = array();
            $headers[]  =  "Accept:application/json";
            $headers[]  =  "Authorization: Bearer ".session('chat_token');
            $url = getenv('APP_CHAT_URL')."/".getenv('APP_CHAT_KEY')."/".getenv('APP_CHAT_NAME')."/users/".$chatId."/contacts/users";
            $respond = $this->getUrl($url,$headers);
            if ($respond['code'] == 200) {
                $data = array();
                foreach ($respond['data']->data as $key => $value) {
                    $uuid = substr_replace(substr_replace(substr_replace(substr_replace(strtoupper($value),'-',8,0),'-',13,0),'-',18,0),'-',23,0);
                    $user = User::where('user_uuid',$uuid)->first();
                    if ($user) {
                        $info['uuid'] = $user->user_uuid;
                        $info['img'] = $user->user_pic;
                        $info['user_nickname'] = $user->user_nickname;
                        $mark = ja_mark::where('uuid',$input['uuid'])
                            ->where('to',$user->user_uuid)
                            ->where('status','1')
                            ->first();
                        if ($mark) {
                            $info['mark'] = $mark->mark;
                        }else{
                            $info['mark'] = '';
                        }
                        $data[] = $info;
                    }
                }
                $result = $this->result('success','获取好友列表成功!',$data);
            }else{
                $result = $this->result('fail','ERROR!接口请求失败!');
            }
        }

        return $result;
    }

    public function mark()
    {
        // 检查当前昵称
        $input = Input::all();
        if (empty($input['mark'])) {
            $input['mark'] = User::where('user_uuid',$input['to'])->first()->user_nickname;
        }
        if (ja_mark::where('uuid',$input['uuid'])
            ->where('to',$input['to'])
            ->where('status','1')
            ->first()) {
            if (ja_mark::where('uuid',$input['uuid'])
                ->where('to',$input['to'])
                ->update([
                    "mark"=>$input['mark']
                ])) {
                $result = $this->result('success','修改备注成功!');
            }else{
                $result = $this->result('fail','修改备注失败!');
            }
        }else{
            $mark = new ja_mark;
            $mark->uuid = $input['uuid'];
            $mark->to = $input['to'];
            $mark->status = 1;
            $mark->mark = $input['mark'];
            if ($mark->save()) {
                $result = $this->result('success','修改备注成功!');
            }else{
                $result = $this->result('fail','修改备注失败!');
            }
        }
        return $result;
    }
}









































