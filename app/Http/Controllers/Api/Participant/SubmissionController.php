<?php

namespace App\Http\Controllers\Api\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Http\Requests\UploadAttachmentRequest;
use App\Models\Attachment;
use App\Models\Submission;
use App\Services\AttachmentService;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
        protected AttachmentService $attachmentService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $submissions = Submission::query()
            ->with(['contest', 'attachments', 'comments'])
            ->forUser($request->user())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($submissions);
    }

    public function show(Request $request, Submission $submission): JsonResponse
    {
        abort_if($submission->user_id !== $request->user()->id, 403);

        $submission->load(['contest', 'attachments', 'comments.user']);

        return response()->json($submission);
    }

    public function store(StoreSubmissionRequest $request): JsonResponse
    {
        $submission = $this->submissionService->create(
            $request->user(),
            $request->validated(),
        );

        return response()->json($submission, 201);
    }

    public function update(UpdateSubmissionRequest $request, Submission $submission): JsonResponse
    {
        $submission = $this->submissionService->update(
            $submission,
            $request->user(),
            $request->validated(),
        );

        return response()->json($submission);
    }

    public function submit(Request $request, Submission $submission): JsonResponse
    {
        $submission = $this->submissionService->submit(
            $submission,
            $request->user(),
        );

        return response()->json($submission);
    }

    public function addComment(StoreCommentRequest $request, Submission $submission): JsonResponse
    {
        $comment = $this->submissionService->addComment(
            $submission,
            $request->user(),
            $request->validated()['body'],
        );

        return response()->json($comment, 201);
    }

    public function uploadAttachment(UploadAttachmentRequest $request, Submission $submission): JsonResponse
    {
        $attachment = $this->attachmentService->upload(
            $submission,
            $request->user(),
            $request->file('file'),
        );

        return response()->json($attachment, 201);
    }

    public function downloadAttachment(Request $request, Attachment $attachment): JsonResponse
    {
        $user = $request->user();

        if ($attachment->user_id !== $user->id && ! $user->isJury() && ! $user->isAdmin()) {
            abort(403);
        }

        $url = \Storage::disk('s3')->temporaryUrl(
            $attachment->storage_key,
            now()->addMinutes(5)
        );

        return response()->json(['url' => $url]);
    }
}


