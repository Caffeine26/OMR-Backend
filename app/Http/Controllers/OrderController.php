<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List all orders
    public function index()
{ 
    try{
         $orders = Order::with(['Items.options','Items.product'])->get();
        return response()->json([
            'message'=>'orders fetch successfully',
            'data'=>$orders
        ]);

    }catch(\Exceptuion $e){
        Log::error('Failed to fetch orders: '. $e->getMessage());
        return response()->json([
            'message'=>' Failed to fetch orders',
            'error' => $e->getMessage()
        ],500);
    }
       
    }

    // Show a single order
    public function show(Order $order)
    {
        try{
            $oder->load(['Items.options','Items.product','feedbacks']);
            return response()->json([
                'message'=>'Order fetch successfully',
                'data'=>$orders

            ],200);

        }catch(\Exceptuion $e){
            Log::error('Failed to fetch order: '. $e->getMessage());
            return response()->json([
                'message'=>'Failed to fetch orders',
                'error'=> $e->getMessage()
            ],500);
        }
        
    }

    // Create a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id'      => 'required|exists:tables,id',
            'coupon_id'     => 'nullable|exists:coupons,id',
            'customer_id'   => 'nullable|exists:customers,id',
            'status'        => 'required|in:ordering,submitted,pending,ready',
            'total_price'   => 'required|numeric|min:0',
            'special_note'  => 'nullable|string',
            'order_time'    => 'nullable|date',
            'estimate_time' => 'nullable|date|after_or_equal:order_time',
        ]);

        $order = Order::create($validated);

        return response()->json([
            'message' => 'Order created successfully',
            'order'   => $order
        ], 201);
    }

    // Update an existing order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'table_id'      => 'required|exists:tables,id',
            'coupon_id'     => 'nullable|exists:coupons,id',
            'customer_id'   => 'nullable|exists:customers,id',
            'status'        => 'sometimes|in:ordering,submitted,pending,ready',
            'total_price'   => 'sometimes|numeric|min:0',
            'special_note'  => 'nullable|string',
            'order_time'    => 'sometimes|date',
            'estimate_time' => 'nullable|date|after_or_equal:order_time',
        ]);

        $order->update($validated);

        return response()->json([
            'message'=> 'Order updated successfully',
            'order'  => $order
        ], 200);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
