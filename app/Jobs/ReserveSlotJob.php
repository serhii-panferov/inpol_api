<?php

namespace App\Jobs;

use App\Models\PeopleCase;
use App\Services\Inpol\InpolClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReserveSlotJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $caseId,
        public string $queueId,
        public string $slotId,
        public string $slotDate
    ) {}

    /**
     * Execute the job.
     */
    public function handle(InpolClient $client): void
    {
        $personalData = $client->fetchPersonalDate($this->caseId);
        if (!$personalData) {
            logger()->error("Failed to fetch personal data for {$this->caseId}");
            return;
        }
        $success = false;
        for ($i = 0; $i < 3; $i++) {
            logger()->info("Attempting to reserve slot {$this->slotId} for case {$this->caseId}, attempt " . ($i + 1));
            $success = $client->reserveRoomInQueue($this->caseId, $this->queueId, $this->slotId, $personalData);
            if ($success) break;
            sleep(2);
        }
        if ($success) {
            PeopleCase::where('id', $this->caseId)->update(['status' => 5]);
            logger()->info("Reserved slot {$this->slotId} for case {$this->caseId}");
        } else {
            logger()->warning("Failed to reserve slot {$this->slotId} for case {$this->caseId}");
        }
    }
}
