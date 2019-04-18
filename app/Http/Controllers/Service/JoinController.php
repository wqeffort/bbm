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
use App\Model\ads;
use App\Model\join;
use App\Model\admin_recharge_point;
use App\Model\log_point_user;
use App\Model\log_point_join;
use App\Model\log_point_spring;
use App\Model\order;
use App\Model\log_price_join;

class JoinController extends Controller
{
    public function index()
    {
    	$data = join::orderBy('join.id', 'DESC')
    		->leftJoin('user','user.user_uuid','=','join.uuid')
    		->select('join.*','user.user_pic','user.user_uuid','user.user_name','user.user_rank','user.user_nickname')
    		->take(20)
    		->get();
    	return view('service.pages.join.list',compact('data'));
    }
}
