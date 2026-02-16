<?php

namespace App\Jobs;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyStatusChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $submissionId,
        protected string $oldStatus,
        protected string $newStatus,
    ) {
    }

    public function handle(): void
    {
        $submission = Submission::with('user')->find($this->submissionId);

        if (! $submission) {
            return;
        }

        Log::info('Submission status changed', [
            'submission_id' => $submission->id,
            'contest_id' => $submission->contest_id,
            'user_id' => $submission->user_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ]);
    }
}


