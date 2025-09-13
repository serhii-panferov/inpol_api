<?php

namespace App\Console\Commands\Log;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ClearLogFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //TODO udate to use Storage facade
        // Clear the log files and select what to clear
        $result = Process::run('echo "" > storage/logs/laravel.log && echo "" > storage/logs/cron.log');
        if ($result->successful()) {
            echo $result->output();
        } else {
            echo $result->errorOutput();
        }
    }
}
