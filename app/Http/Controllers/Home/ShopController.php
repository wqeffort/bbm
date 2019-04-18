<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\goods;
use App\Model\attribute;
use Log;
class ShopController extends Controller
{
    public function index()
    {
        // dd(session('app'));
        // session(['user'=>'']);
        // 判断session是否存在,如果不存在则跳转Oauth
        if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            session(['app'=>$app]);
            $common = new Controller;
            // 获取广告图片
            $banner = ad::where('status','1')
                ->where('id','>','4')
                ->where('id','<','10')
                ->get();
            $ad_1 = ad::find('1');
            $ad_2 = ad::find('2');
            $ad_3 = ad::find('3');
            $ad_4 = ad::find('4');

            // 获取新品
            $new = goods::orderBy('goods.updated_at','DESC')
                ->where('goods.status','1')
                ->where('goods.is_new','1')
                ->get();
            // 获取热销
            $goodsHot = goods::orderBy('goods.updated_at','DESC')
                ->where('goods.status','1')
                // ->leftJoin('attribute','goods.id','=','attribute.goods_id')
                ->where('goods.is_hot','1')
                // ->select('goods.*','attribute.attr_depot')
                ->take(4)
                ->get();
            // dd($goodsHot);
            $hot = array();
            foreach ($goodsHot as $key => $value) {
                $value->depot += $value->attr_depot;
                $hot[] = $value;
            }
            // dd($hot);
            // 猜您喜欢
            $goodsPush = goods::where('goods.status','1')
                ->orderBy(\DB::raw('RAND()'))
                // ->leftJoin('attribute','attribute.goods_id','=','goods.id')
                // ->select('goods.*','attribute.attr_depot')
                ->take(4)
                ->get();
            $push = array();
            foreach ($goodsPush as $key => $value) {
                $value->depot += $value->attr_depot;
                $push[] = $value;
            }

            return view('home.shop',compact('banner','ad_1','ad_2','ad_3','ad_4','new','hot','push'));
        }
    }
}
