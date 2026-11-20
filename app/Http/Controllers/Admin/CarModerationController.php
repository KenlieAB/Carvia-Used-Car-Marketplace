<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['carModel.brand', 'user', 'city', 'primaryImage']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('plate_region', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $cars = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all'      => Car::count(),
            'active'   => Car::where('status', 'active')->count(),
            'sold'     => Car::where('status', 'sold')->count(),
            'rejected' => Car::where('status', 'rejected')->count(),
        ];

        return view('admin.cars.index', compact('cars', 'statusCounts'));
    }

    public function reject(Car $car)
    {
        $car->update(['status' => 'rejected']);

        return back()->with('success', "Iklan \"{$car->title}\" berhasil ditangguhkan/ditolak (rejected).");
    }

    public function restore(Car $car)
    {
        $car->update([
            'status' => 'active',
            'expired_at' => now()->addDays(30),
        ]);

        return back()->with('success', "Iklan \"{$car->title}\" berhasil diaktifkan kembali.");
    }

    public function destroy(Car $car)
    {
        $car->load('images');

        foreach ($car->images as $img) {
            $filePath = str_replace('/storage/', '', $img->path);
            Storage::disk('public')->delete($filePath);
        }

        $title = $car->title;
        $car->delete();

        return back()->with('success', "Iklan \"{$title}\" berhasil dihapus permanen oleh admin.");
    }
}
