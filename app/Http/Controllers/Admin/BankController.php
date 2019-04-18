<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\join;
use App\Model\log_login;
use App\Model\bank;
use Illuminate\Http\Response;
use App\Model\ticket;
use App\Model\ticket_order;
use Log;
class BankController extends Controller
{
    public function edit($id)
    {
        $input = Input::all();
        if (bank::where('id',$id)
            ->update([
                "bank_location"=>$input['bank_location'],
                "bank_card"=>$input['bank_card'],
                "name"=>$input['name'],
                "bank_name"=>$input['bank_name']
            ])) {
            $result = $this->result('success','修改银行卡信息成功!');
        } else {
            $result = $this->result('fail','修改失败,未知错误!');
        }
        return $result;
    }

    public function del($id)
    {
        if (bank::destroy($id)) {
            $result = $this->result('success','删除银行卡成功!');
        } else {
            $result = $this->result('fail','删除银行卡失败,未知的错误!');
        }
        return $result;
    }
}
