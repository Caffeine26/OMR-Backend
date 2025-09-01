<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    // Fetch all promotions with items
    public function index()
    {
        try {
            $promotions = Promotion::with('promotionItems')->get();

            return response()->json([
                'message' => 'Promotions fetched successfully',
                'data' => $promotions
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch promotions: '.$e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch promotions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single promotion with its items and customizations
    public function show($id)
    {
        $promotion = Promotion::with('promotionItems')->find($id);

        if (!$promotion) {
            return response()->json([
                'message' => 'Promotion not found'
            ], 404);
        }

        // For each promotion item, attach all add_on and modify products
        $promotion->promotionItems->transform(function ($item) {
            $item->customizations = Product::whereIn('type', ['add_on', 'modify'])->get();
            return $item;
        });

        return response()->json([
            'message' => 'Promotion fetched successfully',
            'data' => $promotion
        ], 200);
    }

    // Store new promotion
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_english' => 'required|string|max:255',
            'name_khmer'   => 'required|string|max:255',
            'detail_des'   => 'nullable|string',
            'date'         => 'required|date',
            'discount'     => 'nullable|numeric',
            'type'         => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date',
            'image'        => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
            $data['image'] = $path;
        }

        $promotion = Promotion::create($data);

        return response()->json([
            'message' => 'Promotion created successfully',
            'data' => $promotion
        ], 201);
    }

    // Update promotion
    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $validated = $request->validate([
            'name_english' => 'sometimes|required|string|max:255',
            'name_khmer'   => 'nullable|string|max:255',
            'detail_des'   => 'nullable|string',
            'date'         => 'nullable|date',
            'discount'     => 'sometimes|required|numeric|min:0|max:100',
            'type'         => 'sometimes|required|string|in:percentage,fixed,other',
            'start_date'   => 'nullable|date|before_or_equal:end_date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ]);

        $promotion->update($validated);

        return response()->json([
            'message' => 'Promotion updated successfully',
            'data' => $promotion
        ], 200);
    }

    // Delete promotion
    public function destroy($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $promotion->delete();

        return response()->json([
            'message' => 'Promotion deleted successfully'
        ], 200);
    }
}
