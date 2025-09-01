<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        
        $data = [
            'productCount' => Product::count(),
            'userCount'    => User::count(),
          
        ];

        return Inertia::render('Dashboard', $data);
    }
}
