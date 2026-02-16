<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Http\Requests\UploadAttachmentRequest;
use App\Models\Attachment;
use App\Models\Contest;
use App\Models\Submission;
use App\Services\AttachmentService;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmissionWebController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
        protected AttachmentService $attachmentService,
    ) {
    }

    public function create(): View
    {
        $contests = Contest::query()
            ->where('is_active', true)
            ->where('deadline_at', '>', now())
            ->orderBy('deadline_at')
            ->get();

        return view('participant.submissions.create', compact('contests'));
    }

    public function store(StoreSubmissionRequest $request): RedirectResponse
    {
        $submission = $this->submissionService->create(
            $request->user(),
            $request->validated(),
        );

        return redirect()
            ->route('participant.submissions.show', $submission)
            ->with('status', 'Заявка успешно создана.');
    }

    public function show(Submission $submission): View
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        $submission->load(['contest', 'attachments', 'comments.user']);

        return view('participant.submissions.show', compact('submission'));
    }

    public function edit(Submission $submission): View
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        if (! in_array($submission->status, [Submission::STATUS_DRAFT, Submission::STATUS_NEEDS_FIX], true)) {
            abort(403, 'Заявку можно редактировать только в статусах draft или needs_fix.');
        }

        $contests = Contest::query()
            ->where('is_active', true)
            ->orderBy('deadline_at')
            ->get();

        return view('participant.submissions.edit', compact('submission', 'contests'));
    }

    public function update(UpdateSubmissionRequest $request, Submission $submission): RedirectResponse
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        $this->submissionService->update(
            $submission,
            $request->user(),
            $request->validated(),
        );

        return redirect()
            ->route('participant.submissions.show', $submission)
            ->with('status', 'Заявка обновлена.');
    }

    public function submit(Submission $submission): RedirectResponse
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        $this->submissionService->submit($submission, auth()->user());

        return redirect()
            ->route('participant.submissions.show', $submission)
            ->with('status', 'Заявка отправлена на проверку.');
    }

    public function uploadAttachment(UploadAttachmentRequest $request, Submission $submission): RedirectResponse
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        $this->attachmentService->upload(
            $submission,
            $request->user(),
            $request->file('file'),
        );

        return redirect()
            ->route('participant.submissions.show', $submission)
            ->with('status', 'Файл загружен и отправлен на проверку.');
    }

    public function addComment(StoreCommentRequest $request, Submission $submission): RedirectResponse
    {
        abort_if($submission->user_id !== auth()->id(), 403);

        $this->submissionService->addComment(
            $submission,
            $request->user(),
            $request->validated()['body'],
        );

        return redirect()
            ->route('participant.submissions.show', $submission)
            ->with('status', 'Комментарий добавлен.');
    }

    public function downloadAttachment(Attachment $attachment): RedirectResponse
    {
        abort_if($attachment->user_id !== auth()->id() && ! auth()->user()->isJury() && ! auth()->user()->isAdmin(), 403);

        $url = \Storage::disk('s3')->temporaryUrl(
            $attachment->storage_key,
            now()->addMinutes(5)
        );

        return redirect($url);
    }
}

