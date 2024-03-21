<?php

namespace App\Console;

use App\Console\Commands\Order\ClearDeletedOrderDraftsCommand;
use App\Console\Commands\Order\OrderTimeoutNotificationPusherCommand;
use App\Console\Commands\User\UserOnlineCacheFlusherCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use function base_path;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(UserOnlineCacheFlusherCommand::class)->everyFifteenMinutes();
        $schedule->command(ClearDeletedOrderDraftsCommand::class)->daily();
        $schedule->command(OrderTimeoutNotificationPusherCommand::class)->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}