<?php

namespace App\Http\Controllers\Api\Jury;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeSubmissionStatusRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionReviewController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
    ) {
    }

    public function index(): JsonResponse
    {
        $submissions = Submission::query()
            ->with(['contest', 'user', 'attachments', 'comments.user'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($submissions);
    }

    public function show(Submission $submission): JsonResponse
    {
        $submission->load(['contest', 'user', 'attachments', 'comments.user']);

        return response()->json($submission);
    }

    public function changeStatus(ChangeSubmissionStatusRequest $request, Submission $submission): JsonResponse
    {
        $submission = $this->submissionService->changeStatus(
            $submission,
            $request->user(),
            $request->validated()['status'],
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
}


