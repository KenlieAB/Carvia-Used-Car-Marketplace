<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarImage;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CarController extends Controller
{
    /**
     * Display a listing of the cars (Browse Cars page).
     */
    public function index(Request $request)
    {
        $brands = Brand::with('carModels')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        $query = Car::with(['carModel.brand', 'city', 'primaryImage', 'user']);

        // Filter Include Sold
        if (! $request->boolean('include_sold')) {
            $query->where('status', 'active');
        } else {
            $query->whereIn('status', ['active', 'sold']);
        }

        // Search Keyword (Brand, Model, Title, City)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhereHas('carModel', function ($m) use ($q) {
                        $m->where('name', 'like', "%{$q}%")
                            ->orWhereHas('brand', function ($b) use ($q) {
                                $b->where('name', 'like', "%{$q}%");
                            });
                    })
                    ->orWhereHas('city', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // Filter Brand
        if ($request->filled('brand')) {
            $query->whereHas('carModel.brand', function ($b) use ($request) {
                $b->where('slug', $request->brand);
            });
        }

        // Filter Body Type
        if ($request->filled('body_type')) {
            $query->whereHas('carModel', function ($m) use ($request) {
                $m->where('body_type', $request->body_type);
            });
        }

        // Filter Transmission
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        // Filter Min Price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter Max Price
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter Fuel Type
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        // Filter Year Range
        if ($request->filled('min_year')) {
            $query->where('manufacture_year', '>=', $request->min_year);
        }
        if ($request->filled('max_year')) {
            $query->where('manufacture_year', '<=', $request->max_year);
        }

        // Filter City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'mileage_low' => $query->orderBy('mileage_km', 'asc'),
            'year_high' => $query->orderBy('manufacture_year', 'desc'),
            'year_low' => $query->orderBy('manufacture_year', 'asc'),
            default => $query->latest('published_at'),
        };

        $cars = $query->paginate(12)->withQueryString();
        $totalCars = $cars->total();

        return view('cars.index', compact('cars', 'brands', 'cities', 'totalCars'));
    }

    /**
     * Show single car detail.
     */
    public function show(string $slug)
    {
        $car = Car::with(['carModel.brand', 'city', 'images', 'features', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $car->increment('view_count');

        // Related cars (same brand or body type)
        $relatedCars = Car::with(['carModel.brand', 'city', 'primaryImage'])
            ->where('id', '!=', $car->id)
            ->where('status', 'active')
            ->whereHas('carModel', function ($m) use ($car) {
                $m->where('brand_id', $car->carModel->brand_id);
            })
            ->take(3)
            ->get();

        return view('cars.show', compact('car', 'relatedCars'));
    }

    /**
     * Form pasang iklan baru.
     */
    public function create()
    {
        $brands = Brand::with(['carModels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $cities = City::orderBy('name')->get();
        $features = \App\Models\Feature::orderBy('name')->get();

        return view('cars.create', compact('brands', 'cities', 'features'));
    }

    /**
     * Simpan iklan baru (Post-moderation).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Identitas Mobil
            'title' => 'required|string|max:200',
            'car_model_id' => 'required|exists:car_models,id',
            'city_id' => 'required|exists:cities,id',
            'price' => 'required|numeric|min:1000000',
            'price_negotiable' => 'boolean',
            'description' => 'required|string|min:20',

            // Tahun & Mesin
            'manufacture_year' => 'required|integer|min:1980|max:'.(date('Y') + 1),
            'registration_year' => 'required|integer|min:1980|max:'.(date('Y') + 1),
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'engine_cc' => 'required|integer|min:500|max:10000',
            'color' => 'required|string|max:50',
            'seats' => 'required|integer|min:2|max:20',

            // Kilometer & Servis
            'mileage_km' => 'required|integer|min:0',
            'service_record' => 'required|in:authorized_dealer,specialist,self,none',
            'has_service_book' => 'boolean',
            'has_spare_key' => 'boolean',

            // Legalitas & Surat
            'bpkb_status' => 'required|in:on_hand,in_leasing',
            'stnk_status' => 'required|in:available,lost',
            'has_faktur' => 'boolean',
            'has_form_a' => 'boolean',
            'registration_type' => 'required|in:personal,company',
            'owner_count' => 'required|integer|min:1|max:10',

            // Pajak & Plat
            'plate_region' => 'required|string|max:5',
            'plate_type' => 'nullable|in:odd,even',
            'tax_status' => 'required|in:active,expired',
            'tax_expiry_date' => 'nullable|date',
            'plate_expiry_year' => 'nullable|integer|min:'.date('Y').'|max:'.(date('Y') + 10),
            'annual_tax_amount' => 'nullable|numeric|min:0',

            // Kondisi Fisik
            'is_accident_free' => 'boolean',
            'is_flood_free' => 'boolean',
            'modification_status' => 'required|in:stock,light_mod,full_mod',

            // Relasi & Upload
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB per foto
        ], [
            'images.required' => 'Wajib mengunggah minimal 1 foto mobil.',
            'images.min' => 'Wajib mengunggah minimal 1 foto mobil.',
            'images.max' => 'Maksimal mengunggah 10 foto mobil.',
            'title.required' => 'Judul iklan wajib diisi.',
            'car_model_id.required' => 'Pilih model mobil yang sesuai.',
            'city_id.required' => 'Pilih lokasi kota mobil.',
            'price.required' => 'Harga wajib diisi.',
            'description.required' => 'Deskripsi mobil wajib diisi.',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // Generate unique slug
            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug;
            $count = 1;
            while (Car::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            // Siapkan data car
            $carData = $validated;
            $carData['user_id'] = auth()->id();
            $carData['slug'] = $slug;
            $carData['status'] = 'active'; // Langsung tayang (post-moderation)
            $carData['view_count'] = 0;
            $carData['published_at'] = now();
            $carData['expired_at'] = now()->addDays(30);

            // Boolean defaults
            $carData['price_negotiable'] = $request->boolean('price_negotiable');
            $carData['has_service_book'] = $request->boolean('has_service_book');
            $carData['has_spare_key'] = $request->boolean('has_spare_key');
            $carData['has_faktur'] = $request->boolean('has_faktur');
            $carData['has_form_a'] = $request->boolean('has_form_a');
            $carData['is_accident_free'] = $request->boolean('is_accident_free');
            $carData['is_flood_free'] = $request->boolean('is_flood_free');

            // Hapus field yang bukan kolom tabel cars
            unset($carData['features'], $carData['images']);

            $car = Car::create($carData);

            // Simpan relasi features
            if ($request->filled('features')) {
                $car->features()->sync($request->features);
            }

            // Upload dan simpan images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('cars', 'public');

                    CarImage::create([
                        'car_id'     => $car->id,
                        'path'       => '/storage/' . $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('cars.show', $car->slug)
                ->with('success', 'Iklan mobil Anda berhasil diterbitkan dan langsung tayang!');
        });
    }

    /**
     * Form edit iklan (pre-filled dengan data existing).
     */
    public function edit(string $slug)
    {
        $car = Car::with(['images', 'features', 'carModel'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Hanya pemilik yang boleh edit
        abort_if($car->user_id !== auth()->id(), 403, 'Anda tidak memiliki izin untuk mengedit iklan ini.');

        $brands = Brand::with(['carModels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $cities = City::orderBy('name')->get();
        $features = \App\Models\Feature::orderBy('name')->get();

        return view('cars.edit', compact('car', 'brands', 'cities', 'features'));
    }

    /**
     * Simpan perubahan iklan (update).
     */
    public function update(Request $request, string $slug)
    {
        $car = Car::where('slug', $slug)->firstOrFail();

        // Hanya pemilik yang boleh update
        abort_if($car->user_id !== auth()->id(), 403, 'Anda tidak memiliki izin untuk mengubah iklan ini.');

        $validated = $request->validate([
            'title'               => 'required|string|max:200',
            'car_model_id'        => 'required|exists:car_models,id',
            'city_id'             => 'required|exists:cities,id',
            'price'               => 'required|numeric|min:1000000',
            'price_negotiable'    => 'boolean',
            'description'         => 'required|string|min:20',
            'manufacture_year'    => 'required|integer|min:1980|max:'.(date('Y') + 1),
            'registration_year'   => 'required|integer|min:1980|max:'.(date('Y') + 1),
            'transmission'        => 'required|in:manual,automatic',
            'fuel_type'           => 'required|in:petrol,diesel,hybrid,electric',
            'engine_cc'           => 'required|integer|min:500|max:10000',
            'color'               => 'required|string|max:50',
            'seats'               => 'required|integer|min:2|max:20',
            'mileage_km'          => 'required|integer|min:0',
            'service_record'      => 'required|in:authorized_dealer,specialist,self,none',
            'has_service_book'    => 'boolean',
            'has_spare_key'       => 'boolean',
            'bpkb_status'         => 'required|in:on_hand,in_leasing',
            'stnk_status'         => 'required|in:available,lost',
            'has_faktur'          => 'boolean',
            'has_form_a'          => 'boolean',
            'registration_type'   => 'required|in:personal,company',
            'owner_count'         => 'required|integer|min:1|max:10',
            'plate_region'        => 'required|string|max:5',
            'plate_type'          => 'nullable|in:odd,even',
            'tax_status'          => 'required|in:active,expired',
            'tax_expiry_date'     => 'nullable|date',
            'plate_expiry_year'   => 'nullable|integer|min:'.date('Y').'|max:'.(date('Y') + 10),
            'annual_tax_amount'   => 'nullable|numeric|min:0',
            'is_accident_free'    => 'boolean',
            'is_flood_free'       => 'boolean',
            'modification_status' => 'required|in:stock,light_mod,full_mod',
            'features'            => 'nullable|array',
            'features.*'          => 'exists:features,id',
            // Foto baru bersifat opsional saat edit
            'images'              => 'nullable|array|max:10',
            'images.*'            => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            // ID foto lama yang ingin dihapus
            'delete_images'       => 'nullable|array',
            'delete_images.*'     => 'integer|exists:car_images,id',
        ]);

        return DB::transaction(function () use ($request, $validated, $car) {
            $carData = $validated;

            // Boolean defaults
            $carData['price_negotiable'] = $request->boolean('price_negotiable');
            $carData['has_service_book'] = $request->boolean('has_service_book');
            $carData['has_spare_key']    = $request->boolean('has_spare_key');
            $carData['has_faktur']       = $request->boolean('has_faktur');
            $carData['has_form_a']       = $request->boolean('has_form_a');
            $carData['is_accident_free'] = $request->boolean('is_accident_free');
            $carData['is_flood_free']    = $request->boolean('is_flood_free');

            // Hapus field non-kolom
            unset($carData['features'], $carData['images'], $carData['delete_images']);

            $car->update($carData);

            // Sync fitur
            $car->features()->sync($request->input('features', []));

            // Hapus foto yang ditandai user
            if ($request->filled('delete_images')) {
                $toDelete = CarImage::where('car_id', $car->id)
                    ->whereIn('id', $request->delete_images)
                    ->get();

                foreach ($toDelete as $img) {
                    $filePath = str_replace('/storage/', '', $img->path);
                    Storage::disk('public')->delete($filePath);
                    $img->delete();
                }
            }

            // Upload foto baru (append)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('cars', 'public');
                    $sortOrder = $car->images()->max('sort_order') + 1;

                    CarImage::create([
                        'car_id'     => $car->id,
                        'path'       => '/storage/' . $path,
                        'is_primary' => false,
                        'sort_order' => $sortOrder,
                    ]);
                }
            }

            // Pastikan ada foto utama jika foto lama dihapus
            if (! $car->images()->where('is_primary', true)->exists()) {
                $firstImg = $car->images()->orderBy('sort_order')->first();
                if ($firstImg) {
                    $firstImg->update(['is_primary' => true]);
                }
            }

            return redirect()->route('my-ads')
                ->with('success', 'Iklan berhasil diperbarui!');
        });
    }

    /**
     * Tandai mobil sebagai Terjual.
     */
    public function markSold(string $slug)
    {
        $car = Car::where('slug', $slug)->firstOrFail();
        abort_if($car->user_id !== auth()->id(), 403);

        $car->update(['status' => 'sold']);

        return redirect()->route('my-ads')
            ->with('success', 'Iklan "' . $car->title . '" ditandai sebagai Terjual.');
    }

    /**
     * Perpanjang masa aktif iklan 30 hari.
     */
    public function extendExpiry(string $slug)
    {
        $car = Car::where('slug', $slug)->firstOrFail();
        abort_if($car->user_id !== auth()->id(), 403);

        $car->update([
            'expired_at' => now()->addDays(30),
            'status'     => 'active',
        ]);

        return redirect()->route('my-ads')
            ->with('success', 'Masa tayang iklan berhasil diperpanjang 30 hari.');
    }

    /**
     * Hapus iklan beserta semua foto.
     */
    public function destroy(string $slug)
    {
        $car = Car::with('images')->where('slug', $slug)->firstOrFail();
        abort_if($car->user_id !== auth()->id(), 403);

        // Hapus semua file foto fisik dari storage
        foreach ($car->images as $img) {
            $filePath = str_replace('/storage/', '', $img->path);
            Storage::disk('public')->delete($filePath);
        }

        $car->delete();

        return redirect()->route('my-ads')
            ->with('success', 'Iklan berhasil dihapus.');
    }
}
