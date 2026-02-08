<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BikeFactory extends Factory
{
    public function definition(): array
    {
        $manufacturers = [
            'Honda' => ['CRF 110 F', 'CRF 150 R', 'CRF 250 R', 'CRF 450 R', 'CRF 450 RX', 'CRF 250 X', 'CRF 300 L', 'CRF 300 R', 'CRF 1000 L Africa Twin', 'CBR 600 RR'],
            'Yamaha' => ['MT-07', 'MT-09', 'MT-10', 'YZF-R1', 'YZF-R3', 'YZF-R6', 'XSR 700', 'XSR 900', 'Tracer 9', 'Niken'],
            'Kawasaki' => ['Ninja 400', 'Ninja 650', 'Ninja ZX-10R', 'Ninja ZX-14R', 'Z900', 'Z650', 'Versys 650', 'KLR 650', 'Vulcan S', 'W800'],
            'Suzuki' => ['GSX-R1000', 'GSX-R750', 'GSX-R600', 'GSX-S750', 'V-Strom 650', 'V-Strom 1000', 'Katana', 'Hayabusa', 'SV650', 'Boulevard M109R'],
            'Ducati' => ['Panigale V4', 'Superleggera V4', 'Streetfighter V4', 'Multistrada V4', 'Monster 821', 'Monster 1200', 'Scrambler Icon', 'XDiavel', 'Hypermotard', 'Supersport 950'],
            'BMW' => ['R 1250 GS', 'R 1250 GS Adventure', 'S 1000 RR', 'M 1000 RR', 'K 1600 GTL', 'K 1600 B', 'R 1250 R', 'F 900 XR', 'G 310 R', 'G 310 GS'],
            'KTM' => ['Duke 125', 'Duke 250', 'Duke 390', 'Duke 790', 'Duke 890', 'Duke 1290', 'RC 390', 'RC 8C', 'Adventure 390', 'Adventure 890'],
            'Harley-Davidson' => ['Street Glide', 'Road Glide', 'Electra Glide', 'Road King', 'Fat Boy', 'Iron 883', 'Sportster S', 'Pan America', 'LiveWire', 'Breakout'],
            'Triumph' => ['Tiger 900', 'Tiger 1200', 'Speed Triple 1200 RS', 'Street Triple 765 RS', 'Bonneville T100', 'Bonneville Bobber', 'Scrambler 1200', 'Rocket 3', 'Daytona 660', 'Speed Twin'],
            'Indian' => ['Chief', 'Chief Dark Horse', 'Chieftain', 'Springfield', 'Scout', 'FTR 1200', 'Pursuit Dark Horse', 'Super Chief Limited', 'Bobber Dark Horse', 'Challenger Dark Horse'],
        ];

        $manufacturer = array_rand($manufacturers);
        $model = $this->faker->randomElement($manufacturers[$manufacturer]);
        $year = $this->faker->numberBetween(2020, 2026);

        return [
            'manufacturer' => $manufacturer,
            'model' => $model,
            'year' => $year,
            'is_active' => true,
        ];
    }
}
