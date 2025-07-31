<?php

namespace App\Console\Commands;

use App\Services\Inpol\InpolClient;
use Illuminate\Console\Command;


class CheckSlotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inpol:check-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check available slots for a given date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $this->info('Checking slots...');
        $client = new InpolClient();
        //1 step: Check if the token is available
        if (!$client->getToken()) {
            $this->error('Login failed. ');
            return;
        }
        $this->info('Login successful.');
        //2 step: Fetch people cases
        $countCases = $client->fetchCases();
        if (!$countCases) {
            $this->error('Failed to fetch cases.');
            return;
        }
        $this->info($countCases . ' people cases received successful.');
        $slots = $client->fetchSlots();
        if (empty($slots)) {
            $this->warn('No available slots.');
        } else {
            foreach ($slots as $slot) {
                $this->line("- " . $slot);
            }
        }
    }
}
