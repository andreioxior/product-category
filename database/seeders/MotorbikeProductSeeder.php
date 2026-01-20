<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MotorbikeProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding motorbike products...');

        $this->command->warn('Deleting existing data...');
        Product::query()->delete();
        Category::query()->delete();

        $this->command->info('Creating 50 categories...');
        Category::factory()->count(50)->create();

        $this->command->info('Creating 500 products...');
        Product::factory()->count(500)->create();

        $this->command->info('Motorbike product seeding completed!');
        $this->command->info('Total Categories: '.Category::count());
        $this->command->info('Total Products: '.Product::count());
    }
}
