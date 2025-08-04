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
    protected $signature = 'inpol:check-slots
                        {inpol-account-id? : The ID of the Inpol account to use}
                        {--case-type-id= : The type ID of the case to check, e.g. "9483073d-97fb-47e7-a126-8a5ce809e568"}
                        {--reservation-queue-id= : The ID of the reservation queue to check}
                        {--date= : The date to check for available slots, e.g. "Y-m-d"}
                        {--date-next-2days}
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
    public function handle(InpolClient $client): void
    {
        $options = $this->options();
        $arguments = $this->arguments();
        $this->info('Checking dates...');
        //1 step: Check if the token is available
        if (!$client->getToken()) {
            $this->error('Login failed. ');
            return;
        }
        $this->info('Login successful.');
        //2 step: Fetch people cases
        $peopleCases = $client->fetchCases();
        $countCases = count($peopleCases);
        if (!$countCases) {
            $this->error('Failed to fetch cases.');
            return;
        }
        $this->info( "$countCases people cases received successful.");
        foreach ($peopleCases as $peopleCase) {
            // 3 step: Fetch reservation queues
            $caseId = $peopleCase['id'];
            $reservationQueues = $client->fetchReservationQueues($caseId, $peopleCase['type_id']);
            if (!$reservationQueues) {
                $this->error('Failed to fetch reservation queues.');
                return;
            }
            foreach ($reservationQueues as $reservationQueue) {
                $this->info("Processing reservation queue: " . $reservationQueue['english_name']);
                // 4 step: Fetch available dates
               // $dates = $client->fetchDates($caseId, $reservationQueue['local_id']);
//                if (empty($dates)) {
//                    $this->warn('No available dates.');
//                } else {
                    $requestDate = $client->getQueueDate($options);
                // 5 step: Fetch available dates
                    $slots = $client->fetchSlots($caseId, $reservationQueue['local_id'], $requestDate, $peopleCase['type_id'],);
                    if (empty($slots)) {
                        $this->warn('No available slots for the selected date.');
                    } else {
                        $this->info('Available slots: ' . count($slots));
                        foreach ($slots as $slot) {
                            $this->line('Slot: ' . $slot['id'] . ' at ' . $slot['date']);
                            $client->reserveRoomInQueue(
                                $caseId,
                                $reservationQueue['local_id'],
                                $slot['id'],
                            );
                        }
                    }

            //   }
            }
        }
    }
}
