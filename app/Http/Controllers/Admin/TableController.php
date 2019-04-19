<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
// 加载Model
use App\Model\log_point_spring;
use App\Model\cash;

// error_reporting(E_ALL);
class TableController extends Controller
{
    public function springCash()
    {
        // 获取昨日春蚕提现列表
        $data = log_point_spring::orderBy('log_point_spring.id', 'ASC')
            ->leftJoin('user', 'user.user_uuid', '=', 'log_point_spring.uuid')
            ->select('log_point_spring.*', 'user.user_name', 'user.user_phone')
            ->where('log_point_spring.type', '3')
            ->where('log_point_spring.created_at', 'like', date('Y-m-d', strtotime('-1 day')).'%')
            ->get();

        return view('admin.table-spring-cash', compact('data'));
    }

    public function springCashFind()
    {
        // 查询指定日期
        $input = Input::all();
        $data = log_point_spring::orderBy('log_point_spring.id', 'ASC')
            ->leftJoin('user', 'user.user_uuid', '=', 'log_point_spring.uuid')
            ->select('log_point_spring.*', 'user.user_name', 'user.user_phone')
            ->where('log_point_spring.type', '3')
            ->where('log_point_spring.created_at', 'like', $input['time'].'%')
            ->get();
        if ($data->isNotEmpty()) {
            $result = $this->result('success', '查询成功!', $data);
        } else {
            $result = $this->result('fail', '未查询到数据');
        }

        return $result;
    }

    public function springCashPrint($time)
    {
        $data = log_point_spring::orderBy('log_point_spring.id', 'ASC')
            ->leftJoin('user', 'user.user_uuid', '=', 'log_point_spring.uuid')
            ->select('log_point_spring.*', 'user.user_name', 'user.user_phone')
            ->where('log_point_spring.type', '3')
            ->where('log_point_spring.created_at', 'like', $time.'%')
            ->get();
        $str = "ID序号,提现用户,用户识别码,用户电话,原有金额,提现金额,剩余金额,提现时间\n";
        // $str = iconv('utf-8','GB18030',$str);
        foreach ($data as $key => $value) {
            $name = $value->user_name;
            $cash = $value->point - $value->new_point;
            $str .= $value->id.','.$name.','.$value->uuid.','.$value->user_phone.','.$value->point.','.$cash.','.$value->new_point.','.$value->created_at."\n";
        }
        $fileName = '春蚕提现-'.$time.'.csv';
        $this->export_csv($fileName, $str);
    }

    // 加盟商提成
    public function joinCash()
    {
        // 获取昨日加盟商提现列表
        $info = cash::orderBy('cash.id', 'ASC')
            ->join('user', 'user.user_uuid', '=', 'cash.uuid')
            ->join('join', 'join.uuid', '=', 'cash.uuid')
            ->join('bank', 'bank.uuid', '=', 'cash.uuid')
            ->leftJoin('user as users', 'users.user_uuid', '=', 'cash.admin')
            ->where('cash.type', '3')
            ->where('cash.created_at', 'like', date('Y-m').'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'join.join_cash', 'bank.bank_card', 'users.user_name as users_name')
            // ->groupBy('cash.id')
            ->get();
        // dd($data);
        $data = [];
        foreach ($info as $item) {
            $data[$item->id] = [
                'id' => $item->id,
                'type' => $item->type,
                'bank_id' => $item->bank_id,
                'price' => $item->price,
                'status' => $item->status,
                'uuid' => $item->uuid,
                'img' => $item->img,
                'admin' => $item->admin,
                'log' => $item->log,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'user_name' => $item->user_name,
                'user_phone' => $item->user_phone,
                'join_cash' => $item->join_cash,
                'bank_card' => $item->bank_card,
                'users_name' => $item->users_name,
            ];
        }
        // dd($data);
        return view('admin.table-join-cash', compact('data'));
    }

    public function joinCashFind()
    {
        // 查询指定日期
        $input = Input::all();
        $data = cash::orderBy('cash.id', 'ASC')
            ->join('user', 'user.user_uuid', '=', 'cash.uuid')
            ->join('join', 'join.uuid', '=', 'cash.uuid')
            ->join('bank', 'bank.uuid', '=', 'cash.uuid')
            ->leftJoin('user as users', 'users.user_uuid', '=', 'cash.admin')
            ->where('cash.type', '3')
            ->where('cash.created_at', 'like', $input['time'].'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'join.join_cash', 'bank.bank_card', 'users.user_name as users_name')
            ->groupBy('cash.id')
            ->get();
        if ($data->isNotEmpty()) {
            $result = $this->result('success', '查询成功!', $data);
        } else {
            $result = $this->result('fail', '未查询到数据');
        }

        return $result;
    }

    public function joinCashPrint($time)
    {
        $data = cash::orderBy('cash.id', 'ASC')
            ->join('user', 'user.user_uuid', '=', 'cash.uuid')
            ->join('join', 'join.uuid', '=', 'cash.uuid')
            ->join('bank', 'bank.uuid', '=', 'cash.uuid')
            ->leftJoin('user as users', 'users.user_uuid', '=', 'cash.admin')
            ->where('cash.type', '3')
            ->where('cash.created_at', 'like', $time.'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'join.join_cash', 'bank.bank_card', 'users.user_name as users_name')
            ->groupBy('cash.id')
            ->get();

        $str = "ID序号,提现用户,用户识别码,用户电话,收款卡号,提现金额,提现时间,审核人员,备注信息,处理时间\n";
        // $str = iconv('utf-8','GB18030',$str);
        foreach ($data as $key => $value) {
            $name = $value->user_name;
            $cash = $value->point - $value->new_point;
            $str .= $value->id.','.$name.','.$value->uuid.','.$value->user_phone.','.$value->bank_card.','.$value->price.','.$value->created_at.','.$value->users_name.','.$value->img.'--'.$value->log.','.$value->updated_at."\n";
        }
        $fileName = '加盟商佣金提现(月)-'.$time.'.csv';
        $this->export_csv($fileName, $str);
    }

    public function finance()
    {
        // 查询出今日的财务信息
        $data = cash::orderBy('cash.id', 'ASC')
            ->leftJoin('bank', 'cash.bank_id', '=', 'bank.id')
            ->leftJoin('user', 'cash.uuid', '=', 'user.user_uuid')
            ->leftJoin('user as admin', 'cash.admin', '=', 'admin.user_uuid')
            ->whereIn('cash.type', [3, 6])
            ->where('cash.created_at', 'like', date('Y-m-d').'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'bank.bank_card', 'bank.name', 'admin.user_name as admin')
            // ->take(10)
            ->get();
        // dd($data);
        return view('admin.table-finance', compact('data'));
    }

    public function financeFind()
    {
        $input = Input::all();
        $data = cash::orderBy('cash.id', 'ASC')
            ->leftJoin('bank', 'cash.bank_id', '=', 'bank.id')
            ->leftJoin('user', 'cash.uuid', '=', 'user.user_uuid')
            ->leftJoin('user as admin', 'cash.admin', '=', 'admin.user_uuid')
            ->whereIn('cash.type', [3, 6])
            ->where('cash.created_at', 'like', $input['time'].'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'bank.bank_card', 'bank.name', 'admin.user_name as admin')
            // ->take(10)
            ->get();
        if ($data->isNotEmpty()) {
            $result = $this->result('success', '查询成功!', $data);
        } else {
            $result = $this->result('fail', '未查询到数据');
        }

        return $result;
    }

    public function financePrint($time)
    {
        $input = Input::all();
        $data = cash::orderBy('cash.id', 'ASC')
            ->leftJoin('bank', 'cash.bank_id', '=', 'bank.id')
            ->leftJoin('user', 'cash.uuid', '=', 'user.user_uuid')
            ->leftJoin('user as admin', 'cash.admin', '=', 'admin.user_uuid')
            ->whereIn('cash.type', [3, 6])
            ->where('cash.created_at', 'like', $time.'%')
            ->select('cash.*', 'user.user_name', 'user.user_phone', 'bank.bank_card', 'bank.name', 'admin.user_name as admin')
            // ->take(10)
            ->get();

        $str = "ID序号,提现用户,用户识别码,用户电话,收款卡号,提现金额,提现类型,提现时间,审核人员,备注信息,处理时间\n";
        // $str = iconv('utf-8','GB18030',$str);
        foreach ($data as $key => $value) {
            $name = $value->user_name;
            switch ($value->type) {
                case '1':
                    $type = '债权提现';
                    break;
                case '2':
                    $type = '产权提现';
                    break;
                case '3':
                    $type = '加盟商现金';
                    break;
                case '4':
                    $type = '加盟商债权';
                    break;
                case '5':
                    $type = '加盟商产权';
                    break;
                case '6':
                    $type = '春擦提现';
                    break;
            }
            $str .= $value->id.','.$name.','.$value->uuid.','.$value->user_phone.','.$value->bank_card.','.$value->price.','.$type.','.$value->created_at.','.$value->admin.','.$value->img.'--'.$value->log.','.$value->updated_at."\n";
        }
        $fileName = '公账财务报表-'.$time.'.csv';
        $this->export_csv($fileName, $str);
    }
}
