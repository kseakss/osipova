<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    /**
     * @return Collection<int, User>
     */
    public function all(): Collection
    {
        return User::query()
            ->orderBy('id')
            ->get();
    }

    public function updateRole(User $user, string $role): User
    {
        $user->role = $role;
        $user->save();

        return $user;
    }
}

