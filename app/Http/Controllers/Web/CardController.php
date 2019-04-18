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
use App\Model\card;
use App\Model\attribute;
use App\Model\ads;
use App\Model\car;
use App\Model\bank;
use App\Model\goods;
use App\Model\collection;
use App\Model\log_point_user;
use App\Model\ticket;
use App\Model\ticket_order;
use Log;
use \Milon\Barcode\DNS1D;
class CardController extends Controller
{
    public function list()
    {
    	// 获取到用户得所有卡券
    	$data = card::orderBy('id','ASC')
    		->where('uuid',session('user')->user_uuid)
    		->where('end_time','>',date('Y-m-d H:i:s'))
    		->get();
    	$card = array();
        $no = '';
    	if ($data->isNotEmpty()) {
    		foreach ($data as $key => $value) {
	    		$value->barcode = DNS1D::getBarcodePNG($value->num, "C128");
                // 获取到桌号
                $noData = ticket_order::where('order_num',$value->num)->first();
                $value->no = ticket::find($noData->ticket_id)->desk;
                $card[] = $value;
	    	}
    	}else{
    		$card = '';
    	}
    	return view('view.card-list',compact('card','no'));
    }

    public function add( )
    {
    	$result = $this->result('fail','暂时未开发完毕,该功能预计8月中旬投入使用!');
    	return $result;
    }
}
