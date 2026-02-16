<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserWebController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    public function index(): View
    {
        $users = $this->userService->all();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateRole($user, $request->validated()['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Роль пользователя успешно обновлена.');
    }
}

