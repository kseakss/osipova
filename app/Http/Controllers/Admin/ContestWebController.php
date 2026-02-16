<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContestRequest;
use App\Http\Requests\Admin\UpdateContestRequest;
use App\Models\Contest;
use App\Services\ContestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContestWebController extends Controller
{
    public function __construct(
        protected ContestService $contestService,
    ) {
    }

    public function index(): View
    {
        $contests = $this->contestService->all();

        return view('admin.contests.index', compact('contests'));
    }

    public function store(StoreContestRequest $request): RedirectResponse
    {
        $this->contestService->create($request->validated());

        return redirect()
            ->route('admin.contests.index')
            ->with('status', 'Конкурс успешно создан.');
    }

    public function edit(Contest $contest): View
    {
        return view('admin.contests.edit', compact('contest'));
    }

    public function update(UpdateContestRequest $request, Contest $contest): RedirectResponse
    {
        $this->contestService->update($contest, $request->validated());

        return redirect()
            ->route('admin.contests.index')
            ->with('status', 'Конкурс обновлён.');
    }

    public function destroy(Contest $contest): RedirectResponse
    {
        $this->contestService->delete($contest);

        return redirect()
            ->route('admin.contests.index')
            ->with('status', 'Конкурс удалён.');
    }
}


