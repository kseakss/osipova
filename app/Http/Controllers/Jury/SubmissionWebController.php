<?php

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeSubmissionStatusRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Attachment;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmissionWebController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
    ) {
    }

    public function show(Submission $submission): View
    {
        $submission->load(['contest', 'user', 'attachments', 'comments.user']);

        return view('jury.submissions.show', compact('submission'));
    }

    public function changeStatus(ChangeSubmissionStatusRequest $request, Submission $submission): RedirectResponse
    {
        $this->submissionService->changeStatus(
            $submission,
            $request->user(),
            $request->validated()['status'],
        );

        return redirect()
            ->route('jury.submissions.show', $submission)
            ->with('status', 'Статус заявки изменён.');
    }

    public function addComment(StoreCommentRequest $request, Submission $submission): RedirectResponse
    {
        $this->submissionService->addComment(
            $submission,
            $request->user(),
            $request->validated()['body'],
        );

        return redirect()
            ->route('jury.submissions.show', $submission)
            ->with('status', 'Комментарий добавлен.');
    }

    public function downloadAttachment(Attachment $attachment): RedirectResponse
    {
        abort_if(! auth()->user()->isJury() && ! auth()->user()->isAdmin(), 403);

        $url = \Storage::disk('s3')->temporaryUrl(
            $attachment->storage_key,
            now()->addMinutes(5)
        );

        return redirect($url);
    }
}

