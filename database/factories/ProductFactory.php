<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        $types = [
            'Sport', 'Cruiser', 'Touring', 'Naked', 'Adventure',
            'Dual Sport', 'Sport Touring', 'Supermoto', 'Off Road',
            'Enduro', 'Motocross', 'Trial', 'Electric', 'Hybrid',
            'City', 'Scooter', 'Racing', 'Track', 'Vintage',
            'Classic', 'Engine', 'Exhaust', 'Air Filter', 'Fuel System',
            'Transmission', 'Brake', 'Suspension', 'Wheel', 'Tire',
            'Chain', 'Sprocket', 'Handlebar', 'Seat', 'Body',
            'Fairing', 'Light', 'Helmet', 'Jacket', 'Glove',
            'Boot', 'Pant', 'Armor', 'Goggle', 'Protection',
            'Maintenance', 'Tool', 'Lubricant', 'Cleaner', 'Battery',
            'Electrical', 'Accessory', 'Luggage', 'Storage', 'Lock',
            'Cover', 'Stand',
        ];

        $manufacturers = [
            'Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'Ducati',
            'BMW', 'KTM', 'Harley-Davidson', 'Indian', 'Triumph',
            'Victory', 'Aprilia', 'Moto Guzzi', 'Buell', 'MV Agusta',
            'Bimota', 'Benelli', 'Husqvarna', 'Gas Gas', 'Beta',
            'Sherco', 'Vespa', 'Piaggio', 'Kymco', 'SYM',
            'Gogoro', 'Zero', 'Energica', 'Brammo', 'Segway',
            'NIU', 'Super Soco', 'Voi', 'Askoll', 'E-Glow',
            'Arc', 'Ride1Up', 'Sur-Ron', 'Cake', 'Urbeto',
            'Bosch', 'Brembo', 'Ohlins', 'Showa', 'Kayaba',
            'Michelin', 'Pirelli', 'Dunlop', 'Bridgestone', 'Metzeler',
            'Alpinestars', 'Dainese', 'Revit', 'Klim', 'Fox',
            'Shoei', 'Arai', 'Bell', 'AGV', 'HJC',
        ];

        $type = $this->faker->randomElement($types);
        $manufacturer = $this->faker->randomElement($manufacturers);
        $basePrice = $this->faker->numberBetween(100, 50000);

        $name = $this->generateProductName($type, $manufacturer, $category->name);

        $images = [
            'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558981033-0f0309287805?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1571188654248-7a89213915f11?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1591635333245-7f322d95aee8?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=400&h=400&fit=crop',
            'https://images.unsplash.com/photo-1621296115628-662905474717?w=400&h=400&fit=crop',
        ];

        return [
            'category_id' => $category->id,
            'name' => $name,
            'description' => $this->generateDescription($type),
            'image' => $this->faker->randomElement($images),
            'type' => $type,
            'manufacturer' => $manufacturer,
            'price' => $basePrice,
            'sku' => strtoupper($this->faker->bothify($manufacturer.'??-####')),
            'stock_quantity' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    private function generateProductName(string $type, string $manufacturer, string $category): string
    {
        $models = [
            'Pro', 'Elite', 'Expert', 'Comp', 'Sport', 'Race', 'GT',
            'Performance', 'Street', 'Cross Country', 'Motocross', 'Dual Sport',
            'Enduro', 'Adventure', 'Supermoto', 'Cruiser', 'Touring',
            'Standard', 'Naked', 'Retro', 'Classic', 'Vintage', 'Modern',
            'Electric', 'Hybrid', 'Commuter', 'Urban', 'Scooter', 'Moped',
            'Carbon', 'Aluminum', 'Steel', 'Titanium', 'Lightweight', 'Heavy Duty',
            'Custom', 'Limited Edition', 'Special Edition', 'Anniversary', 'Commemorative',
            'Track', 'Superbike', 'Hyperbike', 'Powerhouse', 'Torque', 'Thunder',
        ];

        $years = [2023, 2024, 2025, 2026];

        return $this->faker->randomElement($years).' '.
               $manufacturer.' '.
               $this->faker->randomElement($models).' '.
               $type;
    }

    private function generateDescription(string $type): string
    {
        $features = [
            'lightweight construction', 'durable materials', 'premium components',
            'advanced technology', 'superior performance', 'exceptional value',
            'professional grade', 'award-winning design', 'industry-leading quality',
            'innovative features', 'cutting-edge technology', 'race-ready',
            'powerful engine', 'smooth transmission', 'excellent handling',
            'superior braking', 'comfortable ergonomics', 'stylish design',
        ];

        return 'This '.strtolower($type).' offers '.
               $this->faker->randomElement($features).' and '.
               $this->faker->randomElement($features).'. '.
               'Perfect for '.$this->faker->randomElement(['beginners', 'enthusiasts', 'professionals']).
               ' looking for '.$this->faker->randomElement(['reliable performance', 'ultimate comfort', 'maximum speed', 'excellent handling']).'.';
    }
}
