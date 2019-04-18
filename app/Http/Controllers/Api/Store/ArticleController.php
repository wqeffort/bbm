<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\ad;
use App\Model\article;
use App\Model\article_cate;
use Log;
class ArticleController extends Controller
{
    public function index()
    {
    	$article['banner'] = ad::where('status','1')
         	->where('id','>','14')
         	->where('id','<','20')
         	->get();
        $article['content'] = article::orderBy('article.id','DESC')
	        ->where('article.status','1')
	        ->where('article_cate.status','1')
	        ->orderBy('article.top','DESC')
	        ->orderBy('article.id','DESC')
	        ->leftJoin('article_cate','article.cate_id','=','article_cate.id')
	        ->select('article_cate.name','article_cate.pic','article.id','article.img','article.title','article.view','article.created_at','article.zan')
	        ->take(10)
	        ->get();
        return $this->result('success','获取数据成功!',$article);
    }

    public function article()
    {
    	$input = Input::all();
    	article::find($input['id'])->increment('view');
      	$article = article::find($input['id']);
      	return $result = $this->result('success','获取数据成功!',$article);
    }
}
