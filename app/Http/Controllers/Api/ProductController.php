<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show form for creating a product
     */
    public function create()
    {
        $categories = Product::select('category')->distinct()->pluck('category');

        return inertia('Products/CreateProduct', [
            'categories' => $categories
        ]);
    }

    /**
     * List products with optional search and filter
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('description', 'like', "%{$request->keyword}%"));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->paginate(10);

        return response()->json($products);

    }

    /**
     * Store a new product
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $images = $this->handleImages($request);

        $product = Product::create(array_merge($validated, ['images' => $images]));

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Show form to edit a product
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Product::select('category')->distinct()->pluck('category');

        return inertia('Products/EditProduct', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update a product
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();

        // Merge existing images with new uploaded images
        $images = array_merge($product->images ?? [], $this->handleImages($request));

        $product->update(array_merge($validated, ['images' => $images]));

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Show a single product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['data' => $product]);
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Handle uploaded images
     */
    private function handleImages(ProductRequest $request): array
    {
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('products', 'public');
            }
        }

        return $images;
    }
}
