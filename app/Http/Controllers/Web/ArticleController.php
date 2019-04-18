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
use App\Model\order;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\goods;
use App\Model\article;
use App\Model\article_cate;
use Log;

class ArticleController extends Controller
{
   public function index()
   {
   	// $app = app('wechat.official_account');
      // session(['app'=>$app]);
      // 获取文章广告
      $ad = ad::where('status','1')
         ->where('id','>','14')
         ->where('id','<','20')
         ->get();
      // 获取资讯的分类
      $cate = article_cate::orderBy('id','ASC')
         ->where('status','1')
         ->get();
      // 获取推荐文章
   	$article = article::orderBy('article.id','DESC')
         ->where('article.status','1')
         ->where('article_cate.status','1')
         ->orderBy('article.top','DESC')
         ->orderBy('article.id','DESC')
         ->leftJoin('article_cate','article.cate_id','=','article_cate.id')
         ->select('article_cate.name','article_cate.pic','article.*')
         ->take(10)
         ->get();
      // dd($article);
      return view('view.article-list',compact('cate','article','ad'));
   }

   public function cate($id)
   {
      // $app = app('wechat.official_account');
      // session(['app'=>$app]);
      // 获取文章广告
      $ad = ad::where('status','1')
         ->where('id','>','14')
         ->where('id','<','20')
         ->get();
      // 获取资讯的分类
      $cate = article_cate::orderBy('id','ASC')
         ->where('status','1')
         ->get();
      // 获取推荐文章
      $article = article::orderBy('article.id','DESC')
         ->where('article.status','1')
         ->where('article_cate.status','1')
         ->orderBy('article.top','DESC')
         ->where('cate_id',$id)
         ->leftJoin('article_cate','article.cate_id','=','article_cate.id')
         ->select('article_cate.name','article_cate.pic','article.*')
         ->take(10)
         ->get();
      // dd($article);
      return view('view.article-list',compact('cate','article','ad'));
   }

   // 获取一篇文章
   public function article($id)
   {
      // $app = app('wechat.official_account');
      // session(['app'=>$app]);
      // dd(session('app'));
      // 每点击一次,自增1
      article::find($id)->increment('view');
      $article = article::find($id);
      // dd($article);
      return view('view.article',compact('article'));
   }
}
