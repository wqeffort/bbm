<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// 加载Model
use App\Model\User;
use App\Model\chat;
use App\Model\admin;

use Log;
class ChatController extends Controller
{
    public function index()
    {
    	// $app = app('wechat.official_account');
     //    if (empty(session('user'))) {
     //        $app = app('wechat.official_account');
     //        $response = $app->oauth->scopes(['snsapi_userinfo'])
     //            ->redirect();
     //        return $response;
     //    }else{
        	
     //    }
    }
}
