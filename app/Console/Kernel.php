<?php

namespace App\Console;

use App\Components\AgentManager;
use App\Components\GoodsBenefitManager;
use App\Components\OrderManager;
use App\Components\UserCouponManager;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //每分钟执行
        $schedule->call(function () {
            //活动检测
            GoodsBenefitManager::checkStatus();
            //订单支付状态检测
            OrderManager::check_pay_all();
        })->everyMinute();

        //每小时执行
        $schedule->call(function () {
            AgentManager::doCash_all();
            UserCouponManager::checkCoupons();
        })->hourly();

        //每天凌晨3点执行
        $schedule->call(function () {
            //订单快递状态检测
            OrderManager::check_postage_all();
        })->dailyAt('3');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
