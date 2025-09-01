<?php

namespace App\Http\Controllers;

use App\Models\PromotionItem;
use Illuminate\Http\Request;

class PromotionItemController extends Controller
{
    // 1. Get all promotion items with related product & promotion
    public function index()
    {
        $items = PromotionItem::with(['promotion', 'product'])->get();

        return response()->json([
            'message' => 'Promotion items fetched successfully',
            'data' => $items
        ]);
    }

    // 2. Get single promotion item by ID
    public function show($id)
    {
        $item = PromotionItem::with(['promotion', 'product'])->find($id);

        if (!$item) {
            return response()->json(['message' => 'Promotion item not found'], 404);
        }

        return response()->json([
            'message' => 'Promotion item fetched successfully',
            'data' => $item
        ]);
    }

    // 3. Get promotion items by promotion ID (optional filtering)
    public function getByPromotion($promotionId)
    {
        $items = PromotionItem::with(['promotion', 'product'])
            ->where('promotion_id', $promotionId)
            ->get();

        return response()->json([
            'message' => 'Promotion items for this promotion fetched successfully',
            'data' => $items
        ]);
    }

    // 4. Create new promotion item
    public function store(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $item = PromotionItem::create([
            'promotion_id' => $request->promotion_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Promotion item created successfully',
            'data' => $item
        ]);
    }

    // 5. Update promotion item
    public function update(Request $request, $id)
    {
        $item = PromotionItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Promotion item not found'], 404);
        }

        $request->validate([
            'promotion_id' => 'sometimes|exists:promotions,id',
            'product_id' => 'sometimes|exists:products,id',
        ]);

        $item->update($request->only(['promotion_id', 'product_id']));

        return response()->json([
            'message' => 'Promotion item updated successfully',
            'data' => $item
        ]);
    }

    // 6. Delete promotion item
    public function destroy($id)
    {
        $item = PromotionItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Promotion item not found'], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Promotion item deleted successfully'
        ]);
    }
}
