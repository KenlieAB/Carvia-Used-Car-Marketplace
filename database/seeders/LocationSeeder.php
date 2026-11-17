<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'DKI Jakarta' => [
                'Jakarta Selatan',
                'Jakarta Barat',
                'Jakarta Pusat',
                'Jakarta Timur',
                'Jakarta Utara',
            ],
            'Jawa Barat' => [
                'Bandung',
                'Bekasi',
                'Bogor',
                'Depok',
                'Cimahi',
                'Cirebon',
                'Sukabumi',
                'Tasikmalaya',
                'Karawang',
            ],
            'Banten' => [
                'Tangerang',
                'Tangerang Selatan',
                'Serang',
                'Cilegon',
            ],
            'Jawa Tengah' => [
                'Semarang',
                'Surakarta (Solo)',
                'Magelang',
                'Pekalongan',
                'Tegal',
                'Salatiga',
                'Banyumas (Purwokerto)',
            ],
            'DI Yogyakarta' => [
                'Yogyakarta',
                'Sleman',
                'Bantul',
            ],
            'Jawa Timur' => [
                'Surabaya',
                'Malang',
                'Sidoarjo',
                'Gresik',
                'Kediri',
                'Madiun',
                'Jember',
            ],
            'Bali' => [
                'Denpasar',
                'Badung',
                'Gianyar',
            ],
            'Sumatera Utara' => [
                'Medan',
                'Binjai',
                'Pematang Siantar',
                'Deli Serdang',
            ],
            'Sumatera Selatan' => [
                'Palembang',
                'Prabumulih',
            ],
            'Sulawesi Selatan' => [
                'Makassar',
                'Gowa',
                'Maros',
            ],
        ];

        foreach ($locations as $provinceName => $cities) {
            $province = Province::create([
                'name' => $provinceName,
                'slug' => Str::slug($provinceName),
            ]);

            foreach ($cities as $cityName) {
                $province->cities()->create([
                    'name' => $cityName,
                    'slug' => Str::slug($cityName),
                ]);
            }
        }
    }
}
