<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CreateBikeProductImages extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating sample bike product images...');

        // Create sample SVG-based images for testing
        $bikeImages = [
            'mountain-bike-001.svg' => $this->createMountainBikeSvg(),
            'road-bike-001.svg' => $this->createRoadBikeSvg(),
            'electric-bike-001.svg' => $this->createElectricBikeSvg(),
            'commuter-bike-001.svg' => $this->createCommuterBikeSvg(),
            'cruiser-bike-001.svg' => $this->createCruiserBikeSvg(),
            'hybrid-bike-001.svg' => $this->createHybridBikeSvg(),
        ];

        $storage = Storage::disk('products');

        foreach ($bikeImages as $filename => $svgContent) {
            $storage->put("original/{$filename}", $svgContent);
        }

        // Update some products to use these local images
        $products = Product::inRandomOrder()->limit(count($bikeImages))->get();
        foreach ($products as $product) {
            if (! $product->image_hosted_locally) {
                $product->image_local_path = array_rand($bikeImages);
                $product->image_hosted_locally = true;
                $product->image_synced_at = now();
                $product->save();
            }
        }

        $this->command->info('Created '.count($bikeImages).' sample bike images');
        $this->command->info('Updated '.$products->count().' products with local images');
    }

    public function createMountainBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#e3f2fd"/>
            <g transform="translate(200,200)">
                <!-- Bike Frame -->
                <ellipse cx="0" cy="-30" rx="80" ry="60" fill="none" stroke="#2d3748" stroke-width="4"/>
                <!-- Wheels -->
                <circle cx="-60" cy="50" r="25" fill="#1e40af" stroke="#2d3748" stroke-width="3"/>
                <circle cx="60" cy="50" r="25" fill="#1e40af" stroke="#2d3748" stroke-width="3"/>
                <!-- Seat -->
                <rect x="-20" y="-10" width="40" height="20" fill="#64748b"/>
                <!-- Handlebars -->
                <rect x="-50" y="-60" width="8" height="60" fill="#2d3748"/>
                <rect x="42" y="-60" width="8" height="60" fill="#2d3748"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="18">Mountain Bike</text>
        </svg>';
    }

    public function createRoadBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#f97316"/>
            <g transform="translate(200,200)">
                <!-- Frame -->
                <ellipse cx="0" cy="-20" rx="100" ry="50" fill="none" stroke="#dc2626" stroke-width="4"/>
                <!-- Wheels -->
                <circle cx="-80" cy="60" r="28" fill="#eab308" stroke="#dc2626" stroke-width="3"/>
                <circle cx="80" cy="60" r="28" fill="#eab308" stroke="#dc2626" stroke-width="3"/>
                <!-- Handlebars -->
                <path d="M-60,-60 L-40,-80" stroke="#dc2626" stroke-width="4" fill="none"/>
                <path d="M60,-60 L40,-80" stroke="#dc2626" stroke-width="4" fill="none"/>
                <!-- Seat -->
                <rect x="-25" y="-5" width="50" height="15" fill="#3b82f6"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="18">Road Bike</text>
        </svg>';
    }

    public function createElectricBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#10b981"/>
            <g transform="translate(200,200)">
                <!-- Frame -->
                <rect x="-80" y="-30" width="160" height="60" rx="10" fill="#374151" stroke="#2d3748" stroke-width="3"/>
                <!-- Battery -->
                <rect x="-20" y="-10" width="40" height="20" fill="#60a5fa"/>
                <text x="0" y="5" text-anchor="middle" fill="#ffffff" font-size="10">⚡</text>
                <!-- Motor -->
                <circle cx="50" cy="20" r="15" fill="#ef4444"/>
                <!-- Wheels -->
                <circle cx="-60" cy="60" r="30" fill="#60a5fa" stroke="#374151" stroke-width="3"/>
                <circle cx="60" cy="60" r="30" fill="#60a5fa" stroke="#374151" stroke-width="3"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="18">Electric Bike</text>
        </svg>';
    }

    public function createCommuterBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#06b6d4"/>
            <g transform="translate(200,200)">
                <!-- Frame -->
                <rect x="-70" y="-25" width="140" height="50" rx="5" fill="#6b7280" stroke="#a78bfa" stroke-width="3"/>
                <!-- Fenders -->
                <path d="M-70,0 Q-60,10 -50,20" stroke="#a78bfa" stroke-width="2" fill="none"/>
                <path d="M70,0 Q60,10 50,20" stroke="#a78bfa" stroke-width="2" fill="none"/>
                <!-- Basket -->
                <rect x="-30" y="-50" width="60" height="30" stroke="#a78bfa" stroke-width="2" fill="#fbbf24"/>
                <!-- Wheels -->
                <circle cx="-50" cy="50" r="25" fill="#1e40af" stroke="#a78bfa" stroke-width="3"/>
                <circle cx="50" cy="50" r="25" fill="#1e40af" stroke="#a78bfa" stroke-width="3"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="18">Commuter Bike</text>
        </svg>';
    }

    public function createCruiserBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#7c3aed"/>
            <g transform="translate(200,200)">
                <!-- Low Frame -->
                <path d="M-100,-20 Q-50,-40 50,-40" stroke="#5b21b6" stroke-width="4" fill="none"/>
                <path d="M100,-20 Q50,-40 -50,-40" stroke="#5b21b6" stroke-width="4" fill="none"/>
                <!-- High Handlebars -->
                <path d="M-100,-40 Q-80,-80 -30,-60" stroke="#5b21b6" stroke-width="4" fill="none"/>
                <path d="M100,-40 Q80,-80 30,-60" stroke="#5b21b6" stroke-width="4" fill="none"/>
                <!-- Seat -->
                <rect x="-40" y="-5" width="80" height="25" rx="5" fill="#1e293b"/>
                <!-- Wheels -->
                <circle cx="-70" cy="50" r="35" fill="#1e293b" stroke="#5b21b6" stroke-width="3"/>
                <circle cx="70" cy="50" r="35" fill="#1e293b" stroke="#5b21b6" stroke-width="3"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="16">Cruiser</text>
        </svg>';
    }

    public function createHybridBikeSvg(): string
    {
        return '<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="400" fill="#059669"/>
            <g transform="translate(200,200)">
                <!-- Frame (lightweight) -->
                <ellipse cx="0" cy="-20" rx="90" ry="45" fill="#4f46e5" stroke-width="3"/>
                <!-- Electric battery -->
                <rect x="-20" y="-10" width="40" height="15" fill="#60a5fa"/>
                <text x="0" y="5" text-anchor="middle" fill="#ffffff" font-size="10">⚡</text>
                <!-- Pedals -->
                <circle cx="-40" cy="60" r="12" fill="#4f46e5"/>
                <circle cx="40" cy="60" r="12" fill="#4f46e5"/>
                <!-- Chain guard -->
                <path d="M-90,30 Q0,40 90,30" stroke="#4f46e5" stroke-width="2" fill="none"/>
                <!-- Wheels -->
                <circle cx="-60" cy="50" r="28" fill="#1e40af" stroke="#059669" stroke-width="3"/>
                <circle cx="60" cy="50" r="28" fill="#1e40af" stroke="#059669" stroke-width="3"/>
            </g>
            <text x="200" y="350" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="18">Hybrid Bike</text>
        </svg>';
    }
}
