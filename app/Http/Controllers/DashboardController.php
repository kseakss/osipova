<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function participant(Request $request)
    {
        $user = $request->user();

        $submissions = Submission::with('contest')
            ->forUser($user)
            ->orderByDesc('created_at')
            ->get();

        $contests = Contest::query()
            ->where('is_active', true)
            ->orderBy('deadline_at')
            ->get();

        return view('participant.dashboard', compact('submissions', 'contests'));
    }

    public function jury()
    {
        $submissions = Submission::with(['contest', 'user'])
            ->orderByDesc('created_at')
            ->get();

        return view('jury.dashboard', compact('submissions'));
    }

    public function admin()
    {
        $contests = Contest::query()
            ->orderByDesc('created_at')
            ->get();

        $users = User::query()
            ->orderBy('id')
            ->get();

        return view('admin.dashboard', compact('contests', 'users'));
    }
}


