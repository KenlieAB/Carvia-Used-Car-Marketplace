<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\City;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            return;
        }

        $allFeatures = Feature::all();

        $sampleCars = [
            [
                'title' => 'Toyota Fortuner 2.4 VRZ Diesel AT 2021 Tangan Pertama Istimewa',
                'model_name' => 'Fortuner',
                'city_name' => 'Jakarta Selatan',
                'user_index' => 0,
                'price' => 435000000,
                'price_negotiable' => true,
                'description' => "Dijual Toyota Fortuner VRZ 2.4 Diesel Automatic tahun 2021 pemakaian 2022.\n- Mobil simpanan sangat terawat\n- Interior bersih tidak bau rokok\n- Service record Auto2000 Cilandak rutin\n- BPKB asli di tangan, faktur lengkap, kunci serep ada 2\n- Pajak hidup panjang sampai Maret 2027\n- Tidak bekas tabrakan atau banjir, siap general check-up bengkel resmi.",
                'manufacture_year' => 2021,
                'registration_year' => 2022,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'engine_cc' => 2400,
                'color' => 'Hitam Metalik',
                'seats' => 7,
                'mileage_km' => 38500,
                'service_record' => 'authorized_dealer',
                'has_service_book' => true,
                'has_spare_key' => true,
                'bpkb_status' => 'on_hand',
                'stnk_status' => 'available',
                'has_faktur' => true,
                'has_form_a' => false,
                'registration_type' => 'personal',
                'owner_count' => 1,
                'plate_region' => 'B',
                'plate_type' => 'odd',
                'tax_status' => 'active',
                'tax_expiry_date' => now()->addMonths(8)->toDateString(),
                'plate_expiry_year' => 2027,
                'annual_tax_amount' => 7200000,
                'is_accident_free' => true,
                'is_flood_free' => true,
                'modification_status' => 'stock',
                'status' => 'active',
                'view_count' => 142,
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1200&q=80',
                ],
            ],
            [
                'title' => 'Honda HR-V 1.5 E CVT 2019 Putih Mutiara Mulus Siap Pakai',
                'model_name' => 'HR-V',
                'city_name' => 'Bandung',
                'user_index' => 1,
                'price' => 238000000,
                'price_negotiable' => true,
                'description' => "Honda HRV 1.5 E CVT tahun 2019 warna favorit putih mutiara.\n- Pajak hidup, kaleng panjang\n- KM 48 ribuan asli, ban tebal 90%\n- Kaki-kaki senyap empuk, AC dingin menggigil\n- Surat-surat lengkap BPKB, STNK, Buku Panduan & Servis\n- Nego tipis di tempat setelah cek unit.",
                'manufacture_year' => 2019,
                'registration_year' => 2019,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'engine_cc' => 1500,
                'color' => 'Putih Mutiara',
                'seats' => 5,
                'mileage_km' => 48200,
                'service_record' => 'authorized_dealer',
                'has_service_book' => true,
                'has_spare_key' => true,
                'bpkb_status' => 'on_hand',
                'stnk_status' => 'available',
                'has_faktur' => true,
                'has_form_a' => false,
                'registration_type' => 'personal',
                'owner_count' => 1,
                'plate_region' => 'D',
                'plate_type' => 'even',
                'tax_status' => 'active',
                'tax_expiry_date' => now()->addMonths(5)->toDateString(),
                'plate_expiry_year' => 2029,
                'annual_tax_amount' => 4100000,
                'is_accident_free' => true,
                'is_flood_free' => true,
                'modification_status' => 'stock',
                'status' => 'active',
                'view_count' => 89,
                'images' => [
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80',
                ],
            ],
            [
                'title' => 'Mitsubishi Xpander Ultimate AT 2022 Full Original Antik KM Rendah',
                'model_name' => 'Xpander',
                'city_name' => 'Tangerang',
                'user_index' => 2,
                'price' => 245000000,
                'price_negotiable' => false,
                'description' => "Dijual santai Mitsubishi Xpander Ultimate AT Facelift 2022.\n- Varian tertinggi, fitur keselamatan lengkap\n- Kilometer baru jalan 21 ribu jarang keluar kota\n- Tangan pertama dari baru perorangan\n- Bebas tabrakan dan banjir berani garansi uang kembali.",
                'manufacture_year' => 2022,
                'registration_year' => 2022,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'engine_cc' => 1500,
                'color' => 'Abu-abu Metalik',
                'seats' => 7,
                'mileage_km' => 21300,
                'service_record' => 'authorized_dealer',
                'has_service_book' => true,
                'has_spare_key' => true,
                'bpkb_status' => 'on_hand',
                'stnk_status' => 'available',
                'has_faktur' => true,
                'has_form_a' => false,
                'registration_type' => 'personal',
                'owner_count' => 1,
                'plate_region' => 'B',
                'plate_type' => 'even',
                'tax_status' => 'active',
                'tax_expiry_date' => now()->addMonths(11)->toDateString(),
                'plate_expiry_year' => 2027,
                'annual_tax_amount' => 3900000,
                'is_accident_free' => true,
                'is_flood_free' => true,
                'modification_status' => 'stock',
                'status' => 'active',
                'view_count' => 210,
                'images' => [
                    'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?auto=format&fit=crop&w=1200&q=80',
                ],
            ],
            [
                'title' => 'Honda Brio Satya E CVT 2020 Merah Terawat Irit Lincah',
                'model_name' => 'Brio',
                'city_name' => 'Surabaya',
                'user_index' => 0,
                'price' => 148000000,
                'price_negotiable' => true,
                'description' => "Honda Brio E CVT pemakaian harian dalam kota. Mesin sangat prima, bensin super irit, AC dingin, transmisi halus. Surat komplit tinggal gas.",
                'manufacture_year' => 2020,
                'registration_year' => 2021,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'engine_cc' => 1200,
                'color' => 'Merah Rallye',
                'seats' => 5,
                'mileage_km' => 35000,
                'service_record' => 'authorized_dealer',
                'has_service_book' => true,
                'has_spare_key' => true,
                'bpkb_status' => 'on_hand',
                'stnk_status' => 'available',
                'has_faktur' => true,
                'has_form_a' => false,
                'registration_type' => 'personal',
                'owner_count' => 1,
                'plate_region' => 'L',
                'plate_type' => 'odd',
                'tax_status' => 'active',
                'tax_expiry_date' => now()->addMonths(3)->toDateString(),
                'plate_expiry_year' => 2026,
                'annual_tax_amount' => 2300000,
                'is_accident_free' => true,
                'is_flood_free' => true,
                'modification_status' => 'stock',
                'status' => 'active',
                'view_count' => 74,
                'images' => [
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1200&q=80',
                ],
            ],
            [
                'title' => 'Toyota Kijang Innova Reborn 2.4 V Diesel AT 2018 Hitam',
                'model_name' => 'Innova Reborn',
                'city_name' => 'Bekasi',
                'user_index' => 1,
                'price' => 315000000,
                'price_negotiable' => true,
                'description' => "Innova Reborn Diesel tipe V AT 2018. Mesin 2GD legendaris bertenaga dan irit. Interior jok sudah lapis kulit mbtech rapi. Pajak on.",
                'manufacture_year' => 2018,
                'registration_year' => 2018,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'engine_cc' => 2400,
                'color' => 'Hitam',
                'seats' => 7,
                'mileage_km' => 89000,
                'service_record' => 'specialist',
                'has_service_book' => true,
                'has_spare_key' => false,
                'bpkb_status' => 'on_hand',
                'stnk_status' => 'available',
                'has_faktur' => true,
                'has_form_a' => false,
                'registration_type' => 'personal',
                'owner_count' => 2,
                'plate_region' => 'B',
                'plate_type' => 'even',
                'tax_status' => 'active',
                'tax_expiry_date' => now()->addMonths(4)->toDateString(),
                'plate_expiry_year' => 2028,
                'annual_tax_amount' => 5400000,
                'is_accident_free' => true,
                'is_flood_free' => true,
                'modification_status' => 'light_mod',
                'status' => 'active',
                'view_count' => 320,
                'images' => [
                    'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1200&q=80',
                ],
            ],
        ];

        foreach ($sampleCars as $data) {
            $carModel = CarModel::where('name', $data['model_name'])->first();
            $city = City::where('name', $data['city_name'])->first();

            if (! $carModel || ! $city) {
                continue;
            }

            $user = $users[$data['user_index'] % $users->count()];
            $uniqueSlug = Str::slug($data['title']).'-'.strtolower(Str::random(5));

            $car = Car::create([
                'user_id' => $user->id,
                'car_model_id' => $carModel->id,
                'city_id' => $city->id,
                'title' => $data['title'],
                'slug' => $uniqueSlug,
                'price' => $data['price'],
                'price_negotiable' => $data['price_negotiable'],
                'description' => $data['description'],
                'manufacture_year' => $data['manufacture_year'],
                'registration_year' => $data['registration_year'],
                'transmission' => $data['transmission'],
                'fuel_type' => $data['fuel_type'],
                'engine_cc' => $data['engine_cc'],
                'color' => $data['color'],
                'seats' => $data['seats'],
                'mileage_km' => $data['mileage_km'],
                'service_record' => $data['service_record'],
                'has_service_book' => $data['has_service_book'],
                'has_spare_key' => $data['has_spare_key'],
                'bpkb_status' => $data['bpkb_status'],
                'stnk_status' => $data['stnk_status'],
                'has_faktur' => $data['has_faktur'],
                'has_form_a' => $data['has_form_a'],
                'registration_type' => $data['registration_type'],
                'owner_count' => $data['owner_count'],
                'plate_region' => $data['plate_region'],
                'plate_type' => $data['plate_type'],
                'tax_status' => $data['tax_status'],
                'tax_expiry_date' => $data['tax_expiry_date'],
                'plate_expiry_year' => $data['plate_expiry_year'],
                'annual_tax_amount' => $data['annual_tax_amount'],
                'is_accident_free' => $data['is_accident_free'],
                'is_flood_free' => $data['is_flood_free'],
                'modification_status' => $data['modification_status'],
                'status' => $data['status'],
                'view_count' => $data['view_count'],
                'published_at' => now(),
                'expired_at' => now()->addDays(30),
            ]);

            // Hubungkan beberapa fitur acak
            if ($allFeatures->isNotEmpty()) {
                $randomFeatures = $allFeatures->random(min(6, $allFeatures->count()))->pluck('id');
                $car->features()->sync($randomFeatures);
            }

            // Tambahkan foto
            foreach ($data['images'] as $index => $imageUrl) {
                $car->images()->create([
                    'path' => $imageUrl,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
