<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SpringStart::class,
        \App\Console\Commands\SpringEnd::class,
        \App\Console\Commands\GiveDay::class,
        \App\Console\Commands\RtshObject::class,
        \App\Console\Commands\YearFee::class,
        \App\Console\Commands\PointOpen::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 春蚕
        // $schedule->command('spring:end')
        //     ->dailyAt('01:00')
        //     // ->everyMinute()
        //     ->sendOutputTo('/spring.log');
        $schedule->command('spring:start')
            ->dailyAt('01:30')
            // ->everyMinute()
            ->sendOutputTo('/spring.log');

        // 融通四海
        $schedule->command('rtsh:object')
            ->dailyAt('02:00')
            // ->everyMinute()
            ->sendOutputTo('/rtsh.log');

        // 每日赠送10
        $schedule->command('give:day')
            ->dailyAt('02:30')
            // ->everyMinute()
            ->sendOutputTo('/giveDay.log');

        // 年费检查
        $schedule->command('year:fee')
            ->dailyAt('03:00')
            // ->everyMinute()
            ->sendOutputTo('/yearFee.log');
        // 开拓积分到期检查
        $schedule->command('point:open')
            ->dailyAt('03:30')
            // ->everyMinute()
            ->sendOutputTo('/pointopen.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
