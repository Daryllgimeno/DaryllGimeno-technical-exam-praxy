<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route
Route::get('/dashboard', function () {
    return inertia('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', function () {
        return inertia('Products/MainProductPage');
    })->name('products.index');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
});

require __DIR__.'/auth.php';
