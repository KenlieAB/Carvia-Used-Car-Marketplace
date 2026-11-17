<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularBrands = Brand::where('is_popular', true)->take(7)->get();

        // 6 unit mobil unggulan untuk Carousel Hero
        $featuredCars = Car::with(['carModel.brand', 'city', 'primaryImage', 'user'])
            ->where('status', 'active')
            ->latest('published_at')
            ->take(6)
            ->get();

        $totalActiveCars = Car::where('status', 'active')->count();

        return view('home', compact('popularBrands', 'featuredCars', 'totalActiveCars'));
    }
}
