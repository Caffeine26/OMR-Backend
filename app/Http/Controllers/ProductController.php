<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // don't forget to import Log

class ProductController extends Controller
{
    // =================== INDEX ===================
    public function index(Request $request)
    {
        try {
            $type = $request->query('type'); // menu, add_on, modify
            $category_id = $request->query('category_id');

            $products = Product::when($type, function ($query, $type) {
                    return $query->where('type', $type);
                })
                ->when($category_id, function ($query, $category_id) {
                    return $query->where('category_id', $category_id);
                })
                ->with('options') // <-- load modify/add_on items
                ->get();

            return response()->json([
                'message' => 'Products fetched successfully',
                'data' => $products
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch products: '. $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =================== SHOW ===================
    public function show($id)
    {
        try {
            $product = Product::with(['promotionItems', 'options'])->find($id);

            if (!$product) {
                return response()->json(['message'=> 'Product not found'], 404);
            }

            return response()->json([
                'message' => 'Product fetched successfully',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch product by id: '.$e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch product by id',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =================== STORE ===================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'nullable|in:menu,add_on,modify',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048', // validate image
            'parent_id' => 'nullable|exists:products,id', // new for modify/add_on
        ]);

        // Save image
        $imagePath = $request->file('image')->store('images/products', 'public');

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $imagePath,
            'type' => $request->type ?? 'menu', // default to menu
            'parent_id' => $request->parent_id ?? null, // link to menu if modify/add_on
        ]);

        return response()->json($product, 201);
    }

    // =================== UPDATE ===================
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric',
            'rate'        => 'nullable|numeric|min:0|max:5',
            'type'        => 'sometimes|in:menu,add_on,modify',
            'image'       => 'nullable|image|max:2048', // allow file upload
            'parent_id'   => 'nullable|exists:products,id', // new
        ]);

        // Handle image file if uploaded
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $validated['image_url'] = $imagePath;
        }

        $product->update($validated);

        return response()->json($product, 200);
    }

    // =================== DESTROY ===================
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
