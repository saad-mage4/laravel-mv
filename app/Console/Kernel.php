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
        \App\Console\Commands\CacheRemoveCommand::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call('App\Http\Controllers\Seller\WithdrawController@automateWithdraw')
        //     ->everyMinute()->timezone('Asia/Karachi');

        $schedule->call('App\Http\Controllers\Seller\SellerSponsorController@bannerRemoveCron')
        ->everyMinute()->timezone('Asia/Karachi');

        // For Private Seller Ads Cron job montly/2months
        $schedule->call('App\Http\Controllers\User\StripeController@PrivateAdsCron')
        ->everyMinute()->timezone('Asia/Karachi');
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