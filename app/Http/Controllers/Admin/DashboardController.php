<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Report;
use App\Models\User;
use App\Models\Brand;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_cars'    => Car::count(),
            'active_cars'   => Car::where('status', 'active')->count(),
            'sold_cars'     => Car::where('status', 'sold')->count(),
            'rejected_cars' => Car::where('status', 'rejected')->count(),
            'total_users'   => User::where('role', 'user')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_brands'  => Brand::count(),
        ];

        $recentCars = Car::with(['carModel.brand', 'user', 'city', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with(['car', 'reporter'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCars', 'recentReports'));
    }
}
