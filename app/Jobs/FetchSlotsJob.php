<?php

namespace App\Jobs;

use App\Services\Inpol\InpolClient;
use Illuminate\Container\Attributes\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchSlotsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $caseId,
        public string $queueId,
        public string $date,
        public string $typeId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(InpolClient $client): void
    {
        $slots = $client->fetchSlots($this->caseId, $this->queueId, $this->date, $this->typeId);
        if (empty($slots)) {
            Log::warning("No slots for case {$this->caseId}");
            return;
        }
        Log::info("Found " . count($slots) . " slots for case {$this->caseId}");
        foreach ($slots as $slot) {
            ReserveSlotJob::dispatch($this->caseId, $this->queueId, $slot['id'], $slot['date'])
                ->delay(now()->addSeconds(rand(1, 3)));
        }
    }
}
