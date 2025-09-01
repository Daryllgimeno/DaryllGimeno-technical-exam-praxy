<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Products API
Route::get('/products', [ProductController::class, 'index']);        // List products
Route::post('/products', [ProductController::class, 'store']);       // Create product
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Delete product
Route::get('/products/{id}', [ProductController::class, 'show']);   // Get single product for edit
Route::put('/products/{id}', [ProductController::class, 'update']); // Update product
