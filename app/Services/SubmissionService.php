<?php

namespace App\Services;

use App\Jobs\NotifyStatusChangedJob;
use App\Models\Attachment;
use App\Models\Contest;
use App\Models\Submission;
use App\Models\User;
use Carbon\CarbonImmutable;
use RuntimeException;

class SubmissionService
{
    public function create(User $user, array $data): Submission
    {
        if (! $user->isParticipant() && ! $user->isAdmin()) {
            throw new RuntimeException('Only participants can create submissions.');
        }

        /** @var Contest $contest */
        $contest = Contest::query()->findOrFail($data['contest_id']);

        if (! $contest->is_active) {
            throw new RuntimeException('Contest is not active.');
        }

        if (CarbonImmutable::now()->greaterThan($contest->deadline_at)) {
            throw new RuntimeException('Contest deadline has passed.');
        }

        return Submission::query()->create([
            'contest_id' => $contest->id,
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => Submission::STATUS_DRAFT,
        ]);
    }

    public function update(Submission $submission, User $user, array $data): Submission
    {
        if (! $this->canEdit($submission, $user)) {
            throw new RuntimeException('Submission cannot be edited in current status.');
        }

        $submission->fill([
            'title' => $data['title'] ?? $submission->title,
            'description' => $data['description'] ?? $submission->description,
        ]);

        $submission->save();

        return $submission;
    }

    public function submit(Submission $submission, User $user): Submission
    {
        if ($submission->user_id !== $user->id && ! $user->isAdmin()) {
            throw new RuntimeException('You are not allowed to submit this submission.');
        }

        if (! in_array($submission->status, [Submission::STATUS_DRAFT, Submission::STATUS_NEEDS_FIX], true)) {
            throw new RuntimeException('Submission can only be submitted from draft or needs_fix.');
        }

        $scannedCount = $submission->attachments()
            ->where('status', Attachment::STATUS_SCANNED)
            ->count();

        if ($scannedCount < 1) {
            throw new RuntimeException('At least one scanned attachment is required to submit.');
        }

        $oldStatus = $submission->status;
        $submission->status = Submission::STATUS_SUBMITTED;
        $submission->save();

        NotifyStatusChangedJob::dispatch($submission->id, $oldStatus, $submission->status);

        return $submission;
    }

    public function changeStatus(Submission $submission, User $user, string $newStatus): Submission
    {
        if (! $user->isJury() && ! $user->isAdmin()) {
            throw new RuntimeException('Only jury or admin can change submission status.');
        }

        if (! in_array($newStatus, [
            Submission::STATUS_DRAFT,
            Submission::STATUS_SUBMITTED,
            Submission::STATUS_NEEDS_FIX,
            Submission::STATUS_ACCEPTED,
            Submission::STATUS_REJECTED,
        ], true)) {
            throw new RuntimeException('Invalid status.');
        }

        $allowed = $this->allowedTransitions()[$submission->status] ?? [];

        if (! in_array($newStatus, $allowed, true)) {
            throw new RuntimeException('Status transition not allowed.');
        }

        $oldStatus = $submission->status;
        $submission->status = $newStatus;
        $submission->save();

        NotifyStatusChangedJob::dispatch($submission->id, $oldStatus, $newStatus);

        return $submission;
    }

    public function addComment(Submission $submission, User $user, string $body)
    {
        if ($submission->user_id !== $user->id && ! $user->isJury() && ! $user->isAdmin()) {
            throw new RuntimeException('You are not allowed to comment on this submission.');
        }

        return $submission->comments()->create([
            'user_id' => $user->id,
            'body' => $body,
        ]);
    }

    protected function canEdit(Submission $submission, User $user): bool
    {
        if ($submission->user_id !== $user->id && ! $user->isAdmin()) {
            return false;
        }

        return in_array($submission->status, [
            Submission::STATUS_DRAFT,
            Submission::STATUS_NEEDS_FIX,
        ], true);
    }

    /**
     * @return array<string, list<string>>
     */
    protected function allowedTransitions(): array
    {
        return [
            Submission::STATUS_DRAFT => [
                Submission::STATUS_SUBMITTED,
            ],
            Submission::STATUS_SUBMITTED => [
                Submission::STATUS_ACCEPTED,
                Submission::STATUS_REJECTED,
                Submission::STATUS_NEEDS_FIX,
            ],
            Submission::STATUS_NEEDS_FIX => [
                Submission::STATUS_SUBMITTED,
                Submission::STATUS_REJECTED,
            ],
            Submission::STATUS_ACCEPTED => [],
            Submission::STATUS_REJECTED => [],
        ];
    }
}


