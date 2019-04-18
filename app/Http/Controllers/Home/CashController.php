<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Response;

// 加载Model
use App\Model\User;
use App\Model\goods;
use App\Model\attribute;
use App\Model\category;
use App\Model\brand;
use App\Model\cash;
use App\Model\bank;
use App\Model\car;
use App\Model\ads;
use App\Model\join;
use App\Model\log_point_spring;
use App\Model\log_price_join;
use App\Model\log_rtsh;
class CashController extends Controller
{
    public function atm()
    {
    	$app = app('wechat.official_account');
    	$card = bank::where('uuid',session('user')->user_uuid)
    		->orderBy('status','DESC')
    		->get();
    	// 检查用户状态是否封禁,否则无法进行提现操作!
    	$user = user::where('user_uuid',session('user')->user_uuid)->first();
    	if ($user->status == 0) {
    		dd('该账号功能已被部分限制,请联系客服人员!');
    	}else{
    		return view('home.cash',compact('app','user','card'));
    	}
    }

    public function getBalance()
    {
    	$input = Input::all();
    	/**
    	 * type
    	 * 1: 债权收益
    	 * 2: 股权收益
         * 7: 推广收益 临时的
    	 */
    	$user = User::where('user_uuid',session('user')->user_uuid)
    		->first();
    	switch ($input['type']) {
    		case '1':
    			$data = $user->rtsh_bond;
    			break;
            case '2':
                $data = $user->rtsh_property;
                break;
            case '7':
                $data = $user->user_price;
                break;
    	}
    	if (isset($data)) {
    		$result = $this->result('success','获取成功!',$data);
    	}else{
    		$result = $this->result('fail','查询失败,请稍后再试!');
    	}
    	return $result;
    }

    public function add()
    {
    	$input = Input::all();
        if ($input['type'] == 1 || $input['type'] == 2 || $input['type'] == 7) {
            $user = User::where('user_uuid',session('user')->user_uuid)
                ->where('status','1')
                ->first();
        }else{
            $user = User::where('user_uuid',session('join')->uuid)
                ->where('status','1')
                ->first();
             $join = join::where('status','1')
                ->where('uuid',session('join')->uuid)
                ->first();
        }
        if ($user || $join) {
            switch ($input['type']) {
                case '7':
                    if ($input['price'] % 100 == 0) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                // return $this->result('fail','由于系统更新迭代,提现功能8月1日后开放,望理解!');
                                $newPrice = $user->user_price - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                // 春蚕提现
                case '6':
                    if ($input['price'] >= 5000 && $input['price'] % 100 == 0) {
                        if ($user->cash_password) {
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                $newPrice = $join->price - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        $nowTime = date('Y-m-d H:i:s');
                        $day = ceil(strtotime($nowTime) - strtotime($join->spring_start)) / 86400;
                        if ($day > 250) {
                            if ($input['price'] % 100 == 0 && $input['price'] > 1) {
                                if ($user->cash_password) {
                                    if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                        $newPrice = $join->price - $input['price'];
                                    }else{
                                        return $this->result('fail','您输入的提现密码不正确!');
                                    }
                                }else{
                                    return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                                }
                            }
                        }else{
                            return $this->result('fail','提现失败!提现金额不符合要求!');
                        }
                    }
                    break;
                // 加盟商产权提现
                case '5':
                    if ($input['price'] % 100 == 0 && $input['price'] > 1) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                $newPrice = $join->rtsh_property - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                // 加盟商债权提现
                case '4':
                    if ($input['price'] % 100 == 0 && $input['price'] > 1) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                // return $this->result('fail','由于系统更新迭代,提现功能8月1日后开放,望理解!');
                                $newPrice = $join->rtsh_bond - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                // 梦享家收益提现
                case '3':
                    if ($input['price'] % 100 == 0 && $input['price'] > 1) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                $newPrice = $join->join_cash - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                // 融通产权提现
                case '2':
                    if ($input['price'] % 10 == 0 && $input['price'] > 1) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                // return $this->result('fail','由于系统更新迭代,提现功能8月1日后开放,望理解!');
                                $newPrice = $user->rtsh_property - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                // 融通债权提现
                case '1':
                    if ($input['price'] % 10 == 0 && $input['price'] > 1) {
                        if ($user->cash_password) {
                            // 验证加盟商密码
                            if ($input['password'] == Crypt::decrypt($user->cash_password)) {
                                // return $this->result('fail','由于系统更新迭代,提现功能8月1日后开放,望理解!');
                                $newPrice = $user->rtsh_bond - $input['price'];
                            }else{
                                return $this->result('fail','您输入的提现密码不正确!');
                            }
                        }else{
                            return $this->result('fail','提现失败,请您先到个人中心设置支付密码');
                        }
                    }else{
                        return $this->result('fail','提现失败!提现金额不符合要求!');
                    }
                    break;
                default:
                    return $this->result('fail','提现失败,未知的请求!');
                    break;
            }
            // dd($newPrice);
            if ($newPrice >= 0) {
                $cash = new cash;
                $cash->type = $input['type'];
                $cash->bank_id = $input['card'];
                $cash->price = $input['price'];
                $cash->uuid = $user->user_uuid;
                // dd($cash);
                DB::beginTransaction();
                try{
                    switch ($input['type']) {
                        case '7':
                            User::where('user_uuid',$user->user_uuid)
                                ->update([
                                    "user_price"=>$newPrice
                                ]);
                            break;
                        case '6':
                            join::where('uuid',session('join')->uuid)
                                ->update([
                                    "price"=>$newPrice
                                ]);
                            // 写入春蚕日志
                            $log = new log_point_spring;
                            $log->uuid = $join->uuid;
                            $log->point = $join->price;
                            $log->type = 3;
                            $log->new_point = $newPrice;
                            $log->status = 1;
                            $log->add = 2;
                            $log->save();
                            break;
                        case '5':
                            join::where('uuid',$join->uuid)
                                ->update([
                                    "rtsh_property"=>$newPrice
                                ]);
                            // 写入日志
                            $log = new log_price_join;
                            $log->uuid = $join->uuid;
                            $log->rtsh_property = $join->rtsh_property;
                            $log->type = 3;
                            $log->new_rtsh_property = $newPrice;
                            $log->status = 1;
                            $log->add = 2;
                            $log->save();
                            break;
                        case '4':
                            join::where('uuid',$join->uuid)
                                ->update([
                                    "rtsh_bond"=>$newPrice
                                ]);
                            // 写入日志
                            $log = new log_price_join;
                            $log->uuid = $join->uuid;
                            $log->rtsh_bond = $join->rtsh_bond;
                            $log->type = 3;
                            $log->new_rtsh_bond = $newPrice;
                            $log->status = 1;
                            $log->add = 2;
                            $log->save();
                            break;
                        case '3':
                            join::where('uuid',$join->uuid)
                                ->update([
                                    "join_cash"=>$newPrice
                                ]);
                            // 写入日志
                            $log = new log_price_join;
                            $log->uuid = $join->uuid;
                            $log->join_cash = $join->join_cash;
                            $log->type = 3;
                            $log->new_join_cash = $newPrice;
                            $log->status = 1;
                            $log->add = 2;
                            $log->save();
                            break;
                        case '2':
                            User::where('user_uuid',$user->user_uuid)
                                ->update([
                                    "rtsh_property"=>$newPrice
                                ]);
                            // 写入日志
                            $log = new log_rtsh;
                            $log->uuid = $user->user_uuid;
                            $log->num = date('YmdHis').rand('111111','999999');
                            $log->price = $user->rtsh_property;
                            $log->type = 3;
                            $log->new_price = $newPrice;
                            $log->status = 1;
                            // dd($log);
                            $log->save();
                            break;
                        case '1':
                        // dd($newPrice);
                            User::where('user_uuid',$user->user_uuid)
                                ->update([
                                    "rtsh_bond"=>$newPrice
                                ]);
                            // 写入日志
                            $log = new log_rtsh;
                            $log->uuid = $user->user_uuid;
                            $log->num = date('YmdHis').rand('111111','999999');
                            $log->price = $user->rtsh_bond;
                            $log->type = 3;
                            $log->new_price = $newPrice;
                            $log->status = 1;
                            $log->save();
                            break;
                    }
                    $cash->save();
                    DB::commit();
                    $result = $this->result('success','提现成功,当前账户余额为: '.$newPrice.' CNY<br>您的提现已申请成功!','');
                }catch (\Exception $e) {
                    //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail','ERROR!系统繁忙,提现失败,请稍后再试!','');
                }
            }else{
                $result = $this->result('fail', '您的账户余额不足以提现,请降低提现金额后再试!');
            }
        }else{
            return $this->result('fail','未查询到用户信息,请刷新后再试!');
        }
    	return $result;
    }

    public function log()
    {
    	$app = app('wechat.official_account');
    	$input = Input::all();
    	$log = cash::orderBy('cash.id','DESC')
            ->whereIn('cash.type',[1,2])
    		->where('cash.uuid',session('user')->user_uuid)
    		->leftJoin('bank','bank.id','=','cash.bank_id')
    		->select('cash.*','bank.bank_name','bank.bank_card','bank.bank_logo','bank.bank_location')
    		->get();
    	return view('home.cash-log',compact('app','log'));
    }

    public function joinLog()
    {
        $app = app('wechat.official_account');
        $input = Input::all();
        $log = cash::orderBy('cash.id','DESC')
            ->whereIn('cash.type',[3,4,5])
            ->where('cash.uuid',session('user')->user_uuid)
            ->leftJoin('bank','bank.id','=','cash.bank_id')
            ->select('cash.*','bank.bank_name','bank.bank_card','bank.bank_logo','bank.bank_location')
            ->get();
        return view('home.join-cash-log',compact('app','log'));
    }

    public function springLog()
    {
        $app = app('wechat.official_account');
        $input = Input::all();
        $log = cash::orderBy('cash.id','DESC')
            ->where('cash.type',6)
            ->where('cash.uuid',session('user')->user_uuid)
            ->leftJoin('bank','bank.id','=','cash.bank_id')
            ->select('cash.*','bank.bank_name','bank.bank_card','bank.bank_logo','bank.bank_location')
            ->get();
        return view('home.spring-cash-log',compact('app','log'));
    }
}
