<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
    //validation
    if ($request->filled('keyword')) {
        $terms = explode(' ', $request->keyword); 

        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
            }
        });
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
    public function store(ProductRequest $request)//Product request separate for validation
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

    $savedImages = $request->input('saved_images', []); 
    $removedImages = $request->input('removed_images', []); 

    //
    foreach ($removedImages as $img) {
        if (Storage::exists('public/' . $img)) {
            Storage::delete('public/' . $img);
        }
    }

 
    $newImages = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $newImages[] = $file->store('products', 'public');
        }
    }

    
    $allImages = array_merge($savedImages, $newImages);

    
    $product->update(array_merge($validated, ['images' => $allImages]));

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

    //for images in the mainproduct.vue
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
    //bulk delete in mainproduct vue 
    public function BulkDeletion(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer|exists:products,id'
    ]);

    Product::whereIn('id', $request->ids)->delete();

    return response()->json(['message' => 'Products deleted successfully']);
}
}
