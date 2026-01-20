<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BikeProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding bike products...');

        $this->command->info('Creating 50 categories...');
        Category::factory()->count(50)->create();

        $this->command->info('Creating 500 products...');
        Product::factory()->count(500)->create();

        $this->command->info('Bike product seeding completed!');
        $this->command->info('Total Categories: '.Category::count());
        $this->command->info('Total Products: '.Product::count());
    }
}
