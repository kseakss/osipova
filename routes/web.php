<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isJury()) {
            return redirect()->route('jury.dashboard');
        }

        return redirect()->route('participant.dashboard');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:participant')->prefix('participant')->name('participant.')->group(function () {
        Route::get('/', [DashboardController::class, 'participant'])->name('dashboard');
        Route::get('/submissions', [DashboardController::class, 'participant'])->name('submissions.index');

        Route::get('/submissions/create', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'create'])
            ->name('submissions.create');
        Route::post('/submissions', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'store'])
            ->name('submissions.store');
        Route::get('/submissions/{submission}', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'show'])
            ->name('submissions.show');
        Route::get('/submissions/{submission}/edit', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'edit'])
            ->name('submissions.edit');
        Route::put('/submissions/{submission}', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'update'])
            ->name('submissions.update');
        Route::post('/submissions/{submission}/submit', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'submit'])
            ->name('submissions.submit');
        Route::post('/submissions/{submission}/attachments', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'uploadAttachment'])
            ->name('submissions.uploadAttachment');
        Route::post('/submissions/{submission}/comments', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'addComment'])
            ->name('submissions.addComment');
        Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\Participant\SubmissionWebController::class, 'downloadAttachment'])
            ->name('submissions.downloadAttachment');
    });

    Route::middleware('role:jury')->prefix('jury')->name('jury.')->group(function () {
        Route::get('/', [DashboardController::class, 'jury'])->name('dashboard');

        Route::get('/submissions/{submission}', [\App\Http\Controllers\Jury\SubmissionWebController::class, 'show'])
            ->name('submissions.show');
        Route::patch('/submissions/{submission}/status', [\App\Http\Controllers\Jury\SubmissionWebController::class, 'changeStatus'])
            ->name('submissions.changeStatus');
        Route::post('/submissions/{submission}/comments', [\App\Http\Controllers\Jury\SubmissionWebController::class, 'addComment'])
            ->name('submissions.addComment');
        Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\Jury\SubmissionWebController::class, 'downloadAttachment'])
            ->name('submissions.downloadAttachment');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

        Route::get('/contests', [\App\Http\Controllers\Admin\ContestWebController::class, 'index'])
            ->name('contests.index');
        Route::post('/contests', [\App\Http\Controllers\Admin\ContestWebController::class, 'store'])
            ->name('contests.store');
        Route::get('/contests/{contest}/edit', [\App\Http\Controllers\Admin\ContestWebController::class, 'edit'])
            ->name('contests.edit');
        Route::put('/contests/{contest}', [\App\Http\Controllers\Admin\ContestWebController::class, 'update'])
            ->name('contests.update');
        Route::delete('/contests/{contest}', [\App\Http\Controllers\Admin\ContestWebController::class, 'destroy'])
            ->name('contests.destroy');

        Route::get('/users', [\App\Http\Controllers\Admin\UserWebController::class, 'index'])
            ->name('users.index');
        Route::patch('/users/{user}/role', [\App\Http\Controllers\Admin\UserWebController::class, 'updateRole'])
            ->name('users.updateRole');
    });
});

