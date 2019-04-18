<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Log;
use App\Http\Controllers\Api\Oss;

// 加载Model
use App\Model\app_set;
use App\Model\app_banner;
use App\Model\app_ad;

class SetController extends Controller
{
	// 轮播列表
	public function bannerList()
	{
		$banner = app_banner::orderBy('id','DESC')
			->get();
		return view('service.pages.app.banner-list',compact('banner'));
	}

	public function bannerAdd()
	{
		return view('service.pages.app.banner-add');
	}

	public function bannerEdit($id)
	{
		$banner = app_banner::find($id);
		return view('service.pages.app.banner-edit',compact('banner'));
	}

	public function bannerEditPost($id)
	{
		$input = Input::all();
		if (app_banner::where('id',$id)->update([
			"title"=>$input['title'],
			"img"=>$input['img'],
			"link"=>$input['link'],
		])) {
			$result = $this->result('success','编辑成功!');
		}else{
			$result = $this->result('fail','ERROR!编辑失败,请稍后再试!!');
		}
		return $result;
	}

	public function bannerAddPost()
	{
		$input = Input::all();
		$banner = new app_banner;
		$banner->title = $input['title'];
		$banner->img = $input['img'];
		$banner->link = $input['link'];
		if ($banner->save()) {
			$result = $this->result('success','上传图片成功!');
		}else{
			$result = $this->result('fail','ERROR!上传图片失败了!');
		}
		return $result;
	}

	public function bannerStatus($id)
	{
		if (app_banner::find($id)->status) {
			if (app_banner::find($id)->update([
				"status"=>0
			])) {
				$result = $this->result('success','修改状态成功!');
			}else{
				$result = $this->result('fail','修改状态失败!');
			}
		}else{
			if (app_banner::find($id)->update([
				"status"=>1
			])) {
				$result = $this->result('success','修改状态成功!');
			}else{
				$result = $this->result('fail','修改状态失败!');
			}
		}
		return $result;
	}


	// 广告列表
	public function adList()
	{
		$ad = app_ad::orderBy('id','DESC')
			->get();
		return view('service.pages.app.ad-list',compact('ad'));
	}

	public function adAdd()
	{
		return view('service.pages.app.ad-add');
	}

	public function adEdit($id)
	{
		$ad = app_ad::find($id);
		return view('service.pages.app.ad-edit',compact('ad'));
	}

	public function adEditPost($id)
	{
		$input = Input::all();
		if (app_ad::where('id',$id)->update([
			"title"=>$input['title'],
			"img"=>$input['img'],
			"link"=>$input['link'],
		])) {
			$result = $this->result('success','编辑成功!');
		}else{
			$result = $this->result('fail','ERROR!编辑失败,请稍后再试!!');
		}
		return $result;
	}

	public function adAddPost()
	{
		$input = Input::all();
		$ad = new app_ad;
		$ad->img = $input['img'];
		$ad->link = $input['link'];
		$ad->title = $input['title'];
		if ($ad->save()) {
			$result = $this->result('success','添加广告成功!');
		}else{
			$result = $this->result('fail','添加广告失败,请稍后再试!');
		}
		return $result;
	}

	public function adStatus($id)
	{
		$input = Input::all();
		if (app_ad::find($id)->status) {
			if (app_ad::find($id)->update([
				"status"=>0
			])) {
				$result = $this->result('success','修改状态成功!');
			}else{
				$result = $this->result('fail','修改状态失败!');
			}
		}else{
			if (app_ad::find($id)->update([
				"status"=>1
			])) {
				$result = $this->result('success','修改状态成功!');
			}else{
				$result = $this->result('fail','修改状态失败!');
			}
		}
		return $result;
	}
}
