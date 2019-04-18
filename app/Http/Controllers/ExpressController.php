<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\goods;
use App\Model\attribute;
use App\Model\ads;
use Log;
class ExpressController extends Controller
{
	// 接收顺丰Access_Token
    public function receiveToken()
    {
    	$input = Inputt::all();
    	Log::warning($input);
    }

    public function receiveResult()
    {
    	$input = Input::all();
    	Log::notice($input);
    }

    /**
     * [getExpressInfo 请求顺丰下单]
     * @param  [str] $orderNum    [订单编号]
     * @param  [int:1] $expressType [发件方式]
     * @param  [str < 30] $mark        [备注字段]
     * @param  [int < 10] $packge      [包裹数量]
     * @return [json]              [成功则返回订单信息]
     */
    public function getExpressInfo()
    {
    	$input = Input::all();
    	// dd($input);
    	$ads = order::where('order.num',$input['orderNum'])
    		->where('order.status','1')
    		->leftJoin('ads','order.ads','=','ads.id')
    		->select('ads.*')
    		->first();
    	// dd($ads);
    	if ($ads) {
    		$accessToken = json_decode($this->getAccessToken())->data->accessToken;
	    	$url = "order/access_token/".$accessToken;
	    	$param['head']['transType'] = "200";
	    	$param['head']['transMessageId'] = "SF".date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,4);
	    	$param['body']['orderId'] = $input['orderNum'];
	    	$param['body']['expressType'] = $input['expressType'];
	    	$param['body']['payMethod'] = '1';
	    	$param['body']['isDoCall'] = '1';
	   		$param['body']['custId'] = '0200039698';
	  		$param['body']['remark'] = $input['mark'];

	  		// 发件方信息
	  		$param['body']['deliveInfo']['company'] = '梦享家俱乐部';
	  		$param['body']['deliveInfo']['contact'] = '梦享家客服';
	  		$param['body']['deliveInfo']['tel'] = '400-967-0003';
	  		$param['body']['deliveInfo']['province'] = '广东省';
	  		$param['body']['deliveInfo']['city'] = '广州市';
	  		$param['body']['deliveInfo']['county'] = '天河区';
	  		$param['body']['deliveInfo']['address'] = '天河区广园东路博汇街6号B301';

	  		// 到件方信息
	  		$param['body']['consigneeInfo']['company'] = '尊敬的梦享家俱乐部会员';
	  		$param['body']['consigneeInfo']['contact'] = $ads->name;
	  		$param['body']['consigneeInfo']['tel'] = $ads->phone;
	  		$param['body']['consigneeInfo']['province'] = $ads->province;
	  		$param['body']['consigneeInfo']['city'] = $ads->city;
	  		$param['body']['consigneeInfo']['county'] = $ads->area;
	  		$param['body']['consigneeInfo']['address'] = $ads->ads;
	  		// $param['body']['consigneeInfo']['mobile'] = $ads->phone;

	  		// 包裹信息
	  		$param['body']['cargoInfo']['parcelQuantity'] = $input['package'];
	  		$param['body']['cargoInfo']['cargo'] = '梦享家会员专享商品';
	  		// dd(json_encode($param));
	  		// 发送请求
	  		// dd($accessToken);
	  		$response = json_decode($this->getSfExpressCurl($url,$param));
	  		// dd($response)
	  		if ($response->status == 'success') {
	  			// 查询订单 需等待顺丰系统处理5min
	  			// 修改状态,禁止再次重复提交订单
	  			if (order::where('num',$input['orderNum'])
	  				->where('status','1')
	  				->where('end','0')
	  				->update(['express_status'=>'9','express_type'=>$input['expressType']])) {
	  				$result = $this->result('success',$response->msg,'');
	  			}else{
	  				$result = $this->result('fail','ERROR!系统错误,下单成功,但未写入系统字段!','');
	  			}
	  		}else{
	  			$result = $this->result('fail',$response->msg,'');
	  		}
    	}else{
    		$result = $this->result('fail','该订单不符合发货要求!','');
    	}
  		return $result;
    }

    // 请求顺丰获取Access_token
    public function getAccessToken()
    {
    	$url = "security/access_token";
    	$param['head']['transType'] = "301";
		$param['head']['transMessageld'] = $num = "SF".date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,4);
		$param['body'] = array(
			""=>""
		);
		$response = json_decode($this->getSfExpressCurl($url,$param,'public'));
		// dd(json_encode($response));
		if ($response->status == 'success') {
			session(['sf_token'=>$response->data->body->accessToken]);
			$result = $this->result('success',$response->data->head->message,$response->data->body);
		}else{
			$result = $this->result('fail',$response->msg,'');
		}
		return $result;
    }

    // 顺丰下单查询接口
    public function orderQuery($orderNum)
    {
		$accessToken = json_decode($this->getAccessToken())->data->accessToken;
    	$url = "order/query/access_token/".$accessToken;
    	$param['head']['transType'] = "203";
		$param['head']['transMessageld'] = $num = "SF".date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,4);
		$param['body']['orderId'] = $orderNum;
		$response = json_decode($this->getSfExpressCurl($url,$param));
		// dd($response);
		if ($response->status == 'success') {
			// 成功后,写入数据库
			if (order::where('num',$orderNum)
				->where('status','1')
				->where('end','0')
				->update([
					'express'=>$response->data->body->mailNo,
					'express_status'=>$response->data->body->filterResult,
					'mark'=>$response->data->body->remark
				])) {
				$result = $this->result('success','货单状态更新完毕',$response->data->body->mailNo);
			}else{
				$result = $this->result('fail','ERROR,货单查询成功,写入数据库失败!','');
			}
		}else{
			$result = $this->result('fail','货单查询失败','');
		}
		return $result;
    }

    // 获取顺丰运单图片
    public function getExpressImg($orderNum)
    {
    	$accessToken = json_decode($this->getAccessToken())->data->accessToken;
    	$url = "waybill/image/access_token/".$accessToken;
    	$param['head']['transType'] = "205";
		$param['head']['transMessageld'] = $num = "SF".date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,4);
		$param['body']['orderId'] = $orderNum;
		$response = json_decode($this->getSfExpressCurl($url,$param));
		if ($response->status == 'success') {
			$imgArray = $response->data->body->images;
			// baseImg 可能是多张图片
			$img = array();
			foreach ($imgArray as $key => $value) {
				//  $base_img是获取到前端传递的src里面的值，也就是我们的数据流文件
				$str = str_replace('data:image/jpg;base64,', '', $value);
				//  设置文件路径和文件前缀名称
				$path = "./images/express/";
				$fileName = $orderNum."_".$key.'.png';
				$path = $path.$fileName;
				//  创建将数据流文件写入我们创建的文件内容中
				$ifp = fopen( $path, "wb" );
				fwrite( $ifp, base64_decode( $str) );
				fclose( $ifp );
				$img[] = $path;
			}
			// dd($img);
			if (order::where('num',$orderNum)
				->where('status','1')
				->where('end','0')
				->where('express_status','!=','0')
				->where('express_type','!=','0')
				->update(['express_img'=>json_encode($img)])) {
				$result = $this->result('success','获取成功!',$img);
			}else{
				$result = $this->result('fail','ERROR!未知的错误!','');
			}
		}else{
			$result = $this->result('fail',$response->msg,'');
		}
		return $result;
    }

    // 获取物流信息
    public function info($orderNum,$phone='')
    {
    	$accessToken = json_decode($this->getAccessToken())->data->accessToken;
    	$url = "route/query/access_token/".$accessToken;
    	$param['head']['transType'] = "501";
		$param['head']['transMessageld'] = $num = "SF".date('YmdHis').substr(strval(rand(1000000000,9999999999)),1,4);
		$param['body']['trackingType'] = 1;
		$param['body']['trackingNumber'] = $orderNum;
		$param['body']['methodType'] = 1;
		// $param['body']['check_phoneNo'] = substr($phone,-4);
		$response = json_decode($this->getSfExpressCurl($url,$param));
		// dd($response);
		if ($response->status == 'success') {
			$result = $this->result('success','获取成功',$response->data->body);
		}else{
			$result = $this->result('fail','暂未查询到快递信息,请稍后再试!','');
		}
		return $result;
    }

    // 快捷发货
    public function swift()
    {
    	$input = Input::all();
    	if (order::where('num',$input['num'])
    		->whereNull('express')
    		->where('express_status','!=','2')
    		->where('status','1')
    		->update([
    			'express'=>$input['express'],
    			'express_status'=>2,
    			'express_type'=>2,
    			'end'=>1
    		])
    	) {
    		$result = $this->result('success','快捷发货成!');
    	}else{
    		$result = $this->result('fail','未知的错误,发货失败!');
    	}
    	return $result;
    }
}









































