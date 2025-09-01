<?php

namespace App\Http\Controllers;

use App\Models\CustomerCoupon;
use Illuminate\Http\Request;

class CustomerCouponController extends Controller
{
    // List all customer coupons
    public function index()
    {
       $customerCoupons = CustomerCoupon::all();
       return response()->json($customerCoupons);
    }

    // Show a single customer coupon
    public function show($id)
    {
        $customerCoupon = CustomerCoupon::find($id);
        if(!$customerCoupon){
            return response()->json(['message'=>'Customer coupon not found'], 404);
        }
        return response()->json($customerCoupon);
    }

    // Create a new customer coupon
    public function store(Request $request)
    {
        $validated=$request->validate([
            'customer_id'   =>'required|exists:customers,id',
            'coupon_id'     =>'required|exists:coupons,id',
            'status'        =>'sometimes|in:available,used',
            'started_date'  =>'required|date',
            'ended_date'    =>'required|date|after:started_date',

        ]);
         $customerCoupon = CustomerCoupon::create($validated);
        
        return response()->json([
            'message'=> 'Customer coupon created successfully',
            'data'  => $customerCoupon
            
        ],201);
    }

    // Update an existing customer coupon
    public function update(Request $request, $id)
    {
        $customerCoupon =CustomerCoupon::find($id);
        if (!$customerCoupon){
            return response()->json(['message'=>'Customer coupon not found'],404);
        }
        $validated = $request->validate([
            'customer_id'=>'sometimes|exists:customers,id',
            'coupon_id' =>'sometimes|exists:coupons,id',
            'started_date'=>'sometimes|date',
            'ended_date'  =>'sometimes|date|after:started_date',
            'status'      =>'sometimes|in:available,used'
        ]);
        $customerCoupon->update($validated);
        return response()->json([
            'message' =>'Customer coupon updated successfully',
            'data'    =>$customerCoupon
        ]);
    }

    // Delete a customer coupon
    public function destroy($id)
    {
        $customerCoupon =CustomerCoupon::find($id);
        if(!$customerCoupon){
            return response()->json(['message'=>' Customer coupon is not found'],404);
        }
        $customerCoupon->delete();
        return response()->json(['message'=> 'Customer coupon deleted successfully', 205]);
    }
}
