<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'jury@example.com'],
            [
                'name' => 'Jury User',
                'password' => Hash::make('password'),
                'role' => 'jury',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'participant@example.com'],
            [
                'name' => 'Participant User',
                'password' => Hash::make('password'),
                'role' => 'participant',
            ]
        );
    }
}
