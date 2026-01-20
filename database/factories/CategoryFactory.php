<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Sport Motorbikes', 'Cruiser Motorbikes', 'Touring Motorbikes', 'Naked Motorbikes',
            'Adventure Motorbikes', 'Dual Sport Motorbikes', 'Sport Touring Motorbikes', 'Supermoto Motorbikes',
            'Off Road Motorbikes', 'Enduro Motorbikes', 'Motocross Motorbikes', 'Trial Motorbikes',
            'Electric Motorbikes', 'Hybrid Motorbikes', 'City Motorbikes', 'Scooter Motorbikes',
            'Racing Motorbikes', 'Track Motorbikes', 'Vintage Motorbikes', 'Classic Motorbikes',
            'Engine Parts', 'Exhaust Systems', 'Air Filters', 'Fuel Systems',
            'Transmission', 'Brakes', 'Suspension', 'Wheels',
            'Tires', 'Chains', 'Sprockets', 'Handlebars',
            'Seats', 'Body Work', 'Fairings', 'Lights',
            'Helmets', 'Jackets', 'Gloves', 'Boots',
            'Pants', 'Armor', 'Goggles', 'Protection',
            'Maintenance', 'Tools', 'Lubricants', 'Cleaners',
            'Batteries', 'Electrical', 'Accessories', 'Luggage',
            'Storage', 'Locks', 'Covers', 'Stands',
        ]);

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }
}
