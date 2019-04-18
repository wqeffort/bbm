<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
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

    public function list()
    {
        $article = article::orderBy('article.id','DESC')
            ->leftJoin('article_cate','article.cate_id','=','article_cate.id')
            ->select('article.*','article_cate.name','article_cate.id as cate_id')
            ->get();
        return view('admin.article-list', compact('article'));
    }

    public function add()
    {
        $cate = article_cate::orderBy('id','ASC')->get();
        return view('admin.article-add',compact('cate'));
    }

    public function create()
    {
        $input = Input::all();
        // dd($input);
        $article = new article;
        $article->title = $input['title'];
        $article->text = $input['text'];
        $article->cate_id = $input['cate_id'];
        $article->img = $input['img'];
        $article->log = $input['log'];
        if ($article->save()) {
            $result = $this->result('success','添加文章成功!','');
        }else{
            $result = $this->result('fail','ERROR!添加文章失败,请检查输入字段!','');
        }
        return $result;
    }

    public function show($id)
    {
        $article = article::find($id);
        $cate = article_cate::orderBy('id','ASC')
            ->where('status','1')
            ->get();
        return view('admin.article-show',compact('article','cate'));
    }

    public function edit($id)
    {
        $input = Input::all();
        if (article::where('id',$id)
            ->update([
            'title' => $input['title'],
            'text' => $input['text'],
            'cate_id' => $input['cate_id'],
            'img' => $input['img'],
            'log' => $input['log']
            ])) {
            $result = $this->result('success','编辑成功!','');
        }else{
            $result = $this->result('fail','ERROR!保存文章失败,请检查输入字段','');
        }
        return $result;
    }
    public function status($id)
    {
        $common = new Controller;
        if (article::find($id)->status) {
            $newStatus = '0';
        }else{
            $newStatus = '1';
        }
        if (isset($newStatus)) {
            if (article::where('id',$id)
                ->update(['status'=>$newStatus])) {
                $result = $common->result('success','修改成功!','');
            }else{
                $result = $common->result('fail','ERROR!修改状态失败!','');
            }
        }else{
            $result = $common->result('fail','修改状态失败!','');
        }
        return $result;
    }
}
