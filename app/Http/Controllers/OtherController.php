<?php

namespace App\Http\Controllers;
set_time_limit(3000);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Log;
use App\Model\User;
use App\Model\article;
use App\Model\join;
use App\Model\goods;
use App\Model\log_point_down;
use App\Model\log_point_open;
use App\Model\log_point_join;
use App\Model\log_point_spring;
use App\Model\rtsh_obj;
use App\Model\rtsh_order;
use App\Model\test_user;
use App\Model\test_join;
use App\Model\test_bank;
use App\Model\test_rtsh_order;
use App\Model\log_rtsh_rent;
use App\Model\log_price_join;
use App\Model\bbm_member_wallet;
use App\Model\bank;
use App\Model\log_point_user;
use App\Model\bbm_discount;
use App\Model\order_wx;
use App\Model\ticket_order;
use App\Model\cash;
use App\Model\order;
use App\Model\join_order;

use App\Http\Controllers\Api\Oss;
ini_set("max_execution_time", "1800");
require_once base_path()."/public/class/PHPExcel.php";
require_once base_path()."/public/class/PHPExcel/IOFactory.php";
class OtherController extends Controller
{
    // 获取验证码图片
    public function makeCode()
    {
        $common = new Controller;
        if ($common->makeCode()) {
            $result = $common->result('success','成功',$common->makeCode());
        }else{
            $result = $common->result('fail','获取验证码失败,请刷新后再试','');
        }
        return $result;
    }

    // 获取验证码进行校验
    public function isCode()
    {
        $input = Input::all();
        $common = new Controller;
        // dd($common->getCode());
        if (strtoupper($input['code']) == $common->getCode()) {
            $result = $common->result('success','成功','');
        }else{
            $result = $common->result('fail','验证码不正确,请重新输入','');
        }
        return $result;
    }

    //图片上传
    public function img()
    {
        $file = Input::file('Filedata');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(10000,99999).'.'.$entension;
            $path = $file -> move(base_path().'/public/images',$newName);
            $filepath = 'images/'.$newName;
            return $filepath;
        }
    }


    // 用户图片上传处理
    public function imgBase64()
    {
        $input = Input::all();
        $data = explode(",", $input['str']);
        $type = strtolower(explode(";", explode("/",$data['0'])['1'])['0']);
        $picType = array("jpg","jpeg","png","gif","bmp");
        if (in_array($type, $picType)) {
           $fileName =  "images/uid/".date('YmdHis').mt_rand(10000,99999).'.'.$type;
           if (file_put_contents($fileName, base64_decode($data['1']))) {
                $obj['status'] = 'success';
                $obj['msg'] = $fileName;
           }else{
                $obj['status'] = 'fail';
                $obj['msg'] = '服务器内部错误，无法保存图片！';
           }
        }else{
            $obj['status'] = 'fail';
            $obj['msg'] = '图片类型不正确，请重新选择图片上传！';
        }
        return json_encode($obj);
    }

    // 处理识别用户身份证信息
    public function ocr()
    {
        $input = Input::all();
        // dd($input);
        $url = "https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
        $appcode = "4635460834644dec83b7ad1e47c08b2b";
        // $file = "http://".env('HTTP_HOST')."/".$input['uimg'];
        $file = $input['uimg'];
        // dd($file);
        //如果输入带有inputs, 设置为True，否则设为False
        $is_old_format = false;
        //如果没有configure字段，config设为空
        $config = array(
            "side" => "face"
        );
        //$config = array()


        if($fp = fopen($file, "rb", 0)) {
            $binary = fread($fp, filesize($file)); // 文件读取
            fclose($fp); 
            $base64 = base64_encode($binary); // 转码
        }
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
        $querys = "";
        if($is_old_format == TRUE){
            $request = array();
            $request["image"] = array(
                    "dataType" => 50,
                    "dataValue" => $base64
            );

            if(count($config) > 0){
                $request["configure"] = array(
                        "dataType" => 50,
                        "dataValue" => json_encode($config) 
                    );
            }
            $body = json_encode(array("inputs" => array($request)));
        }else{
            $request = array(
                "image" => $base64
            );
            if(count($config) > 0){
                $request["configure"] = json_encode($config);
            }
            $body = json_encode($request);
        }
        $method = "POST";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$url, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); 
        $rheader = substr($result, 0, $header_size); 
        $rbody = substr($result, $header_size);

        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        if($httpCode == 200){
            if($is_old_format){
                $output = json_decode($rbody, true);
                $result_str = $output["outputs"][0]["outputValue"]["dataValue"];
            }else{
                $result_str = $rbody;
            }
            $result = $this->result('success','识别成功',$result_str);
        }else{
            $result = $this->result('success','身份证信息识别失败,请重新上传','');
        }
        return $result;
    }

    public function send()
    {
        $input = Input::all();
        if ($this->sendSms($input['phone'])) {
            $result = $this->result('success','成功!',$input['phone']);
        }else{
            $result = $this->result('fail','短信发送失败!');
        }
        return $result;
    }

    // public function varSms()
    // {
    //     dd($this->sendVarSms('18608782520,帅哥一号','isInfo'));
    // }
    // 给user表赋予UUID
    public function testUuid()
    {
        // $data = test_user::get()->count();
        for ($i=0; $i < 4000 ; $i++) {
            $uuid[] = $this->uuid();
        }
        // dd($uuid);
        foreach ($uuid as $key => $value) {
            // dd($key);
            test_user::whereNull('uuid')
                ->where('user_id',$key)
                ->update(['uuid'=>$value]);
        }
    }

    // 为join表赋予UUID
    public function joinUuid()
    {
        $data = test_join::leftJoin('mw_users','mw_users.user_name','=','bbm_agent_info.member_username')
            ->select('bbm_agent_info.AgentID','mw_users.uuid')
            ->get();
        // dd($data);
        foreach ($data as $key => $value) {
            test_join::where('AgentID',$value->AgentID)->update(['uuid'=>$value->uuid]);
        }

    }

    // 钱包表写入UUID
    public function testWallet()
    {
        $data = bbm_member_wallet::leftJoin('mw_users','bbm_member_wallet.UserID','=','mw_users.user_id')
            ->get();
        foreach ($data as $key => $value) {
            bbm_member_wallet::where('WalletID',$value->WalletID)
                ->update(['uuid'=>$value->uuid]);
        }
    }

    // 写入join 代理商表
    public function testJoin()
    {
        $data = test_join::get();
        $array = array();
        foreach ($data as $key => $value) {
            $info = test_user::where('user_name',$value->member_username)->first();
            if ($info) {
                $uuid = $info->uuid;
            }else{
                $uuid = '';
            }
            if ($value->is_chuncan == 1) {
                $protocol = 2;
            }else{
                $protocol = 1;
            }
            if ($value->chuncan_start_date) {
                $springTime = date('Y-m-d H:i:s',$value->chuncan_start_date);
            }else{
                $springTime = '';
            }
            if ($value->chuncan_end_date) {
                $springTimeEnd = date('Y-m-d H:i:s',$value->chuncan_end_date);
            }else{
                $springTimeEnd = '';
            }
            if ($value->PreAgentID) {
                $pid = test_join::find($value->PreAgentID)->uuid;
            }else{
                $pid = '';
            }
            $array[] = [
                "uuid"=>$uuid,
                "pid"=>$pid,
                "point"=>$value->UserCoins,
                "point_give"=>$value->GiveCoins,
                "price"=>$value->CashMoney,
                "join_cash"=>$value->CpCashMoney,
                "rtsh_property"=>$value->CaProperty,
                "rtsh_bond"=>$value->CpCredit,
                "protocol"=>$protocol,
                "ads"=>$value->AgentAddress,
                "point_fund"=>$value->commission,
                "point_fund_total"=>$value->total_commission,
                "fund_end"=>$value->returned_participation_fee,
                "fund_price_total"=>$value->total_fund,
                "fund_price_balance"=>$value->fund,
                "fund_point_balance"=>$value->chuncan_frozen_give_integral,
                "spring_start"=>$springTime,
                "spring_end"=>$springTimeEnd,
                "spring_pid_count"=>$value->promote_chuncan_count,
                "created_at"=>date('Y-m-d H:i:s')
            ];
        }
        // dd($array);
        $join = new join();
        // 入库
        $result = $join::insert($array);
        // dd($result);
        if(!$result){
            DB::rollBack();
        }else{
            echo "success";
        }
    }

    // 整理user表数据
    public function testUser()
    {
        $data = test_user::whereNotNull('mobile')
            // ->where('mw_users.user_id','>','1999')
            ->leftJoin('bbm_member_wallet','bbm_member_wallet.UserID','=','mw_users.user_id')
            ->get();
        $data_mobile = array();
        foreach ($data as $key => $value) {
            if (strlen($value->mobile) == '11') {
                $data_mobile[] = $value;
            }
        }
        $array = [];
        $user = new user();
        foreach ($data_mobile as $key => $value) {
            switch ($value->user_rank) {
                case '1':
                    $rank = 0;
                    break;
                case '2':
                    $rank = 1;
                    break;
                case '3':
                    $rank = 2;
                    break;
                case '4':
                    $rank = 3;
                    break;
                case '5':
                    $rank = 4;
                    break;
                case '6':
                    $rank = 5;
                    break;
                case '7':
                    $rank = 9;
                    break;
                case '8':
                    $rank = 6;
                    break;
                default:
                    $rank = 0;
                    break;
            }
            $pid = test_user::where('user_name',$value->IntroduceUserName)
                ->first();
            if ($pid) {
                $userPid = $pid->uuid;
            }else{
                $userPid = '';
            }
            $joinPidInfo = test_join::where('AgentCode',$value->AgentCode)
                ->first();
            if ($joinPidInfo) {
                $joinPid = $joinPidInfo->uuid;
            }else{
                $joinPid = '';
            }
            $joinBuyInfo = test_join::where('AgentID',$value->AgentID)
                ->first();
            if ($joinBuyInfo) {
                $joinBuy = $joinBuyInfo->uuid;
            }else{
                $joinBuy = '';
            }
            if ($value->IdCardFrontPath) {
                $user_uid_a = str_replace('upload/idPic/', 'images/uid/', $value->IdCardFrontPath);
            }else{
                $user_uid_a = '';
            }
            if ($value->IdCardBackPath) {
                $user_uid_b = str_replace('upload/idPic/', 'images/uid/', $value->IdCardBackPath);
            }else{
                $user_uid_b = '';
            }
            $uid[] = [
                "a" => $value->IdCardFrontPath,
                "b" => $value->IdCardBackPath
            ];
            $array[] = [
                "user_uuid"=>$value->uuid,
                "user_phone"=>$value->mobile,
                "user_rank"=>$rank,
                "user_sex"=>$value->sex,
                "user_birthday"=>str_replace('-','',$value->birthday),
                "created_at"=>date('Y-m-d H:i:s',$value->reg_time),
                "updated_at"=>date('Y-m-d H:i:s'),
                "user_point"=>$value->user_money,
                "user_point_give"=>$value->give_money,
                "user_pid"=>$userPid,
                "temp_join"=>$value->rank_status,
                "join_pid"=>$joinPid,
                "join_buy"=>$joinBuy,
                "user_name"=>$value->realname,
                "user_uid"=>$value->idcard,
                "user_uid_a"=>$user_uid_a,
                "user_uid_b"=>$user_uid_b,
                "old"=>'1',
                "rtsh_bond"=>$value->WalletCredit + $value->FrozenCredit,
                "user_price"=>$value->WalletRewards
            ];
        }
        dd(json_encode($uid));
        $user = new User();
        // 入库
        $result = $user::insert($array);
        // dd($result);
        if(!$result){
            DB::rollBack();
        }else{
            echo "success";
        }
    }


    // 整理融通四海订单表
    public function testRtshOrder()
    {
        $data = test_rtsh_order::
        leftJoin('rtsh_obj','rtsh_obj.title','=','bbm_creditor_user_asset.GoodsName')
        ->leftJoin('bbm_agent_info','bbm_creditor_user_asset.AgentCode','=','bbm_agent_info.AgentCode')
        ->leftJoin('mw_users','mw_users.user_id','=','bbm_creditor_user_asset.UserID')
        ->select('bbm_creditor_user_asset.*','mw_users.uuid as user_uuid','rtsh_obj.*','bbm_agent_info.*','bbm_agent_info.uuid as join_uuid')
        ->orderBy('bbm_creditor_user_asset.AssetsID','ASC')
        ->get();
        // dd($data);
        $array = [];
        foreach ($data as $key => $value) {
            // dd($value);
            if ($value->Rate  == 18) {
                $time = 2;
            }else{
                $time = 1;
            }
            if ($value->AssetsStatus == 1) {
                $end = 0;
            }else{
                $end = 2;
            }
            $array[] = [
                "num" => date('YmdHis',$value->AddTime).rand('111111','999999'),
                "obj_id" => $value->id,
                "type" => 1,
                "uuid" => $value->user_uuid,
                "join_uuid" => $value->join_uuid,
                "join_name" => $value->RealName,
                "price" => $value->AssetsMoney,
                "odds" => $value->Rate/100,
                "time" => $time,
                "img" => $value->img,
                "status" => '1',
                "end" => $end,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ];
        }
        // dd($array);
        $rtsh_order = new rtsh_order();
        // 入库
        $result = $rtsh_order::insert($array);
        // dd($result);
        if(!$result){
            DB::rollBack();
        }else{
            echo "success";
        }
    }

    public function testBank()
    {
        // 查询出银行卡信息
        $bankInfo = test_bank::get();
        $array = [];
        $cc = array();
        foreach ($bankInfo as $key => $value) {
            if ($info = test_user::where('user_id',$value->UserID)->first()) {
                if (!bank::where('bank_card',$value->BankNo)->first()) {
                    $bank = new bank;
                    $bank->bank_card = $value->BankNo;
                    $bank->name = $value->BankPersonName;
                    $bank->bank_name = $value->BankName;
                    $bank->bank_phone = $value->UserMobile;
                    $bank->bank_logo = $this->getBankCardLogo($value->BankNo);
                    $bank->bank_code = '';
                    $bank->bank_location = $value->BankSubName;
                    $bank->status = $value->BankStatus;
                    $bank->uuid = $info->uuid;
                    $bank->save();
                }
            }
        }
    }

    // 账户调换
    // $data = join::get();
    //     foreach ($data as $key => $value) {
    //         join::where('uuid',$value->uuid)
    //             ->update([
    //                 'rtsh_bond'=>$value->rtsh_bond + $value->join_cash,
    //                 'price'=>$value->fund_price_balance,
    //                 'join_cash'=>$value->price
    //             ]);
    //     }

    // 年费检查方法
    public function rankStatus()
    {
        // 检查是否有到期需要扣费的 写入待扣费字段
        $data = User::where('user_rank','>','1')->get();
        $array = array();
        foreach ($data as $key => $value) {
            $y = date('Y',$value->rank_start);
            $m = date('m',$value->rank_start);
            $d = date('d',$value->rank_start);
            $time = ceil(time() - $value->rank_start) / 86400;
            if ($y == date('Y',strtotime('now'))) {
                if ($m == date('m',strtotime('-1 month'))) {
                    if ($d == date('d',strtotime('now'))) {
                        $array[] = $value->user_uuid;
                    }
                }
            }else{
                if ($m == date('m',strtotime('now'))) {
                    if ($d == date('d',strtotime('now'))) {
                        $array[] = $value->user_uuid;
                    }
                }
            }
        }
        // dd($array);
        if ($array) {
            foreach ($array as $key => $value) {
                User::where('user_uuid',$value)->update([
                    'rank_status'=>1
                ]);
            }
        }

        // 查询处所有待扣费
        $data = User::where('user_rank','>','1')
            ->where('rank_status','1')
            ->select('user_point','user_point_give','user_uuid')
            ->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $key => $value) {
                if (($value->user_point + $value->user_point_give) > 365) {
                    if ($value->user_point_give >= 365) {
                        $newPoint = $value->user_point;
                        $newPointGive = $value->user_point_give - 365;
                    }else{
                        $newPoint = ($value->user_point + $value->user_point_give) - 365;
                        $newPointGive = 0;
                    }
                    User::where('user_uuid',$value->user_uuid)->update([
                        "user_point"=>$newPoint,
                        "user_point_give"=>$newPointGive,
                        "rank_status"=>0
                    ]);
                    // 写入日志
                    $log = new log_point_user;
                    $log->uuid = $value->user_uuid;
                    $log->point = $value->user_point;
                    $log->new_point = $newPoint;
                    $log->type = 6;
                    $log->point_give = $value->user_point_give;
                    $log->new_point_give = $newPointGive;
                    $log->status = 1;
                    $log->add = 2;
                    dd($log);
                    $log->save();
                }
            }
        }
    }

    public function excel()
    {
        $file_name = '2018.xlsx';
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn = $sheet->getHighestColumn();//取得总列数
        $data = array();
        for($i=1;$i<=$highestRow;$i++){
            for($j='A';$j<=$highestColumn;$j++){
                $data[$i][] = $objPHPExcel->getActiveSheet()->getCell("$j$i")->getValue();
            }
        }
        $arr = array();
        foreach ($data as $key => $value) {
            if ($value['0'] != '') {
                $value['1'] = explode('.',$value['1'])['0'];
                if ($user = User::where('user_phone',$value['1'])->first()) {
                    $value['2'] = $user->user_uuid;
                }else{
                    $user = User::where('user_name',$value['0'])->first();
                    $value['2'] = $user->user_uuid;
                }
                $arr[] = $value;
            }
        }
        // dd($arr);
        $data = array();
        foreach ($arr as $key => $value) {
            // 检查扣费记录
            if ($tmp = log_point_user::where('type','6')
                ->where('uuid',$value['2'])
                ->get()->count() > 1) {
                $data[] = $tmp;
            }
        }
        dd($data);
    }

    public function test()
    {
        // $user = User::where('user_phone','18608782520')->first();
        // dd(Crypt::decrypt($user->user_password));
        // 获取所有经销商
        $sale = join::where('type','1')->get();
        $point = 0;
        $point_give = 0;
        $point_fund = 0;
        foreach ($sale as $key => $value) {
            $point += $value->point;
            $point_give += $value->point_give;
            $point_fund += $value->point_fund;
        }
        echo "普通";
        print_r($point);
        echo "赠送";
        print_r($point_give);
        echo "返佣";
        print_r($point_fund);
        echo "<pre>";

        // 获取售卖的积分
        // $salePoint = 0;
        // $rankPoint = 0;
        // $sales = array();
        // foreach ($sale as $key => $value) {
        //     $salePoint += join_order::where('uuid',$value->uuid)->where('type','2')->get()->sum('point');
        //     $rankPoint += join_order::where('uuid',$value->uuid)->where('type','1')->get()->sum('point');
        //     $sales[] = $value->uuid;
        // }
        // $zijue[] = join_order::whereIn('uuid',$sales)->where('type','1')->where('point','>','1000')->get();
        // // dd($zijue);
        // foreach ($zijue as $key => $value) {
        //     if ($value) {
        //         foreach ($value as $k => $v) {
        //             $user[] = User::where('user.user_uuid',$v->to)
        //                 ->select('user.user_uuid','user.user_name','user.user_nickname','user.user_phone')
        //                 ->first();
        //         }
        //     }
        // }

        // foreach ($user as $key => $value) {
        //     $value['pay'] = order::where('uuid',$value->user_uuid)
        //         ->where('status','1')
        //         ->get()->sum('point');
        //     $order[] = $value;
        // }

        // $str = "ID序号,购买用户,姓名,电话,金额\n";
        // // $str = iconv('utf-8','GB18030',$str);
        // foreach ($order as $key => $value) {
        //     if ($key) {
        //         $str .= $key.",".$value->user_name.",".$value->user_nickname.",".$value->user_phone.",".$value->pay."\n";
        //     }
        // }
        // $fileName = '消费明细.csv';
        // $this->export_csv($fileName,$str);



        // echo "售卖的积分"; print_r($salePoint);
        // echo "<br>";
        // echo "售卖会籍的积分"; print_r($rankPoint);
        // echo "<br>";
        // echo "剩余为赠送出去的开拓积分"; print_r(1500000 - $point - $salePoint - $rankPoint);

        // 获取到经销商发展到的用户总数  按月分割
        // $users = 0;
        // foreach ($sale as $key => $value) {
        //     // $users += User::where('user_pid',$value->uuid)
        //     //     ->where('created_at','>','2019-03-18')
        //     //     ->where('created_at','<','2019-04-19')
        //     //     ->get()
        //     //     ->count();
        //     $users[] = User::where('user_pid',$value->uuid)
        //         ->get();
        //     $sales[] = $value->uuid;
        // }
        // foreach ($users as $key => $value) {
        //     // $arr = $value->user_uuid;
        //     foreach ($value as $k => $v) {
        //         $arr[] = $v->user_uuid;
        //     }
        // }
        // dd($sale->count());
        // dd($arr); // 所有人的uuid数组
        // $wx = order_wx::whereIn('uuid',$arr)
        //     ->where('status','1')
        //     ->get()
        //     ->sum('total_fee');
        // dd($wx);
        // $order = order::whereIn('uuid',$sales)
        //     ->where('status','1')
        //     ->where('created_at','>','2018-10-18')
        //     ->where('created_at','<','2018-11-19')
        //     ->get()->sum('point');
        // dd($order);
        // if (rtsh_order::where('obj_id',30)
        //     ->where('time',2)
        //     ->update(['end'=>1])) {
        //     echo "success";
        // }else{
        //     echo "die";
        // }
    }
}


























