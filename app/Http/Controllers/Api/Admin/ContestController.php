<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContestRequest;
use App\Http\Requests\Admin\UpdateContestRequest;
use App\Models\Contest;
use App\Services\ContestService;
use Illuminate\Http\JsonResponse;

class ContestController extends Controller
{
    public function __construct(
        protected ContestService $contestService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->contestService->all());
    }

    public function store(StoreContestRequest $request): JsonResponse
    {
        $contest = $this->contestService->create($request->validated());

        return response()->json($contest, 201);
    }

    public function show(Contest $contest): JsonResponse
    {
        return response()->json($contest);
    }

    public function update(UpdateContestRequest $request, Contest $contest): JsonResponse
    {
        $contest = $this->contestService->update($contest, $request->validated());

        return response()->json($contest);
    }

    public function destroy(Contest $contest): JsonResponse
    {
        $this->contestService->delete($contest);

        return response()->json(['deleted' => true]);
    }
}


