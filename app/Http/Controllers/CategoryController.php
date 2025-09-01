<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                return response()->json([
                    'message' => 'No categories found'
                ], 404);
            }

            // Add full image URL before returning
            $categories->transform(function ($category) {
                if ($category->image) {
                    $category->image_url = asset('storage/' . $category->image);
                }
                return $category;
            });

            return response()->json([
                'message' => 'Categories fetched successfully',
                'data' => $categories
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch categories: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single category
    public function show($id)
    {
        try {
            $category = Category::with('products')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            }

            if ($category->image) {
                $category->image_url = asset('storage/' . $category->image);
            }

            return response()->json([
                'message' => 'Category fetched successfully',
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch category by id: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch category by id',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
{
    // 1️⃣ Validate request
    $request->validate([
        'name_khmer' => 'required|string|max:255',
        'name_english' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 2️⃣ Prepare category data
    $categoryData = $request->only(['name_khmer', 'name_english']);

    // 3️⃣ Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        // Store in 'public/images/categories' directory
        $path = $image->storeAs('images/categories', $imageName, 'public');
        $categoryData['image'] = $path; // store relative path in DB
    }

    // 4️⃣ Create category
    $category = Category::create($categoryData);

    // 5️⃣ Return response (image_url will be auto-appended by model)
    return response()->json([
        'message' => 'Category created successfully',
        'data' => $category
    ], 201);
}



    // Update an existing category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Store new image
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = $path;
        }

        $category->update($data);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
