<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount([
            'carModels as active_cars_count' => function ($q) {
                // hitung total mobil aktif per brand
                $q->join('cars', 'cars.car_model_id', '=', 'car_models.id')
                    ->where('cars.status', 'active');
            },
        ])->orderBy('name')->get();

        return view('brands.index', compact('brands'));
    }
}
