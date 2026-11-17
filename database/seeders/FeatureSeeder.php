<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Safety
            ['name' => 'Airbag (6+ Titik)', 'category' => 'safety'],
            ['name' => 'Anti-lock Braking System (ABS)', 'category' => 'safety'],
            ['name' => 'Electronic Brake Distribution (EBD)', 'category' => 'safety'],
            ['name' => 'Vehicle Stability Control (VSC/ESP)', 'category' => 'safety'],
            ['name' => 'Hill Start Assist (HSA)', 'category' => 'safety'],
            ['name' => 'Blind Spot Monitoring (BSM)', 'category' => 'safety'],
            ['name' => 'Kamera Mundur', 'category' => 'safety'],
            ['name' => 'Kamera 360 Derajat', 'category' => 'safety'],
            ['name' => 'Sensor Parkir Depan & Belakang', 'category' => 'safety'],
            ['name' => 'ISOFIX Child Seat', 'category' => 'safety'],

            // Comfort
            ['name' => 'Keyless Entry & Push Start Button', 'category' => 'comfort'],
            ['name' => 'Cruise Control', 'category' => 'comfort'],
            ['name' => 'Adaptive Cruise Control (ACC)', 'category' => 'comfort'],
            ['name' => 'AC Digital Dual Zone', 'category' => 'comfort'],
            ['name' => 'AC Double Blower', 'category' => 'comfort'],
            ['name' => 'Jok Kulit (Leather Seat)', 'category' => 'comfort'],
            ['name' => 'Pengaturan Kursi Elektrik', 'category' => 'comfort'],
            ['name' => 'Paddle Shift', 'category' => 'comfort'],
            ['name' => 'Power Back Door / Pintu Bagasi Elektrik', 'category' => 'comfort'],
            ['name' => 'Wireless Smartphone Charger', 'category' => 'comfort'],

            // Entertainment
            ['name' => 'Head Unit Layar Sentuh', 'category' => 'entertainment'],
            ['name' => 'Apple CarPlay & Android Auto', 'category' => 'entertainment'],
            ['name' => 'Bluetooth & USB Audio', 'category' => 'entertainment'],
            ['name' => 'Steering Audio Control', 'category' => 'entertainment'],
            ['name' => 'Premium Sound System', 'category' => 'entertainment'],

            // Exterior
            ['name' => 'Sunroof / Panoramic Roof', 'category' => 'exterior'],
            ['name' => 'Lampu LED Projector', 'category' => 'exterior'],
            ['name' => 'Daytime Running Lights (DRL)', 'category' => 'exterior'],
            ['name' => 'Spion Lipat Elektrik (Retractable Mirror)', 'category' => 'exterior'],
            ['name' => 'Fog Lamp / Lampu Kabut', 'category' => 'exterior'],
            ['name' => 'Roof Rail', 'category' => 'exterior'],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
