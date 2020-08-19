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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('insert_user_info')->everyFiveMinutes()->runInBackground(); // 更新粉丝基础信息
//        $schedule->command('insert_user_openid')->everyFiveMinutes()->runInBackground();// 更新粉丝id
//        $schedule->command('amend_user_count')->hourly()->appendOutputTo(storage_path('logs/amend_user_count' . date('Y-m') . '.log'))
//            ->runInBackground(); // 如果数量对不上，拉取新的(废弃)

        $schedule->command('send_service_msg')->everyMinute()->appendOutputTo(storage_path('logs/send_service_msg' . date('Y-m') . '.log'))
            ->runInBackground(); // 发送客服消息
        $schedule->command('update_active_fans')->everyMinute()->appendOutputTo(storage_path('logs/update_active_fans' . date('Y-m') . '.log'))
            ->runInBackground(); // 更新活跃粉丝
        $schedule->command('update_user_info')->everyMinute()->appendOutputTo(storage_path('logs/update_user_info' . date('Y-m') . '.log'))
            ->runInBackground(); // 实时更新用户基本信息
        $schedule->command('update_userday_total')->twiceDaily(1, 13)->appendOutputTo(storage_path('logs/update_userday_total' . date('Y-m') . '.log'))
            ->runInBackground(); // 每日更新两次粉丝情况
        $schedule->command('update_total_active')->hourly()->appendOutputTo(storage_path('logs/update_total_active' . date('Y-m') . '.log'))
            ->runInBackground(); // 每小时更新一次总粉数据
        $schedule->command('update_platform_data')->everyFiveMinutes()->appendOutputTo(storage_path('logs/update_platform_data' . date('Y-m') . '.log'))
            ->runInBackground(); // 更新小说平台的名称
        $schedule->command('update_active_user')->dailyAt('00:05')->appendOutputTo(storage_path('logs/update_active_user' . date('Y-m') . '.log'))
            ->runInBackground(); // 每日更新活跃用户数据
        $schedule->command('update_account_pwd')->everyTenMinutes()->appendOutputTo(storage_path('logs/update_account_pwd' . date('Y-m') . '.log'))
            ->runInBackground(); // 更新投放账号密码
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
