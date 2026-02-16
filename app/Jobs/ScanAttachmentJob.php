<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $attachmentId
    ) {
    }

    public function handle(AttachmentService $attachmentService): void
    {
        $attachment = Attachment::query()->find($this->attachmentId);

        if (! $attachment) {
            return;
        }

        $reason = null;

        $extension = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'zip', 'png', 'jpg', 'jpeg'];

        if (! in_array($extension, $allowedExtensions, true)) {
            $reason = 'File type is not allowed.';
        } elseif ($attachment->size > 10 * 1024 * 1024) {
            $reason = 'File size exceeds 10MB.';
        } elseif (mb_strlen($attachment->original_name) > 255) {
            $reason = 'File name is too long.';
        }

        if ($reason !== null) {
            $attachmentService->reject($attachment, $reason);
        } else {
            $attachmentService->markScanned($attachment);
        }
    }
}


