<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Log;
use App\Model\User;
use App\Model\join;
use App\Model\log_point_down;
use App\Model\log_point_spring;
use App\Model\rtsh_obj;
use App\Model\rtsh_order;
use App\Model\log_rtsh_rent;

class RtshObject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rtsh:object';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rtsh object';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 查询出所有需要处理的订单
        $order = rtsh_order::where('rtsh_order.status','1')
            ->leftJoin('rtsh_obj','rtsh_obj.id','=','rtsh_order.obj_id')
            ->where('rtsh_obj.status','1')
            ->where('rtsh_order.end','0')
            ->where('rtsh_order.status','1')
            ->select('rtsh_order.*','rtsh_obj.start')
            ->get();
        // 过滤查询出6个月前的
        $month6 = date('Y-m-d',strtotime("-6 month"));
        // 过滤查询出3个月前的
        $month3 = date('Y-m-d',strtotime("-3 month"));
        // dd($date3);
        // 对比两个时间是否同月同日
        foreach ($order as $key => $value) {
            // dd($value);
            // print_r(date('ymdhis',strtotime("today")));
            // print_r(strtotime($value->start));echo "<br>";
            // print_r(strtotime("today"));echo "<br>";
            // print_r(date('d',strtotime($value->start)));echo "<br>";
            // dd(date('d',strtotime("today")));
            // 核查时间.
            // dd($value);
            if ($value->time == 2) {
                $date6 = floor((strtotime($value->start) - strtotime($month6)) / 86400);
                // dd($date6);
                // dd($value);
                if ($date6 >= 0) {
                    // dd($date6);
                         // 还在时间范围内.派发月息
                    if (strtotime($value->start) < strtotime("today") && date('d',strtotime($value->start)) == date('d',strtotime("today"))) {
                             // 派息 计算加盟商提成
                        $newCount = $value->count + ($value->price * ($value->odds / 12));
                        if ($value->join_uuid) {
                            $newJoinCount = $value->join_count + (($value->price * ($value->odds / 12)) * 0.1);
                            // if ($join = join::where('uuid',$value->join_uuid)->first()) {
                            //     // 写入加盟商日志
                            //     $joinLog = new log_price_join;
                            //     $joinLog->uuid = $value->join_uuid;
                            //     $joinLog->rtsh_bond = ($value->price * ($value->odds / 12)) * 0.1;
                            //     $joinLog->new_rtsh_bond = $join->rtsh_bond + ($value->price * ($value->odds / 12)) * 0.1;
                            //     $joinLog->type = 2;
                            //     $joinLog->add = 1;
                            //     $joinLog->status = 0;
                            //     $joinLog->save();
                            // }
                        }else{
                            $newJoinCount = 0;
                        }
                        rtsh_order::where('num', $value->num)
                            ->update([
                                "count"=>$newCount,
                                "join_count"=>$newJoinCount
                            ]);
                        // 写入融通四海滚动日志
                        $log = new log_rtsh_rent;
                        $log->num = $value->num;
                        $log->price = $value->price * ($value->odds / 12);
                        $log->type = 1;
                        $log->status = 0;
                        $log->save();

                    }
                }else{
                    // 六个月到期
                    if (date('d',strtotime($value->start)) == date('d',strtotime("today"))) {
                        rtsh_order::where('num',$value->num)->update(["end"=>1]);
                    }
                }
            }else{
                // dd($value);
                $date3 = floor((strtotime($value->start) - strtotime($month3)) / 86400);
                if ($date3 >= 0) {
                    // 还在时间范围内.派发月息
                    if (strtotime($value->start) < strtotime("today") && date('d',strtotime($value->start)) == date('d',strtotime("today")) ) {
                        // 派息 计算加盟商提成
                        $newCount = $value->count + ($value->price * ($value->odds / 12));
                        // dd($newCount);
                        if ($value->join_uuid) {
                            $newJoinCount = $value->join_count + (($value->price * ($value->odds / 12)) * 0.1);
                            // if ($join = join::where('uuid',$value->join_uuid)->first()) {
                            //     // 写入加盟商日志
                            //     $joinLog = new log_price_join;
                            //     $joinLog->uuid = $value->join_uuid;
                            //     $joinLog->rtsh_bond = ($value->price * ($value->odds / 12)) * 0.1;
                            //     $joinLog->new_rtsh_bond = $join->rtsh_bond + ($value->price * ($value->odds / 12)) * 0.1;
                            //     $joinLog->type = 2;
                            //     $joinLog->add = 1;
                            //     $joinLog->status = 0;
                            //     $joinLog->save();
                            // }
                        }else{
                            $newJoinCount = 0;
                        }
                        rtsh_order::where('num', $value->num)
                            ->update([
                                "count"=>$newCount,
                                "join_count"=>$newJoinCount
                        ]);
                        // 写入融通四海滚动日志
                        $log = new log_rtsh_rent;
                        $log->num = $value->num;
                        $log->price = $value->price * ($value->odds / 12);
                        $log->type = 1;
                        $log->status = 0;
                        $log->save();

                    }
                }else{
                    // 三个月到期
                    if (date('d',strtotime($value->start)) == date('d',strtotime("today"))) {
                        rtsh_order::where('num',$value->num)->update(["end"=>1]);
                    }
                }
            }
        }
    }
}
