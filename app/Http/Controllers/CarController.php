<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\City;
use Illuminate\Http\Request;

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
        $brands = Brand::with('carModels')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('cars.create', compact('brands', 'cities'));
    }

    /**
     * Simpan iklan baru (Post-moderation).
     */
    public function store(Request $request)
    {
        // Akan diimplementasikan lengkap di Fase 6
        return redirect()->route('cars.index')->with('success', 'Iklan berhasil dipasang!');
    }
}
