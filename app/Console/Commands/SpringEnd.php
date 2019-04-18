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
class SpringEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spring:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spring end';

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
        // 春蚕定时任务测试路由
        // 春蚕计划的四个时间段;
        // 1----- 1-10天为发放积分;
        // 2----- 11-250天为发放500RMB
        // 3----- 250--270天 检查party次数和发张的付费用户数量
        // 4----- 270内发展满十个会员(并且消费都满3000+) ->13万减去已经发放的
        $nowTime = date('Y-m-d H:i:s');
        $spring = join::where('protocol', '2')
            ->where('end','0')
            ->get();
        foreach ($spring as $key => $value) {
            $endUser = 0;
            // 消费已经满3000 的
            // 循环查询出发展用户
            // 先查询人数是否大于10
            $userCount = User::where('join_pid',$value->uuid)
                ->get();
            if ($userCount->isNotEmpty()) {
                foreach ($userCount as $k => $v) {
                    // 计算3000的逻辑
                    if ((log_point_down::where('uuid',$v->user_uuid)->get()->sum('point') + $v->user_point) >= 3000 ) {
                        $endUser++;
                    }
                }
                // dd($endUser);
                $value->endUser = $endUser;
                // dd($userCount);
            }
            $data[] = $value;
        }
        // dd($data);
        // 存入已完成3000任务的人数
        foreach ($data as $key => $value) {
            join::where('uuid',$value->uuid)->update(['spring_count'=>$value->endUser]);
        }

        // 重查表,检查是否有满足条件的
        $spring = join::where('join.protocol', '2')
            ->where('join.end','0')
            ->where('spring_count','>=','10')
            ->where('is_party','>=','4')
            ->get();
        // 开始派发
        if ($spring->isNotEmpty()) {
            foreach ($spring as $key => $value) {
                if ((ceil(strtotime($nowTime) - strtotime($value->spring_start)) / 86400) < 270) {
                    // 检查目前阶段
                    $day = ceil(strtotime($nowTime) - strtotime($value->spring_start) / (60 * 60 * 24));
                    if ($day > 10) {
                        // 进行到了第三阶段
                        $addPrice = (270 - $day) * 500;
                        join::where('uuid',$value->uuid)->update([
                            'price'=>$value->price + $addPrice,
                            'end'=>1
                        ]);
                        $log_point_spring = new log_point_spring;
                        $log_point_spring->uuid = $value->uuid;
                        $log_point_spring->point = $value->price;
                        $log_point_spring->type = 1;
                        $log_point_spring->new_point = $value->price + $addPrice;
                        $log_point_spring->add = 1;
                        $log_point_spring->save();
                    }else{
                        $addPoint = (11 - $day) * 500;
                        $addPrice = 260 * 500;
                        join::where('uuid',$value->uuid)->update([
                            'price'=>$value->price + $addPrice,
                            'end'=>1
                        ]);
                        $user = User::where('user_uuid',$value->uuid)->first();
                        User::where('user_uuid',$value->uuid)->update([
                            'user_point'=>$user->point + $addPoint
                            ]);
                        $log_point_spring = new log_point_spring;
                        $log_point_spring->uuid = $value->uuid;
                        $log_point_spring->point = $user->user_point;
                        $log_point_spring->type = 2;
                        $log_point_spring->new_point = $user->point + $addPoint;
                        $log_point_spring->add = 1;
                        $log_point_spring->save();
                    }
                }
            }
        }
    }
}
