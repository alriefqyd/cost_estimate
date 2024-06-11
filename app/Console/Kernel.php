<?php

namespace App\Console;

use App\Http\Controllers\EstimateAllDisciplineController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WorkBreakdownStructureController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function (){
            $settingController = new SettingController();
            $settingController->updateCurrencyUsd();
        })->cron('00 07 * * *');

        $schedule->call(function (){
            $wbsController = new WorkBreakdownStructureController();
            $wbsController->deleteWbsLevel3MoreOneMonth();
        })->daily();

        $schedule->call(function (){
            $estimateController = new EstimateAllDisciplineController();
            $estimateController->deleteEstimateDisciplineMoreOneMonth();
        })->cron('00 03 * * *');
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
