<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Log;

// 加载Model
use App\Model\User;
class CommonController extends Controller
{
    // 高德地图逆地址解析
    public function reLocation()
    {
    	$input = Input::all();
    	$radius = 1000; // 设置搜索半径
    	$extensions = 'base'; // 返回POI信息.base为不返回
    	$url = 'https://restapi.amap.com/v3/geocode/regeo?output=json&location='.$input['lng'].','.$input['lat'].'&key='.env('GEO_SERVICE_KEY').'&radius='.$radius.'&extensions='.$extensions;
    	$response = json_decode($this->curlGet($url));
    	$data['adcode'] = $response->regeocode->addressComponent->adcode;
    	if ($response->status == 1 && $response->infocode == 10000) {
    		if ($response->regeocode->addressComponent->city) {
    			$data['city'] = $response->regeocode->addressComponent->city;
    			$result = $this->result('success','获取成功!',$data);
    		}else{
    			$data['city'] = $response->regeocode->addressComponent->province;
    			$result = $this->result('success','获取成功!',$data);
    		}
    	}else{
    		$result = $this->result('fail','ERROR!请求出错了,错误码:'.$response->infocode);
    	}
    	return $result;
    }

    // IP地址解析
    public function ipLocation()
    {
    	$input = Input::all();
        $ip = $_SERVER["REMOTE_ADDR"];
    	$url = 'https://restapi.amap.com/v3/ip?ip='.$ip.'&output=json&key='.env('GEO_SERVICE_KEY');
    	$response = json_decode($this->curlGet($url));
    	if ($response->status == 1 && $response->infocode == 10000) {
    		$data['adcode'] = $response->adcode;
    		if ($response->city) {
    			$data['city'] = $response->city;
    			$result = $this->result('success','获取成功!',$data);
    		}else{
    			$data['city'] = $response->province;
    			$result = $this->result('success','获取成功!',$data);
    		}
    	}else{
    		$result = $this->result('fail','ERROR!请求出错了,错误码:'.$response->infocode);
    	}
    	return $result;
    }


    // 高德天气
    public function weather()
    {
    	$input = Input::all();
    	$data = array();
    	if ($input['lng'] && $input['lat']) {
    		$radius = 1000; // 设置搜索半径
	    	$extensions = 'base'; // 返回POI信息.base为不返回
	    	$url = 'https://restapi.amap.com/v3/geocode/regeo?output=json&location='.$input['lng'].','.$input['lat'].'&key='.env('GEO_SERVICE_KEY').'&radius='.$radius.'&extensions='.$extensions;
	    	$response = json_decode($this->curlGet($url));
	    	$data['adcode'] = $response->regeocode->addressComponent->adcode;
	    	if ($response->status == 1 && $response->infocode == 10000) {
	    		if ($response->regeocode->addressComponent->city) {
	    			$data['city'] = $response->regeocode->addressComponent->city;
	    		}else{
	    			$data['city'] = $response->regeocode->addressComponent->province;
	    		}
	    	}else{
	    		return $this->result('fail','ERROR!请求出错了,错误码:'.$response->infocode);
	    	}
    	}else{
            $ip = $_SERVER["REMOTE_ADDR"];
    		$url = 'https://restapi.amap.com/v3/ip?ip='.$ip.'&output=json&key='.env('GEO_SERVICE_KEY');
	    	$response = json_decode($this->curlGet($url));
	    	if ($response->status == 1 && $response->infocode == 10000) {
	    		$data['adcode'] = $response->adcode;
	    		if ($response->city) {
	    			$data['city'] = $response->city;
	    		}else{
	    			$data['city'] = $response->province;
	    		}
	    	}else{
	    		return $this->result('fail','ERROR!请求出错了,错误码:'.$response->infocode);
	    	}
    	}
    	if ($data['city'] && $data['adcode']) {
    		// 请求获取到天气
    		$extensions = 'base';
    		$url = 'https://restapi.amap.com/v3/weather/weatherInfo?extensions='.$extensions.'&city='.$data['adcode'].'&key='.env('GEO_SERVICE_KEY');
    		$response = json_decode($this->curlGet($url));
    		if ($response->status == 1 && $response->infocode == 10000) {
    			$data['weather'] = $response->lives['0'];
                // dd($data['weather']);
                switch ($data['weather']->weather) {
                    case "晴" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "少云" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "晴间多云" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "多云" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";

                    break;
                    case "阴" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "有风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "平静" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "微风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "和风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "清风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "强风/劲风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "疾风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "大风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "烈风" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "风暴" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "狂爆风" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "飓风" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "热带风暴" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "阵雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "雷阵雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "雷阵雨并伴有冰雹" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "小雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "中雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "大雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "大暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "特大暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "强阵雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "强雷阵雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "极端降雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "毛毛雨/细雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "小雨-中雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "中雨-大雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "大雨-暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "暴雨-大暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "大暴雨-特大暴雨" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "雨雪天气" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "雨夹雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "阵雨夹雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "冻雨" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "阵雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "小雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "中雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "大雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "暴雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "小雪-中雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "中雪-大雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "大雪-暴雪" :
                        $data['weather']->weather = "雪";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-雪.png";
                    break;
                    case "浮尘" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "扬沙" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "沙尘暴" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "强沙尘暴" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "龙卷风" :
                        $data['weather']->weather = "雨";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-yu.png";
                    break;
                    case "雾" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "浓雾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "强浓雾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "轻雾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "大雾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "特强浓雾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "霾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "中度霾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "重度霾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "严重霾" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    case "热" :
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                    break;
                    case "冷" :
                        $data['weather']->weather = "云";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-duoyun.png";
                    break;
                    default:
                        $data['weather']->weather = "晴";
                        $data['weather']->img = "http://".env('HTTP_HOST')."/images/weather-qing.png";
                        break;
                }

    			$result = $this->result('success','获取天气成功',$data);
    		}else{
    			$result = $this->result('fail','ERROR!请求出错了,错误码:'.$response->infocode);
    		}
    	}else{
    		$restul = $this->result('fail','未获取到地址编码或者地址名称');
    	}
    	return $result;
    }

}
