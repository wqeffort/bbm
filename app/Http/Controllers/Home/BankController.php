<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Response;

// 加载Model
use App\Model\User;
use App\Model\goods;
use App\Model\attribute;
use App\Model\category;
use App\Model\brand;
use App\Model\bank;
use App\Model\car;
use App\Model\ads;
class BankController extends Controller
{
    public function list()
    {
    	// 获取用户所有的银行卡
    	$app = app('wechat.official_account');
    	$data = bank::where('uuid',session('user')->user_uuid)->get();
    	return view('home.bank-list',compact('app','data'));
    }

    public function create()
    {
    	$app = app('wechat.official_account');
    	// 添加银行卡
    	return view('home.bank-add',compact('app'));
    }

    public function isBankCard()
    {
    	$input = Input::all();
    	if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $input['name']) === 1) {
    		@$logo = $this->getBankCardLogo($input['card']);
    		@$info = $this->getBankCardInfo($input['card']);
    		@$bank['logo'] = $logo;
    		@$bank['name'] = $info->bankname;
	    	if ($bank['logo'] || $bank['name']) {
	    		$result = $this->result('success','成功!',$bank);
	    	}else{
	    		$result = $this->result('fail','识别银行卡失败,请检查卡号或者更换银行卡!');
	    	}
    	}else{
    		$result = $this->result('fail','持卡人姓名非法,请输入纯中文字符!');
    	}
    	return $result;
   	}

   	public function add()
   	{
   		$input = Input::all();
   		$bank = new bank;
   		$bank->bank_card = $input['card'];
      $bank->name = $input['name'];
   		$bank->bank_location = $input['bankName'];
   		if ($logo = $this->getBankCardLogo($input['card'])) {
   			$bank->bank_logo = $logo;
   		}
   		if ($bankInfo = $this->getBankCardInfo($input['card'])) {
   			$bank->bank_name = $bankInfo->bankname;
   			$bank->bank_code = $bankInfo->bankid;
   		}
   		$bank->uuid = session('user')->user_uuid;
   		// 检查是否有默认的卡号,没有的话设置为默认
   		if (bank::where('uuid',session('user')->user_uuid)
   			->where('status','1')->first()) {
   			$bank->status = 0;
   		}else{
   			$bank->status = 1;
   		}
   		if (bank::where('uuid',session('user')->user_uuid)
   			->where('bank_card',$input['card'])->first()) {
   			$result = $this->result('fail','该银行已经添加,请换一张重试!');
   		}else{
   			if ($bank->save()) {
	   			$result = $this->result('success','添加银行卡成功!');
	   		}else{
	   			$result = $this->result('fail', '添加银行卡失败,请稍后再试,或者联系客服!');
	   		}
   		}
   		return $result;
   	}

   	public function status()
   	{
   		$input = Input::all();
   		if (bank::where('uuid',session('user')->user_uuid)
   			->update(['status'=>0])) {
   			bank::where('uuid',session('user')->user_uuid)
   				->where('id',$input['id'])
   				->update(['status'=>1]);
   			$result = $this->result('success','设置成功!');
   		}else{
   			$result = $this->result('fail','设置默认银行卡失败,请稍后再试!');
   		}
   		return $result;
   	}

    public function createSpring()
    {
        $app = app('wechat.official_account');
        $name = User::where('user_uuid',session('join')->uuid)
            ->first()
            ->user_name;
        // 添加银行卡
        return view('home.bank-spring-add',compact('app','name'));
    }

    public function addSpring()
    {
        $input = Input::all();
        $bank = new bank;
        $bank->bank_card = $input['card'];
        $bank->name = $input['name'];
        $bank->bank_location = $input['bankName'];
        if ($logo = $this->getBankCardLogo($input['card'])) {
            $bank->bank_logo = $logo;
        }
        if ($bankInfo = $this->getBankCardInfo($input['card'])) {
            $bank->bank_name = $bankInfo->bankname;
            $bank->bank_code = $bankInfo->bankid;
        }
        $bank->uuid = session('join')->uuid;
        // 检查是否有默认的卡号,没有的话设置为默认
        if (bank::where('uuid',session('join')->uuid)
            ->where('status','1')->first()) {
            $bank->status = 0;
        }else{
            $bank->status = 1;
        }
        if (bank::where('uuid',session('join')->uuid)
            ->where('bank_card',$input['card'])->first()) {
            $result = $this->result('fail','该银行已经添加,请换一张重试!');
        }else{
            if ($bank->save()) {
                $result = $this->result('success','添加银行卡成功!');
            }else{
                $result = $this->result('fail', '添加银行卡失败,请稍后再试,或者联系客服!');
            }
        }
        return $result;
	}
	
	public function del($id)
	{
		if (bank::find($id)->delete()) {
			$result = $this->result('success','删除银行卡成功!');
		} else {
			$result = $this->result('fail','未知错误,删除银行卡失败!');
		}
		return $result;
	}
}
