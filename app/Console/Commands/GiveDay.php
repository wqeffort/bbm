<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Log;
use App\Model\User;
use App\Model\log_point_user;
use App\Model\bbm_discount;
// 春蚕计划-每日派发
class GiveDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'give:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'give day';

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
        // 写入每日返还积分
        $now = strtotime(date('Y-m-d',strtotime('now')));
        $data = bbm_discount::where('EndTime','>',date('Y-m-d'))
            ->get();
        $array = [];
        foreach ($data as $key => $value) {
            // dd($value->DiscountMoney);
            $user = User::where('user_uuid',$value->uuid)->first();
            if ($user) {
                $newPoint = $user->user_point_give + (($value->DiscountMoney / 100) * 0.1);
                if (user::where('user_uuid',$value->uuid)->update(['user_point_give'=>$newPoint])) {
                    $log = new log_point_user;
                    $log->uuid = $user->user_uuid;
                    $log->point = $user->user_point;
                    $log->new_point = $user->user_point;
                    $log->type = 5;
                    $log->point_give = $user->user_point_give;
                    $log->new_point_give = $newPoint;
                    $log->status = 1;
                    $log->add = 1;
                    $log->save();
                }
            }
        }
    }
}
