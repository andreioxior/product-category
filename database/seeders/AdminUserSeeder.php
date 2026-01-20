<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'andrei@admin.com'],
            [
                'name' => 'Andrei',
                'email' => 'andrei@admin.com',
                'password' => 'andrei',
                'email_verified_at' => now(),
            ]
        );
    }
}
