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

                             
        $topCategory = $categories->sortByDesc('count')->first();


        $recentProduct = Product::latest()->first();

        return inertia('Dashboard', [
            'productCount' => Product::count(),
            'userCount' => User::count(),
          
            'categoryLabels' => $categories->pluck('category'),
            'categoryData' => $categories->pluck('count'),
             'topCategory'     => $topCategory ? [
                'name'  => $topCategory->category,
                'count' => $topCategory->count,
            ] : null,
            'recentProduct'   => $recentProduct ? [
                'name'       => $recentProduct->name,
                'created_at' => $recentProduct->created_at->toDateTimeString(),
            ] : null,
        ]);
    }
}
