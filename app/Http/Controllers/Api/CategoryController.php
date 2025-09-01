<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        
        $categories = Product::select('category')->distinct()->pluck('category');

        return response()->json($categories);
    }
}
