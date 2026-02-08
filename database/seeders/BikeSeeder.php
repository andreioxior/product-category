<?php

namespace Database\Seeders;

use App\Models\Bike;
use Illuminate\Database\Seeder;

class BikeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding bikes...');

        $bikes = Bike::factory()->count(100)->make();

        foreach ($bikes as $bike) {
            Bike::firstOrCreate(
                [
                    'manufacturer' => $bike->manufacturer,
                    'model' => $bike->model,
                    'year' => $bike->year,
                ],
                [
                    'is_active' => $bike->is_active,
                ]
            );
        }

        $this->command->info('Bike seeding completed!');
        $this->command->info('Total Bikes: '.Bike::count());
    }
}
