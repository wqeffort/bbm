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
use App\Model\log_point_user;
class YearFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'year:fee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'year fee';

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
                    // dd($log);
                    $log->save();
                }
            }
        }
    }
}
