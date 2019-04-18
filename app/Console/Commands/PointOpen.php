<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Log;
use App\Model\User;
use App\Model\join;
use App\Model\log_point_user;
use App\Model\log_point_join;
use App\Model\log_point_open;
// 检查开拓积分
class PointOpen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'point:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'point open';

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
        // 检查到期的开拓积分
        $data = log_point_open::where('time','like',date('Y-m-d')."%")
            ->where('end','0')
            ->where('status','1')
            ->get();
        foreach ($data as $key => $value) {
            // 查询到用户积分
            $user = User::where('user_uuid',$value->uuid)->first();
            $join = join::where('uuid','596A043D-664B-5A5A-7F54-3C74B9E332F6')->first();
            if ($user && $join) {
                if ($user->user_point_open > 0) {
                    // 进行返还合伙人动作
                    // dd($user->user_point_open);
                    User::where('user_uuid',$value->uuid)->update(['user_point_open'=>0]);
                    join::where('uuid',$join->uuid)->update([
                        "point_open"=>$join->point_open + $user->user_point_open
                    ]);
                    log_point_open::find($value->id)->update(['end'=>1]);
                    // 合伙人日志
                    $joinLog = new log_point_join;
                    $joinLog->point_open = $join->point_open;
                    $joinLog->uuid = $join->uuid;
                    $joinLog->new_point_open = $join->point_open + $user->user_point_open;
                    $joinLog->type = 26;
                    $joinLog->status = 1;
                    $joinLog->add = 1;
                    $joinLog->to = $user->user_uuid;
                    $joinLog->save();
                    // 用户日志
                    $userLog = new log_point_user;
                    $userLog->point_open = $user->user_point_open;
                    $userLog->uuid = $user->user_uuid;
                    $userLog->new_point_open = 0;
                    $userLog->add = 2;
                    $userLog->status = 1;
                    $userLog->save();
                }else{
                    log_point_open::find($value->id)->update(['end'=>1]);
                }
            }
        }
    }
}
