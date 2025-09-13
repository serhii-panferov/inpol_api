<?php

namespace App\Console;

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
//        $schedule->command('inpol:check-slots --date-next-6weeks --case-type-id=37595c79-032f-4cf6-a72c-159e801f5efb')
//            ->everyMinute()
//            //->withoutOverlapping()
//            ->appendOutputTo(storage_path('logs/scheduler.log'));
////        $schedule->command('inpol:check-slots')
////            ->everyMinute()
//////            ->withOption('case-type-id', '37595c79-032f-4cf6-a72c-159e801f5efb')
//////            ->withOption('date-next-6weeks', true)
////            ->appendOutputTo(storage_path('logs/scheduler.log'));
///
///
        $schedule->command('inpol:check-slots')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/scheduler.log'))
            ->withOption('case-type-id', '37595c79-032f-4cf6-a72c-159e801f5efb')
            ->withOption('date-next-6weeks', true);
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

