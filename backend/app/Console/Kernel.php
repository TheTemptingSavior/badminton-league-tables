<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\UpdateScoreboards::class,
        \App\Console\Commands\UpdateScoreboard::class,
        \App\Console\Commands\ImportData::class,
        \App\Console\Commands\CreateSeasons::class,
        \App\Console\Commands\CreateUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:create-seasons')
            ->dailyAt('01:00');
    }
}
