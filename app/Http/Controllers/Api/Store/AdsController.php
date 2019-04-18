<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

// 加载Model
use App\Model\User;
use App\Model\ads;
use Log;
class AdsController extends Controller
{
	/**
	 * [ads 获取用户的所有地址]
	 * @return [type]
	 */
    public function ads()
    {
    	$input = Input::all();
        // 获取到所有的收货地址
        $ads = ads::where('uuid',$input['uuid'])->where('del','0')->get();
        return $this->result('success','获取数据成功!',$ads);
    }

    /**
     * [adsIn 获取用户的默认地址]
     * @return [type]
     */
    public function adsIn()
    {
    	$input = Input::all();
    	$ads = ads::where('uuid',$input['uuid'])
    		->where('status','1')
    		->where('del','0')
    		->first();
    	if ($ads) {
    		$result = $this->result('success','成功!',$ads);
    	}else{
    		$result = $this->result('fail','未获取到默认地址');
    	}
    	return $result;
    }

    /**
     * [adsShow 显示单条地址信息]
     * @return [json]
     */
    public function adsShow()
    {
    	$input = Input::all();
    	$ads = ads::where('id',$input['adsId'])
    		->where('uuid',$input['uuid'])
    		->where('del','0')
    		->first();
    	if ($ads) {
    		$result = $this->result('success','成功!',$ads);
    	}else{
    		$result = $this->result('fail','未获取到地址信息!');
    	}
    	return $result;
    }

    /**
     * [adsEdit 编辑地址信息]
     * @return [json]
     */
    public function adsEdit()
    {
        $input = Input::all();
        if (ads::where('id',$input['adsId'])
        	->where('uuid',$input['uuid'])
        	->update([
	            "province"=>$input['province'],
	            "city"=>$input['city'],
	            "area"=>$input['area'],
	            "ads"=>$input['ads'],
	            "name"=>$input['name'],
	            "phone"=>$input['phone']
        ])) {
            $result = $this->result('success','编辑收货地址成功!!');
        }else{
            $result = $this->result('success','ERROR!编辑收货地址失败,请稍后再试!');
        }
        return $result;
    }

    /**
     * [adsAdd 添加地址]
     * @return [json]
     */
    public function adsAdd()
    {
    	$input = Input::all();
    	$ads = new ads;
    	$ads->uuid = $input['uuid'];
    	$ads->name = $input['name'];
    	$ads->phone = $input['phone'];
    	$ads->province = $input['province'];
    	$ads->city = $input['city'];
    	$ads->area = $input['area'];
    	$ads->ads = $input['ads'];
    	// 检查用户是否存在默认地址,如果没有,就直接设为默认地址
    	if (ads::where('uuid',$input['uuid'])
    		->where('status','1')
    		->where('del','0')
    		->first()) {
    		$ads->status = 0;
    	}else{
    		$ads->status = 1;
    	}
    	if ($ads->save()) {
    		$result = $this->result('success','添加收货地址成功!',$ads);
    	}else{
    		$result = $this->result('fail','添加收货地址失败!','');
    	}
    	return $result;
    }

    /**
     * [adsDel 删除地址信息]
     * @return [json]
     */
    public function adsDel()
    {
        $input = Input::all();
        // 删除用户地址
        if (ads::where('id',$input['adsId'])
        	->where('uuid',$input['uuid'])
        	->update(['del'=>1])) {
            $result = $this->result('success','删除地址成功!');
        }else{
            $result = $this->result('fail','删除收货地址失败,请稍后再试!');
        }
        return $result;
    }

    /**
     * [adsStatus 接收用户设置默认地址]
     * @return [json]
     */
    public function adsStatus()
    {
    	$input = Input::all();
    	if (ads::where('uuid',$input['uuid'])
        		->update(['status'=>'0'])) {
    		if (ads::where('id',$input['adsId'])
        			->update(['status'=>'1'])) {
    			$result = $this->result('success','设置默认收货地址成功!','');
    		}else{
    			$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    		}
    	}else{
    		$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    	}

		return $result;
    }
}
