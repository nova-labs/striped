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
        $schedule->command('novalabs:webhookToRaw')
            ->everyFiveMinutes();

        $schedule->command('novalabs:stripeinvoiceparse')
            ->everyFiveMinutes();
        $schedule->command('novalabs:stripeSubscriptionParse')
            ->everyFifteenMinutes();
        $schedule->command('novalabs:stripepaymentparse')
            ->everyFifteenMinutes();
        $schedule->command('novalabs:striperefundparse')
            ->everyFifteenMinutes();

        
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
