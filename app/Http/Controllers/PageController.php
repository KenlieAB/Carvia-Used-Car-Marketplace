<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function myAds()
    {
        // Placeholder untuk Garasi User (Fase 7)
        // Ambil demo user pertama untuk preview
        $userCars = Car::with(['carModel.brand', 'city', 'primaryImage'])
            ->latest()
            ->paginate(10);

        return view('pages.my-ads', compact('userCars'));
    }
}
