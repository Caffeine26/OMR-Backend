<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // List all coupons
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json($coupons, 200);
    }

    // Show a single coupon
    public function show($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
        return response()->json($coupon, 200);
    }

    // Create a new coupon
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'description' => 'nullable|string',
            'name_english' => 'required|string|max:50',
            'min_order' => 'nullable|numeric',
            'discount_type' => 'required|in:percent,decimal',
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'active' => 'required|boolean',
        ]);

        $coupon = Coupon::create($validated);
        return response()->json($coupon, 201); // 201 Created
    }

    // Update an existing coupon
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id,
            'description' => 'nullable|string',
            'name_english' => 'required|string|max:50',
            'min_order' => 'nullable|numeric',
            'discount_type' => 'required|in:percent,decimal',
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'active' => 'required|boolean',
        ]);

        $coupon->update($validated);
        return response()->json($coupon, 202); // 202 Accepted
    }

    // Delete a coupon
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted successfully'], 200);
    }
}
