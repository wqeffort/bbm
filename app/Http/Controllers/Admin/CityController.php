<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\log_login;
use App\Model\join;
use App\Model\join_order;
use Log;


class CityController extends Controller
{
    public function info()
    {
        // 获取城市合伙人区域
        $city = [
            [
                'city'=>'哈尔滨',
                'code'=>'2301'
            ]
        ];
        $data = [];
        foreach ($city as $key => $value) {
            $total = 0;
            $joinData = [];
            $new = join_order::where('join_order.created_at','like',date('Y-m',strtotime("last month")).'%')
                ->join('user','join_order.uuid','=','user.user_uuid')
                ->join('user as users','join_order.to','=','users.user_uuid')
                // ->where('user.user_uid','like',$value['code'].'%')
                ->where('users.user_uid','like',$value['code'].'%')
                ->select('join_order.*','user.user_name as join_name','users.user_name','user.user_uid')
                ->get();


            $result = User::leftJoin('join','user.user_uuid','=','join.uuid')
                ->where('user.user_uid','like',$value['code'].'%')
                // ->whereNotNull('join.uuid')
                ->get();
            // dd($result);
            foreach ($result as $k => $v) {
                $dataTotal = join_order::where('join_order.to',$v->user_uuid)
                    ->where('join_order.created_at','like',date('Y-m',strtotime("last month")).'%')
                    ->join('user','join_order.uuid','=','user.user_uuid')
                    ->join('user as users','join_order.to','=','users.user_uuid')
                    ->select('join_order.*','user.user_name as join_name','users.user_name','user.user_uid')
                    ->first();
                if ($dataTotal) {
                    if ($dataTotal->user_uid) {
                        if (substr($dataTotal->user_uid,0,4) == $value['code']) {
                            $total = $new->sum('point');
                            $joinData[] = $dataTotal;
                        }
                    }
                }
            }
            $data[$key]['city'] = $value['city'];
            $data[$key]['join'] = $result;
            $data[$key]['total'] = $total;
            $data[$key]['history'] = $new;
        }
        // dd($data);
        return view('admin.city-info',compact('data'));
    }










}
