<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->limit(20)->get();

        $colorVariants = [
            ['name' => 'Red', 'type' => 'color', 'price_modifier' => 0],
            ['name' => 'Blue', 'type' => 'color', 'price_modifier' => 0],
            ['name' => 'Green', 'type' => 'color', 'price_modifier' => 0],
            ['name' => 'Black', 'type' => 'color', 'price_modifier' => 10],
            ['name' => 'White', 'type' => 'color', 'price_modifier' => 5],
            ['name' => 'Yellow', 'type' => 'color', 'price_modifier' => 0],
            ['name' => 'Orange', 'type' => 'color', 'price_modifier' => 0],
            ['name' => 'Gray', 'type' => 'color', 'price_modifier' => 0],
        ];

        $sizeVariants = [
            ['name' => 'Small', 'type' => 'size', 'price_modifier' => -10],
            ['name' => 'Medium', 'type' => 'size', 'price_modifier' => 0],
            ['name' => 'Large', 'type' => 'size', 'price_modifier' => 10],
            ['name' => 'XL', 'type' => 'size', 'price_modifier' => 20],
            ['name' => 'XXL', 'type' => 'size', 'price_modifier' => 25],
        ];

        $productTypes = [
            'Helmet' => ['type' => 'size', 'variants' => $sizeVariants],
            'Jacket' => ['type' => 'size', 'variants' => $sizeVariants],
            'Gloves' => ['type' => 'size', 'variants' => [
                ['name' => 'S', 'type' => 'size', 'price_modifier' => 0],
                ['name' => 'M', 'type' => 'size', 'price_modifier' => 0],
                ['name' => 'L', 'type' => 'size', 'price_modifier' => 0],
                ['name' => 'XL', 'type' => 'size', 'price_modifier' => 5],
            ]],
            'Boots' => ['type' => 'size', 'variants' => $sizeVariants],
            'Pants' => ['type' => 'size', 'variants' => $sizeVariants],
            'Guard' => ['type' => 'size', 'variants' => [
                ['name' => 'One Size', 'type' => 'size', 'price_modifier' => 0],
            ]],
        ];

        foreach ($products as $index => $product) {
            $matchedType = null;
            $matchedConfig = null;

            foreach ($productTypes as $typeName => $config) {
                if (stripos($product->name, $typeName) !== false || stripos($product->type, $typeName) !== false) {
                    $matchedType = $typeName;
                    $matchedConfig = $config;
                    break;
                }
            }

            if ($matchedType) {
                $product->update(['has_variants' => true]);

                $variants = $matchedConfig['variants'];
                $basePrice = (float) $product->price;

                foreach ($variants as $variantIndex => $variant) {
                    $variantPrice = $basePrice + $variant['price_modifier'];
                    $skuSuffix = strtoupper(substr($variant['name'], 0, 3));

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'type' => $variant['type'],
                        'price' => max(0, $variantPrice),
                        'sku_suffix' => $skuSuffix,
                        'stock_quantity' => rand(0, 20),
                        'is_active' => true,
                        'display_order' => $variantIndex,
                    ]);
                }
            } elseif ($index % 3 === 0) {
                $product->update(['has_variants' => true]);

                $colorOptions = array_slice($colorVariants, 0, 3);
                $basePrice = (float) $product->price;

                foreach ($colorOptions as $variantIndex => $variant) {
                    $variantPrice = $basePrice + $variant['price_modifier'];
                    $skuSuffix = strtoupper(substr($variant['name'], 0, 3));

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'type' => 'color',
                        'price' => max(0, $variantPrice),
                        'sku_suffix' => $skuSuffix,
                        'stock_quantity' => rand(0, 15),
                        'is_active' => true,
                        'display_order' => $variantIndex,
                    ]);
                }
            }
        }
    }
}
