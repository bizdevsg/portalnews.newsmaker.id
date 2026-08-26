<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('berita:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('pivot:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('newsmaker:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('pasar-indonesia:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('pasar-indonesia-regulasi:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('tiktok:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('video-briefing:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('popup-banner:cache-json')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('iklan:cache-json')->everyTenMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
