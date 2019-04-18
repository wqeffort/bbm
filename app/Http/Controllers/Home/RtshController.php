<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\article;
use App\Model\rtsh_order;
use App\Model\rtsh_obj;
use App\Model\log_rtsh;
use App\Model\log_rtsh_rent;

use Log;
class RtshController extends Controller
{
    public function index()
    {
    	$app = app('wechat.official_account');
    	$article = article::where('cate_id','1')->where('status','1')
    		->get();
    	return view('home.rtsh',compact('app','article'));
    }

    public function object()
    {
    	$app = app('wechat.official_account');
    	$obj = rtsh_obj::orderBy('id','DESC')->take(10)->get();
    	return view('home.rtsh-object',compact('app','obj'));
    }

    public function getDesc()
    {
    	$input = Input::all();
    	$desc = rtsh_obj::find($input['id']);
    	if ($desc) {
    		$result = $this->result('success','成功!',$desc->desc);
    	}else{
    		$result = $this->result('fail','获取数据失败,请稍后再试!');
    	}
    	return $result;
    }

    public function order()
    {
    	$app = app('wechat.official_account');
    	$order = rtsh_order::orderBy('rtsh_order.id','DESC')
    		->leftJoin('rtsh_obj','rtsh_obj.id','=','rtsh_order.obj_id')
    		->where('rtsh_order.uuid',session('user')->user_uuid)
    		->select('rtsh_order.*','rtsh_obj.title','rtsh_obj.odds_1','rtsh_obj.odds_2','rtsh_obj.img','rtsh_obj.start','rtsh_obj.end as objEnd','rtsh_obj.desc')
    		->get();
    	// dd($order);
    	return view('home.rtsh-order',compact('app','order'));
    }

    public function list()
    {
    	$app = app('wechat.official_account');
    	$data = log_rtsh::where('log_rtsh.uuid',session('user')->user_uuid)
    		->where('log_rtsh.status','1')
    		->leftJoin('rtsh_order','log_rtsh.num','=','rtsh_order.num','rtsh_order.time')
    		->take(20)
    		->select('log_rtsh.*','rtsh_order.price as total','rtsh_order.obj_id','rtsh_order.time')
    		->get();
    	// dd($data);
    	$list = array();
        // dd($data);
    	foreach ($data as $key => $value) {
            if ($value->obj_id) {
                $info = rtsh_obj::where('id',$value->obj_id)
                ->first();
                $value->start = $info->start;
                $value->title = $info->title;
                $value->odds_1 = $info->odds_1;
                $value->odds_2 = $info->odds_2;
                $list[] = $value;
            }
    	}
    	// dd($list);
    	return view('home.user-rtsh-list',compact('app','list'));
    }

    // 用户中心页面
    public function walletBond()
    {
        $app = app('wechat.official_account');
        // 获取当前投资总额 有效订单
        $data = rtsh_order::orderBy('rtsh_order.id','DESC')
            ->where('rtsh_order.uuid',session('user')->user_uuid)
            ->leftJoin('rtsh_obj','rtsh_obj.id','=','rtsh_order.obj_id')
            ->where('rtsh_order.status','1')
            // ->where('rtsh_order.end','0')
            ->select('rtsh_order.*','rtsh_obj.title','rtsh_obj.start')
            ->take(20)
            ->get();
        // dd($data);
        if ($data->isNotEmpty()) {
            $allPrice = $data->sum('price');
            $allOrder = $data->count('id');
        }else{
            $allPrice = 0;
            $allOrder = 0;
        }
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        return view('home.wallet-bond',compact('app','data','allPrice','allOrder','user'));
    }

    public function getLog()
    {
        $input = Input::all();
        if ($data = log_rtsh_rent::orderBy('id','DESC')
            ->where('num',$input['num'])
            ->get()) {
            $result = $this->result('success','成功!',$data);
        }else{
            $result = $this->result('fail','获取数据失败!!');
        }
        return $result;
    }

    public function setProtocol()
    {
        $input = Input::all();
        if ($user = User::where('user_uuid',session('user')->user_uuid)
            ->first()) {
            if ($user->rtsh_protocol == 1) {
                $protocol = 0;
            }else{
                $protocol = 1;
            }
            if (User::where('user_uuid',session('user')->user_uuid)
                ->update(['rtsh_protocol'=>$protocol])) {
                $result = $this->result('success','设置成功!');
            }else{
                $result = $this->result('success','ERROR!设置失败!');
            }
        }else{
            $result = $this->result('fail','获取数据失败!!');
        }
        return $result;
    }
}
