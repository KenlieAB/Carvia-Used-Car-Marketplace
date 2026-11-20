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
        $userId = auth()->id();

        $userCars = Car::with(['carModel.brand', 'city', 'primaryImage'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $stats = [
            'active'  => Car::where('user_id', $userId)->where('status', 'active')->count(),
            'sold'    => Car::where('user_id', $userId)->where('status', 'sold')->count(),
            'expired' => Car::where('user_id', $userId)->where('status', 'expired')->count(),
            'total'   => Car::where('user_id', $userId)->count(),
        ];

        return view('pages.my-ads', compact('userCars', 'stats'));
    }
}
