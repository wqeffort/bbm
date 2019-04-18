<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\log_login;
use App\Model\join;
use App\Model\admin_join_order;
use App\Model\bank;
use App\Model\order;
use App\Model\goods;
use App\Model\ads;
use App\Model\attribute;
use App\Model\log_point_user;
use App\Model\log_point_join;
use App\Model\log_price_join;
use App\Model\log_point_spring;
use App\Model\cash;
use Log;
class SearchController extends Controller
{
    public function searchUser()
    {
        $input = Input::all();
        if (ctype_digit($input['text'])) {
            $data = User::where('user_phone','like','%'.$input['text'].'%')->get();
            // dd($data);
            if ($data->isEmpty()) {
                $result = $this->result('fail','未查询到相关用户信息!');
            }else{
                $result = $this->result('success','查询成功!',$data);
            }
        }else{
            $data = User::where('user_name','like','%'.$input['text'].'%')->get();
            // dd($data);
            if ($data->isEmpty()) {
                $result = $this->result('fail','未查询到相关用户信息!');
            }else{
                $result = $this->result('success','查询成功!',$data);
            }
        }
        return $result;
    }

    public function search()
    {
        $input = Input::all();
        if (ctype_digit($input['keyWord'])) {
            $data = User::where('user.user_phone',$input['keyWord'])
                ->leftJoin('join','join.uuid','=','user.user_uuid')
                ->first();
            if ($data) {
                $result = $this->result('success','成功!',$data);
            } else {
                $result = $this->result('fail','未查询到用户!');
            }    
        } else {
            $data = User::where('user.user_name',$input['keyWord'])
                ->leftJoin('join','join.uuid','=','user.user_uuid')
                ->first();
            if ($data) {
                $result = $this->result('success','成功!',$data);
            } else {
                $result = $this->result('fail','未查询到用户!');
            } 
        }
        return $result;
    }

    public function searchName()
    {
        $input = Input::all();
        $data = User::where('user.user_name','like','%'.$input['text'].'%')
            ->leftJoin('join','join.uuid','=','user.user_uuid')
            ->get();
        if ($data->isEmpty()) {
            $result = $this->result('fail','未查询到相关用户信息!');
        }else{
            $result = $this->result('success','查询成功!',$data);
        }
        return $result;
    }

    public function searchPhone()
    {
        $input = Input::all();
        $data = User::where('user.user_phone','like','%'.$input['text'].'%')
            ->leftJoin('join','join.uuid','=','user.user_uuid')
            ->get();
        // dd($data);
        if ($data) {
            $result = $this->result('success','查询成功!',$data);
        }else{
            $result = $this->result('fail','未查询到相关用户信息!');
        }
        return $result;
    }

    public function user($uuid)
    {
        $data = array();
        $user = User::where('user_uuid',$uuid)->first();
        $data['user'] = $user;
        if ($join = join::where('uuid',$uuid)->first()) {
            $data['join'] = $join;
            $join_password = Crypt::decrypt($join->password);
        }else{
            $data['join'] = false;
        }

        if ($user->password) {
            $password = Crypt::decrypt($user->password);
        }else{
            $password = '未设置登录密码';
        }

        if ($user->cash_password) {
            $cash_password = Crypt::decrypt($user->cash_password);
        }else{
            $cash_password = '未设置支付密码';
        }

        // 获取绑定的用户
        if ($user->user_pid) {
            $userPid = User::where('user_uuid',$user->user_pid)->first();
        }else{
            $userPid = '';
        }
        // 获取绑定的加盟商
        if ($user->join_pid) {
            $joinPid = User::where('user_uuid',$user->join_pid)->first();
        }else{
            $joinPid = '';
        }
        // 获取银行卡信息
        $bank = bank::where('uuid',$uuid)->get();
        return view('admin.view-user',compact('data','password','cash_password','userPid','joinPid','join_password','bank'));
    }

    public function userEdit($uuid)
    {
        $input = Input::all();
        switch ($input['type']) {
            case 'name':
                if (User::where('user_uuid',$uuid)
                        ->update(['user_name'=>$input['val']])) {
                    $result = $this->result('success','修改成功!');
                }else{
                    $result = $this->result('fail','未知的错误,修改失败!!');
                }
                break;
            case 'phone':
                if (User::where('user_uuid',$uuid)
                        ->update(['user_phone'=>$input['val']])) {
                    $result = $this->result('success','修改成功!');
                }else{
                    $result = $this->result('fail','未知的错误,修改失败!!');
                }
                break;
            case 'userPassword':
                if (User::where('user_uuid',$uuid)
                        ->update(['password'=>Crypt::encrypt($input['val'])])) {
                    $result = $this->result('success','修改成功!');
                }else{
                    $result = $this->result('fail','未知的错误,修改失败!!');
                }
                break;
            case 'cashPassword':
                if (User::where('user_uuid',$uuid)
                        ->update(['cash_password'=>Crypt::encrypt($input['val'])])) {
                    $result = $this->result('success','修改成功!');
                }else{
                    $result = $this->result('fail','未知的错误,修改失败!!');
                }
                break;
            case 'joinPassword':
                if (join::where('uuid',$uuid)
                        ->update(['password'=>Crypt::encrypt($input['val'])])) {
                    $result = $this->result('success','修改成功!');
                }else{
                    $result = $this->result('fail','未知的错误,修改失败!!');
                }
                break;
        }
        return $result;
    }

    public function searchOrder()
    {
        $input = Input::all();
        if (ctype_digit($input['text'])) {
            $order = order::orderBy('order.id','ASC')
                ->leftJoin('goods','goods.id','=','order.goods_id')
                ->leftJoin('user','user.user_uuid','=','order.uuid')
                ->leftJoin('ads','ads.id','=','order.ads')
                ->select('user.user_nickname','user.user_name','user.user_pic','user.user_rank','goods.goods_name','goods.goods_pic','ads.province','ads.city','ads.area','ads.ads','ads.name','ads.phone','order.*')
                ->where('order.status','1')
                ->where('order.num','like','%'.$input['text'].'%')
                ->get();
            // ->groupBy('num');
            $orderInfo = $order->unique('num');
            foreach ($orderInfo as $key => $value) {
                // dd($value);
                $attr = array();
                foreach (explode(',',$value->goods_attr) as $a => $b) {
                    $attr[] = attribute::find($b)->attr_name;
                }
                $value->attr = $attr;
                $data[] = $value;
            }
        }else{
            $order = order::orderBy('order.id','ASC')
                ->leftJoin('goods','goods.id','=','order.goods_id')
                ->leftJoin('user','user.user_uuid','=','order.uuid')
                ->leftJoin('ads','ads.id','=','order.ads')
                ->select('user.user_nickname','user.user_name','user.user_pic','user.user_rank','goods.goods_name','goods.goods_pic','ads.province','ads.city','ads.area','ads.ads','ads.name','ads.phone','order.*')
                ->where('order.status','1')
                ->where('user.user_name','like','%'.$input['text'].'%')
                ->get();
            // ->groupBy('num');
            $orderInfo = $order->unique('num');
            foreach ($orderInfo as $key => $value) {
                // dd($value);
                $attr = array();
                foreach (explode(',',$value->goods_attr) as $a => $b) {
                    $attr[] = attribute::find($b)->attr_name;
                }
                $value->attr = $attr;
                $data[] = $value;
            }
        }
        if ($data) {
            $result = $this->result('success','查询成功!',$data);
        }else{
            $result =$this->result('fail','未查询到数据!');
        }
        return $result;
    }

    public function userLog($uuid)
    {
        $user = User::where('user_uuid', $uuid)->first();
        // 获取用户积分日志
        $userPointLog = log_point_user::orderBy('id','DESC')
            ->where('uuid',$uuid)
            ->where('status','1')
            // ->take(50)
            ->get();

        // 获取提现日志
        $cashLog = cash::orderBy('cash.id','DESC')
            ->where('cash.uuid',$uuid)
            ->where('cash.end','0')
            ->leftJoin('user','user.user_uuid','=','cash.admin')
            ->select('cash.*', 'user.user_name')
            ->get();
        // 判断用户是否是加盟商
        $join = join::where('uuid',$uuid)->first();
        if ($join) {
            $isJoin = true;
            // 获取加盟商积分账户明细
            $joinPointLog = log_point_join::orderBy('id','DESC')
            ->where('uuid',$uuid)
            ->where('status','1')
            // ->take(50)
            ->get();
            // 获取加盟商收益账户明细
            $joinPriceLog = log_price_join::orderBy('id','DESC')
                ->where('uuid',$uuid)
                ->where('status','1')
                ->get();
            // 获取春蚕账户明细
            if ($join->protocol == 2) {
                $isSpring = true;
                $springLog =  log_point_spring::orderBy('id','DESC')
                ->where('uuid', $uuid)
                ->get();
            }else{
                $isSpring = false;
                $springLog = '';
            }
        }else{
            $isJoin = false;
            $joinPointLog = '';
            $joinPriceLog = '';
        }
        return view('admin.view-log',compact('user','join','cashLog','userPointLog','joinPointLog','joinPriceLog','springLog','isJoin','isSpring'));
    }
}
