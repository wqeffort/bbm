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
class SpringStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spring:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spring start';

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
        // 每天派发程序
        $nowTime = date('Y-m-d H:i:s');
        $join = join::where('protocol','2')
            ->whereNotNull('spring_start')
            ->whereNotNull('spring_end')
            ->where('end','0')
            ->get();
        if ($join->isNotEmpty()) {
            foreach ($join as $key => $value) {
                $day = ceil(strtotime($nowTime) - strtotime($value->spring_start)) / 86400;
                if ($day < 10) {
                    $user = User::where('user_uuid',$value->uuid)->first();
                    User::where('user_uuid',$value->uuid)->update([
                        "user_point_give"=>$user->user_point_give + 500
                    ]);
                    $log_point_spring = new log_point_spring;
                    $log_point_spring->uuid = $value->uuid;
                    $log_point_spring->point = $user->user_point_give;
                    $log_point_spring->type = 2;
                    $log_point_spring->add = 1;
                    $log_point_spring->new_point = $user->user_point_give + 500;
                    $log_point_spring->save();
                    $log = new log_point_user;
                    $log->uuid = $value->uuid;
                    $log->point = $user->user_point;
                    $log->new_point = $user->user_point;
                    $log->type = 2;
                    $log->point_give = $user->user_point_give;
                    $log->new_point_give = $user->user_point_give + 500;
                    $log->status = 1;
                    $log->add = 1;
                    $log->save();
                }else{
                    if ($day < 250) {
                        if (strtotime($value->spring_start) > 1538323199) {
                            if ($day < 242) {
                                join::where('uuid',$value->uuid)->update([
                                    "price"=>$value->price + 500
                                ]);
                                $log_point_spring = new log_point_spring;
                                $log_point_spring->uuid = $value->uuid;
                                $log_point_spring->point = $value->price;
                                $log_point_spring->type = 1;
                                $log_point_spring->add = 1;
                                $log_point_spring->new_point = $value->price + 500;
                                $log_point_spring->save();
                            }
                        }else{
                            join::where('uuid',$value->uuid)->update([
                                "price"=>$value->price + 500
                            ]);
                            $log_point_spring = new log_point_spring;
                            $log_point_spring->uuid = $value->uuid;
                            $log_point_spring->point = $value->price;
                            $log_point_spring->type = 1;
                            $log_point_spring->add = 1;
                            $log_point_spring->new_point = $value->price + 500;
                            $log_point_spring->save();
                        }
                    }
                }
            }
        }
    }
}
