<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()
            ->orderBy('id')
            ->get();

        return response()->json($users);
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        $user->role = $request->validated()['role'];
        $user->save();

        return response()->json($user);
    }
}


