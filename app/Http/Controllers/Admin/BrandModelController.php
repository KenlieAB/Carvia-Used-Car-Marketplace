<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandModelController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('carModels')
            ->with(['carModels' => function ($q) {
                $q->orderBy('name');
            }])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'country' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Brand::create($validated);

        return back()->with('success', "Merek \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function storeModel(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:100',
            'body_type' => 'required|in:suv,mpv,sedan,hatchback,city_car,coupe,commercial',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        CarModel::create($validated);

        return back()->with('success', "Model \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function destroyBrand(Brand $brand)
    {
        $name = $brand->name;
        $brand->delete();

        return back()->with('success', "Merek \"{$name}\" dan seluruh modelnya berhasil dihapus.");
    }

    public function destroyModel(CarModel $model)
    {
        $name = $model->name;
        $model->delete();

        return back()->with('success', "Model \"{$name}\" berhasil dihapus.");
    }
}
