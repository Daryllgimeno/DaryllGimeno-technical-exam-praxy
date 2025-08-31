<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
{
   $categories = Product::select('category')->distinct()->pluck('category');

    return inertia('Products/CreateProduct', [
        'categories' => $categories
    ]);
}
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

   public function store(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_time' => 'nullable|date',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048', // multiple images
    ]);

    $images = [];

    // Handle uploaded images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            // Store each file in storage/app/public/products
            $path = $file->store('products', 'public');
            $images[] = $path;
        }
    }

    // Create product with images
    $product = Product::create([
        'name' => $request->name,
        'category' => $request->category,
        'description' => $request->description,
        'date_time' => $request->date_time,
        'images' => $images, 
    ]);

    return response()->json([
        'message' => 'Product created successfully',
        'data' => $product
    ], 201);
}

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
