<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\car;
use App\Model\ads;
use App\Model\log_point_user;
use App\Model\collection;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        $collection = collection::where('uuid',session('user')->user_uuid)->where('status','1')->get()->count();
        return view('view.user',compact('user','collection'));
    }

    public function point($value='')
    {
        $user = User::where('user_uuid',session('user')->user_uuid)->first();

        return view('view.point',compact('user'));
    }

    public function collection()
    {
        $goods = collection::orderBy('collection.id','DESC')
            ->leftJoin('goods','collection.goods_id','=','goods.id')
            ->where('collection.uuid',session('user')->user_uuid)
            ->where('collection.status','1')
            ->get();
        // dd($goods);
        return view('view.collection-list',compact('goods'));
    }














    public function ads()
    {
        // 查询出用户的现在的地址
        $user = User::where('user_uuid',session('user')->user_uuid)->first();
        // 获取到所有的收货地址
        $ads = ads::where('uuid',session('user')->user_uuid)->where('del','0')->get();
        return view('view.ads',compact('user','ads'));
    }


    public function adsSelect()
    {
    	$ads = ads::where('uuid',session('user')->user_uuid)
    		->where('status','1')
    		->where('del','0')
    		->first();
    	if ($ads) {
    		$result = $this->result('success','成功!',$ads);
    	}else{
    		$result = $this->result('fail','未获取到默认地址');
    	}
    	return $result;
    }

    public function adsEdit($id)
    {
        $ads = ads::find($id);
        return view('view.ads-edit', compact('ads'));
    }

    public function adsEditPost($id)
    {
        $input = Input::all();
        $ads = explode('/',$input['city']);
        $province = $ads['0'];
        $city = $ads['1'];
        if (isset($ads['2'])) {
            $area = $ads['2'];
        }else{
            $area = '';
        }
        if (User::find($id)->update([
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "ads"=>$input['ads']

        ])) {
            $result = $this->result('success','保存通讯地址成功!');
        }else{
            $result = $this->result('success','ERROR!保存通讯地址失败!');
        }
        return $result;
    }

    public function adsAdd()
    {
        $user = User::where('user_uuid',session('user')->user_uuid)
            ->first();
        $name = $user->user_name;
        $phone = $user->user_phone;
        return view('view.ads-add',compact('name','phone'));
    }

    public function adsAddPost()
    {
    	$input = Input::all();
    	$ads = new ads;
    	$ads->uuid = session('user')->user_uuid;
    	$ads->ads = $input['ads'];
    	$ads->name = $input['name'];
    	$ads->phone = $input['phone'];
    	$text = explode('/',$input['city']."/");
    	$ads->province = $text['0'];
    	$ads->city = $text['1'];
    	$ads->area = $text['2'];
    	// 检查用户是否存在默认地址,如果没有,就直接设为默认地址
    	if (ads::where('uuid',session('user')->user_uuid)
    		->where('status','1')
    		->where('del','0')
    		->first()) {
    		$ads->status = 0;
    	}else{
    		$ads->status = 1;
    	}
    	if ($ads->save()) {
    		$result = $this->result('success','添加收货地址成功!','');
    	}else{
    		$result = $this->result('fail','添加收货地址失败!','');
    	}
    	return $result;
    }

    public function adsDel()
    {
        $input = Input::all();
        // 删除用户地址
        if (ads::find($input['id'])->update(['del'=>1])) {
            $result = $this->result('success','删除地址成功!');
        }else{
            $result = $this->result('fail','删除收货地址失败,请稍后再试!');
        }
        return $result;
    }

    // 接收用户设置默认地址
    public function adsStatus()
    {
    	$input = Input::all();
    	if (ads::where('uuid',session('user')->user_uuid)
        		->update(['status'=>'0'])) {
    		if (ads::where('id',$input['id'])
        			->update(['status'=>'1'])) {
    			$result = $this->result('success','设置默认收货地址成功!','');
    		}else{
    			$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    		}
    	}else{
    		$result = $this->result('fail','设置默认收货地址失败,请重新再试!','');
    	}

		return $result;
    }


    // 获取用户的积分日志
    public function pointLog()
    {
        $log = log_point_user::orderBy('id','DESC')
            ->where('uuid',session('user')->user_uuid)
            ->take(100)
            ->get();
        $result = $this->result('success','成功!',$log);
        return $result;
    }
}
