<?php

namespace App\Console\Commands;

use App\Jobs\FetchSlotsJob;
use App\Models\PeopleCase;
use App\Services\Inpol\InpolClient;
use Illuminate\Console\Command;
use function Psy\debug;


class CheckSlotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inpol:check-slots
                        {inpol-account-id? : The ID of the Inpol account to use}
                        {--case-type-id= : The type ID of the case to check, e.g. "9483073d-97fb-47e7-a126-8a5ce809e568"}
                        {--reservation-queue-id= : The ID of the reservation queue to check}
                        {--date= : The date to check for available slots, e.g. "Y-m-d"}
                        {--date-next-1days}
                        {--date-next-2days}
                        {--date-next-3days}
                        {--date-next-4days}
                        {--date-next-5days}
                        {--date-next-6weeks}
                        {--date-today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check available slots for a given date';

    /**
     * Execute the console command.
     *
     * @param InpolClient $client
     * @return void
     */
    public function handle(InpolClient $client): int
    {
        $options = $this->options();
       // $arguments = $this->arguments();
        $this->info('Checking dates...');
        //1 step: Check if the token is available
        if (!$client->getToken()) {
            $this->error('Login failed. ');
            return self::FAILURE;
        }
        $this->info('Login successful.');
        //2 step: Fetch people cases
        $caseId = $options['case-type-id'] ?? null;
        $peopleCases = $client->fetchCases(caseId: $caseId);
        $countCases = count($peopleCases);
        if (!$countCases) {
            $this->error('Failed to fetch cases.');
            return self::FAILURE;
        }
        $this->info( "$countCases cases.");
        foreach ($peopleCases as $peopleCase) {
            // 3 step: Fetch reservation queues
            $caseId = $peopleCase['id'];
            // type_id - from DB or ['type']['id'] - from API
            $typeId = $peopleCase['type_id'] ?? $peopleCase['type']['id'];
            $reservationQueues = $client->fetchReservationQueues($caseId, $typeId);
            if (!$reservationQueues) {
                $this->error('Failed to fetch reservation queues.');
                return self::FAILURE;
            }
                foreach ($reservationQueues as $reservationQueue) {
                    // local_id - from DB or 'id' - from API
                    $reservationQueueId = $reservationQueue['local_id'] ?? $reservationQueue['id'];
                    $this->info('Case for ' . $peopleCase['person'] . ' with type ID: ' . $typeId);
                     $this->info("Processing reservation queue: " . $reservationQueue['english_name']);
                    // 4 step: Fetch available dates
                    // $dates = $client->fetchDates($caseId, $reservationQueue['local_id']);
//                if (empty($dates)) {
//                    $this->warn('No available dates.');
//                } else {
                    $requestDate = $client->getQueueDate($options);

                    // 5 step: Fetch available dates
                   $res = FetchSlotsJob::dispatch($caseId, $reservationQueueId, $requestDate, $typeId)
                        ->delay(now()->addSeconds(rand(1, 5))); // rate limit
                }
        }
        $this->info('Jobs dispatched for slot fetching.');
        //$client->cleanUp();
        return self::SUCCESS;
    }
}
