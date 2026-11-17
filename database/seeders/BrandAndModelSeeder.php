<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandAndModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandsData = [
            [
                'name' => 'Toyota',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Avanza', 'body_type' => 'mpv'],
                    ['name' => 'Innova Reborn', 'body_type' => 'mpv'],
                    ['name' => 'Innova Zenix', 'body_type' => 'mpv'],
                    ['name' => 'Fortuner', 'body_type' => 'suv'],
                    ['name' => 'Rush', 'body_type' => 'suv'],
                    ['name' => 'Calya', 'body_type' => 'mpv'],
                    ['name' => 'Raize', 'body_type' => 'suv'],
                    ['name' => 'Yaris', 'body_type' => 'hatchback'],
                    ['name' => 'Camry', 'body_type' => 'sedan'],
                    ['name' => 'Corolla Altis', 'body_type' => 'sedan'],
                    ['name' => 'Hilux', 'body_type' => 'pickup'],
                ],
            ],
            [
                'name' => 'Honda',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Brio', 'body_type' => 'city_car'],
                    ['name' => 'HR-V', 'body_type' => 'suv'],
                    ['name' => 'CR-V', 'body_type' => 'suv'],
                    ['name' => 'Jazz', 'body_type' => 'hatchback'],
                    ['name' => 'City Hatchback', 'body_type' => 'hatchback'],
                    ['name' => 'BR-V', 'body_type' => 'suv'],
                    ['name' => 'Civic', 'body_type' => 'sedan'],
                    ['name' => 'Mobilio', 'body_type' => 'mpv'],
                ],
            ],
            [
                'name' => 'Mitsubishi',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Xpander', 'body_type' => 'mpv'],
                    ['name' => 'Xpander Cross', 'body_type' => 'suv'],
                    ['name' => 'Pajero Sport', 'body_type' => 'suv'],
                    ['name' => 'Xforce', 'body_type' => 'suv'],
                    ['name' => 'Triton', 'body_type' => 'pickup'],
                ],
            ],
            [
                'name' => 'Suzuki',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Ertiga', 'body_type' => 'mpv'],
                    ['name' => 'XL7', 'body_type' => 'suv'],
                    ['name' => 'Jimny', 'body_type' => 'suv'],
                    ['name' => 'Baleno', 'body_type' => 'hatchback'],
                    ['name' => 'Ignis', 'body_type' => 'city_car'],
                    ['name' => 'Carry Pick Up', 'body_type' => 'commercial'],
                ],
            ],
            [
                'name' => 'Daihatsu',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Sigra', 'body_type' => 'mpv'],
                    ['name' => 'Terios', 'body_type' => 'suv'],
                    ['name' => 'Xenia', 'body_type' => 'mpv'],
                    ['name' => 'Ayla', 'body_type' => 'city_car'],
                    ['name' => 'Rocky', 'body_type' => 'suv'],
                    ['name' => 'Gran Max', 'body_type' => 'commercial'],
                ],
            ],
            [
                'name' => 'Hyundai',
                'is_popular' => true,
                'models' => [
                    ['name' => 'Creta', 'body_type' => 'suv'],
                    ['name' => 'Stargazer', 'body_type' => 'mpv'],
                    ['name' => 'Palisade', 'body_type' => 'suv'],
                    ['name' => 'Santa Fe', 'body_type' => 'suv'],
                    ['name' => 'Ioniq 5', 'body_type' => 'suv'],
                ],
            ],
            [
                'name' => 'Mazda',
                'is_popular' => false,
                'models' => [
                    ['name' => 'CX-5', 'body_type' => 'suv'],
                    ['name' => 'CX-3', 'body_type' => 'suv'],
                    ['name' => 'Mazda 2', 'body_type' => 'hatchback'],
                    ['name' => 'Mazda 3', 'body_type' => 'hatchback'],
                    ['name' => 'CX-30', 'body_type' => 'suv'],
                ],
            ],
            [
                'name' => 'Wuling',
                'is_popular' => false,
                'models' => [
                    ['name' => 'Almaz', 'body_type' => 'suv'],
                    ['name' => 'Cortez', 'body_type' => 'mpv'],
                    ['name' => 'Confero', 'body_type' => 'mpv'],
                    ['name' => 'Air EV', 'body_type' => 'city_car'],
                    ['name' => 'Binguo EV', 'body_type' => 'hatchback'],
                ],
            ],
            [
                'name' => 'BMW',
                'is_popular' => false,
                'models' => [
                    ['name' => '320i', 'body_type' => 'sedan'],
                    ['name' => '330i', 'body_type' => 'sedan'],
                    ['name' => '520i', 'body_type' => 'sedan'],
                    ['name' => 'X1', 'body_type' => 'suv'],
                    ['name' => 'X3', 'body_type' => 'suv'],
                    ['name' => 'X5', 'body_type' => 'suv'],
                ],
            ],
            [
                'name' => 'Mercedes-Benz',
                'is_popular' => false,
                'models' => [
                    ['name' => 'C200', 'body_type' => 'sedan'],
                    ['name' => 'C300', 'body_type' => 'sedan'],
                    ['name' => 'E300', 'body_type' => 'sedan'],
                    ['name' => 'GLA 200', 'body_type' => 'suv'],
                    ['name' => 'GLC 300', 'body_type' => 'suv'],
                ],
            ],
        ];

        foreach ($brandsData as $data) {
            $brand = Brand::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'is_popular' => $data['is_popular'],
            ]);

            foreach ($data['models'] as $model) {
                $brand->carModels()->create([
                    'name' => $model['name'],
                    'slug' => Str::slug($model['name']),
                    'body_type' => $model['body_type'],
                ]);
            }
        }
    }
}
