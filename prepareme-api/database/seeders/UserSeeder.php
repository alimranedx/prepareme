<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@prepareme.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::ADMIN,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Demo Candidate 1
        User::updateOrCreate(
            ['email' => 'user1@prepareme.com'],
            [
                'name' => 'Al-Amin Hossain',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::USER,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Demo Candidate 2
        User::updateOrCreate(
            ['email' => 'user2@prepareme.com'],
            [
                'name' => 'Nusrat Jahan',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::USER,
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        // Demo Blocked Account (for testing rejection)
        User::updateOrCreate(
            ['email' => 'blocked@prepareme.com'],
            [
                'name' => 'Suspended Candidate',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::USER,
                'status' => UserStatus::BLOCKED,
                'email_verified_at' => now(),
            ]
        );
    }
}
