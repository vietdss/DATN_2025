<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\DeleteUnconfirmedTransactions;
use App\Console\Commands\DeleteExpiredProducts;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        DeleteUnconfirmedTransactions::class,
        DeleteExpiredProducts::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:delete-unconfirmed-transactions')->daily();
        $schedule->command('products:delete-expired')->everyFiveMinutes();
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
