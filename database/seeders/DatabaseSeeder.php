<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User']
        );

        User::firstOrCreate(
            ['email' => 'andrei@andrei'],
            [
                'name' => 'andrei',
                'password' => Hash::make('andrei'),
            ]
        );

        if (Category::count() === 0) {
            $this->call([
                BikeProductSeeder::class,
            ]);
        }
    }
}
