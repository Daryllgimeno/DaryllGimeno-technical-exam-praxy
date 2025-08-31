<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by keyword
        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->keyword.'%')
                  ->orWhere('description', 'like', '%'.$request->keyword.'%');
            });
        }

        // Filter by category
        if ($request->category) {
        $query->where('category', $request->category);
    }

        // Paginate results (10 per page)
        $products = $query->paginate(10);

        return response()->json($products);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
