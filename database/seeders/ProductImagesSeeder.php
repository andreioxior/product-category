<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Adding images to existing products...');

        $images = [
            'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1541625602330-2277a4c46182?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1507035895480-2b3156c3113a?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558059815-451b67423313?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1544191696-102dbdaeeaa0?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=400&h=400&fit=crop',
        ];

        $products = Product::whereNull('image')->get();

        foreach ($products as $product) {
            $product->image = $images[array_rand($images)];
            $product->save();
        }

        $this->command->info("Added images to {$products->count()} products!");
        $this->command->info('Total Products with images: '.Product::whereNotNull('image')->count());
    }
}
