<?php

use App\Http\Controllers\Api\Admin\ContestController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Jury\SubmissionReviewController;
use App\Http\Controllers\Api\Participant\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.basic', 'role:participant'])->prefix('participant')->group(function () {
    Route::get('submissions', [SubmissionController::class, 'index']);
    Route::get('submissions/{submission}', [SubmissionController::class, 'show']);
    Route::post('submissions', [SubmissionController::class, 'store']);
    Route::put('submissions/{submission}', [SubmissionController::class, 'update']);
    Route::post('submissions/{submission}/submit', [SubmissionController::class, 'submit']);
    Route::post('submissions/{submission}/comments', [SubmissionController::class, 'addComment']);
    Route::post('submissions/{submission}/attachments', [SubmissionController::class, 'uploadAttachment']);
    Route::get('attachments/{attachment}/download', [SubmissionController::class, 'downloadAttachment']);
});

Route::middleware(['auth.basic', 'role:jury'])->prefix('jury')->group(function () {
    Route::get('submissions', [SubmissionReviewController::class, 'index']);
    Route::get('submissions/{submission}', [SubmissionReviewController::class, 'show']);
    Route::post('submissions/{submission}/status', [SubmissionReviewController::class, 'changeStatus']);
    Route::post('submissions/{submission}/comments', [SubmissionReviewController::class, 'addComment']);
});

Route::middleware(['auth.basic', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('contests', [ContestController::class, 'index']);
    Route::post('contests', [ContestController::class, 'store']);
    Route::get('contests/{contest}', [ContestController::class, 'show']);
    Route::put('contests/{contest}', [ContestController::class, 'update']);
    Route::delete('contests/{contest}', [ContestController::class, 'destroy']);

    Route::get('users', [UserController::class, 'index']);
    Route::patch('users/{user}/role', [UserController::class, 'updateRole']);
});

