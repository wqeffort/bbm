<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\attribute;
use App\Model\ads;
use App\Model\join;
use App\Model\admin_recharge_point;
use App\Model\log_point_user;
use App\Model\log_point_join;
use App\Model\log_point_spring;
use App\Model\order;
use App\Model\log_price_join;

class LogController extends Controller
{
    public function userPoint($uuid)
    {
    	// 获取用户的所有积分日志
    	$input = Input::all();
        $data = log_point_user::where('uuid',$uuid)
            ->where('status','1')
            ->get();
        return $this->result('success','获取数据成功!',$data);
    }

    public function userOrder($uuid)
    {
    	// $user = User::where('user_uuid',$uuid)->first();
        $data = order::orderBy('order.id','DESC')
            ->where('order.uuid',$uuid)
            ->where('order.status','1')
            ->leftJoin('goods','goods.id','=','order.goods_id')
            ->select('order.*','goods.goods_name','goods.goods_pic')
            ->get()
            ->groupBy('num');
        $order = array();
        foreach ($data as $key => $value) {
            $cc = array();
            $total = 0;
            foreach ($value as $k => $v) {
            	if (is_numeric($v->goods_attr)) {
	            	$v->goods_attr .= attribute::find($v->goods_attr)->attr_name;
	            }else{
	            	foreach (explode(',', $v->goods_attr) as $ke => $va) {
	            		$v->goods_attr .= attribute::find($va)->attr_name."  ";
	            	}
	            }
                $total += $v->point;
                $id = $v->id;
                $type = $v->type;
                $num = $v->num;
                $express = $v->express;
                $mark = $v->mark;
                $time = $v->created_at;
                $cc['order'][] = $v;
            }
            $cc['total'] = $total;
            $cc['id'] = $id;
            $cc['type'] = $type;
            $cc['num'] = $num;
            $cc['express'] = $express;
            $cc['mark'] = $mark;
            $cc['time'] = $time;
            $order[] = $cc;
        }
        return $this->result('success','获取成功!',$order);
    }

    public function joinPoint($uuid)
    {
        // 先判断用户是否是加盟商
        $join = Join::where('uuid',$uuid)->first();
        if ($join) {
            // 获取加盟商积分日志
            $log = log_point_join::orderBy('log_point_join.id','DESC')
                ->where('log_point_join.uuid',$uuid)
                ->where('log_point_join.status','1')
                ->leftJoin('user','user.user_uuid','=','log_point_join.to')
                ->select('log_point_join.*','user.user_nickname','user.user_phone','user.user_name')
                ->get();
            $result = $this->result('success','获取成功!',$log);
        }else{
            $result = $this->result('fail','该用户不是加盟商!');
        }
        return $result;
    }

    public function joinSpring($uuid)
    {
        $spring = Join::where('uuid',$uuid)
            ->where('protocol','2')
            ->first();
        if ($spring) {
            // 获取春蚕积分日志
            $log = log_point_spring::orderBy('id','DESC')
                ->where('uuid',$uuid)
                ->where('status','1')
                ->get();
            $result = $this->result('success','获取成功!',$log);
        }else{
            $result = $this->result('fail','该用户不是春蚕用户!');
        }

        return $result;
    }
}
