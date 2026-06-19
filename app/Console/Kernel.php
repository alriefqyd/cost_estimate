<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:update-currency-usd')->cron('00 07 * * *');
        $schedule->command('app:delete-old-wbs')->daily();
        $schedule->command('app:delete-old-estimates')->cron('00 03 * * *');
        $schedule->command('app:send-reviewer-reminders')->cron('00 08 * * *');
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
