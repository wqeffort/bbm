<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\goods;
use Illuminate\Http\Response;
use Log;

class IndexController extends Controller
{
    public function index()
    {
        // dd(session('user'));
        $banner = ad::where('id','>','4')
            ->Where('id','<','10')
            ->get();
        // 获取推荐商品
        $goods = goods::orderBy('id','DESC')
        	->where('is_hot','1')
        	->where('status','1')
        	->select('id','goods_name','goods_title','goods_price','goods_point','goods_pic')
        	->get();
        // dd($goods);
    	return view('view.index',compact('banner','goods'));
    }

    public function webIndex($uuid)
    {
        session(['user'=>'']);
        $user = User::where('user_uuid',$uuid)->first();
        session(['user'=>$user]);
        $banner = ad::where('id','>','4')
            ->Where('id','<','10')
            ->get();
        // 获取推荐商品
        $goods = goods::orderBy('id','DESC')
            ->where('is_hot','1')
            ->where('status','1')
            ->select('id','goods_name','goods_title','goods_price','goods_point','goods_pic')
            ->get();
        // dd($goods);
        return view('view.index',compact('banner','goods'));
    }

}
