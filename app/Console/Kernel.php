<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telescope:prune')->daily();

        // The refresh interval is configured in seconds; we need minutes
        $minutes = floor(Config::get('happystack.status_refresh_interval') / 60);
        $cron = "*/{$minutes} * * * *";

        $schedule
            ->command('happy:refresh-statuses')
            ->cron($cron)
            ->runInBackground()
            ->withoutOverlapping()
            ->thenPingHoneybadger('OaIKBK', 'production');
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
