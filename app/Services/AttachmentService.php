<?php

namespace App\Services;

use App\Jobs\ScanAttachmentJob;
use App\Models\Attachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AttachmentService
{
    protected const MAX_ATTACHMENTS_PER_SUBMISSION = 3;
    protected const MAX_FILE_SIZE_BYTES = 10 * 1024 * 1024; // 10MB
    protected const ALLOWED_EXTENSIONS = ['pdf', 'zip', 'png', 'jpg', 'jpeg'];

    public function upload(Submission $submission, User $user, UploadedFile $file): Attachment
    {
        if ($submission->user_id !== $user->id && ! $user->isAdmin()) {
            throw new RuntimeException('You are not allowed to upload attachments for this submission.');
        }

        $currentCount = $submission->attachments()->count();

        if ($currentCount >= self::MAX_ATTACHMENTS_PER_SUBMISSION) {
            throw new RuntimeException('Maximum attachments count reached for this submission.');
        }

        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new RuntimeException('File type not allowed.');
        }

        if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
            throw new RuntimeException('File size exceeds allowed limit.');
        }

        $storageKey = sprintf(
            'submissions/%d/%s_%s',
            $submission->id,
            uniqid(),
            $file->getClientOriginalName(),
        );

        try {
            Storage::disk('s3')->putFileAs(
                dirname($storageKey),
                $file,
                basename($storageKey)
            );
        } catch (\Exception $e) {
            \Log::error('S3 upload failed', [
                'error' => $e->getMessage(),
                'storage_key' => $storageKey,
                'config' => [
                    'endpoint' => config('filesystems.disks.s3.endpoint'),
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'region' => config('filesystems.disks.s3.region'),
                ],
            ]);
            throw new RuntimeException('Failed to upload file to S3: ' . $e->getMessage());
        }

        $attachment = $submission->attachments()->create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'storage_key' => $storageKey,
            'status' => Attachment::STATUS_PENDING,
        ]);

        ScanAttachmentJob::dispatch($attachment->id);

        return $attachment;
    }

    public function markScanned(Attachment $attachment): void
    {
        $attachment->status = Attachment::STATUS_SCANNED;
        $attachment->rejection_reason = null;
        $attachment->save();
    }

    public function reject(Attachment $attachment, string $reason): void
    {
        $attachment->status = Attachment::STATUS_REJECTED;
        $attachment->rejection_reason = $reason;
        $attachment->save();
    }
}


