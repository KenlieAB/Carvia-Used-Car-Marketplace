<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// 1. Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Browse Cars & Detail
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
Route::get('/cars/{slug}', [CarController::class, 'show'])->name('cars.show');

// 3. Brands Directory
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

// 4. How It Works & Information
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');

// 5. User Ads (Garasi Saya)
Route::get('/my-ads', [PageController::class, 'myAds'])->name('my-ads');
