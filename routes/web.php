<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (accessible by everyone including guests)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Browse Cars (listing — guests can browse)
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');

// Brands directory
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

// How It Works page
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');

/*
|--------------------------------------------------------------------------
| Auth-Required Routes (guests are redirected to login)
|--------------------------------------------------------------------------
*/

// Post a Car — must be logged in (HARUS DI ATAS /cars/{slug})
Route::get('/cars/create', [CarController::class, 'create'])
    ->name('cars.create')
    ->middleware('auth');

Route::post('/cars', [CarController::class, 'store'])
    ->name('cars.store')
    ->middleware('auth');

// Car Detail — guests must login to see contact info
Route::get('/cars/{slug}', [CarController::class, 'show'])
    ->name('cars.show')
    ->middleware('auth');

// My Ads / Garage
Route::get('/my-ads', [PageController::class, 'myAds'])
    ->name('my-ads')
    ->middleware('auth');

// Car Management (owner only)
Route::middleware('auth')->group(function () {
    Route::get('/cars/{slug}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{slug}', [CarController::class, 'update'])->name('cars.update');
    Route::patch('/cars/{slug}/sold', [CarController::class, 'markSold'])->name('cars.sold');
    Route::patch('/cars/{slug}/extend', [CarController::class, 'extendExpiry'])->name('cars.extend');
    Route::delete('/cars/{slug}', [CarController::class, 'destroy'])->name('cars.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by auth and admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Overview
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Car Moderation
    Route::get('/cars', [\App\Http\Controllers\Admin\CarModerationController::class, 'index'])->name('cars.index');
    Route::patch('/cars/{car}/reject', [\App\Http\Controllers\Admin\CarModerationController::class, 'reject'])->name('cars.reject');
    Route::patch('/cars/{car}/restore', [\App\Http\Controllers\Admin\CarModerationController::class, 'restore'])->name('cars.restore');
    Route::delete('/cars/{car}', [\App\Http\Controllers\Admin\CarModerationController::class, 'destroy'])->name('cars.destroy');

    // Reports Management
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');
    Route::patch('/reports/{report}/dismiss', [\App\Http\Controllers\Admin\ReportController::class, 'dismiss'])->name('reports.dismiss');

    // Brands & Models Management
    Route::get('/brands', [\App\Http\Controllers\Admin\BrandModelController::class, 'index'])->name('brands.index');
    Route::post('/brands', [\App\Http\Controllers\Admin\BrandModelController::class, 'storeBrand'])->name('brands.store');
    Route::delete('/brands/{brand}', [\App\Http\Controllers\Admin\BrandModelController::class, 'destroyBrand'])->name('brands.destroy');
    Route::post('/models', [\App\Http\Controllers\Admin\BrandModelController::class, 'storeModel'])->name('models.store');
    Route::delete('/models/{model}', [\App\Http\Controllers\Admin\BrandModelController::class, 'destroyModel'])->name('models.destroy');
});
/*
|--------------------------------------------------------------------------
| Breeze Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
