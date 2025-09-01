<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Product::select('category')
                             ->selectRaw('COUNT(*) as count')
                             ->groupBy('category')
                             ->get();

        return inertia('Dashboard', [
            'productCount' => Product::count(),
            'userCount' => User::count(),
            'orderCount' => 0,
            'errorCount' => 0,
            'categoryLabels' => $categories->pluck('category'),
            'categoryData' => $categories->pluck('count'),
        ]);
    }
}
