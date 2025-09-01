<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    // List all order items with index
    public function index()
    {
        try{
            $items = OrderItem::with(['product','options',])->get();
            return response()->json([
                'message'=> 'Order item fetched successfully.',
                'data'   => $items
            ], 200);

        }catch(\Exceptuion $e){
            Log::error('Failed to fetch orders: '. $e->getMessage());
                    return response()->json([
                        'message'=>' Failed to fetch orders',
                        'error' => $e->getMessage()
                    ],500);
        }
      
    }

    // Show a single order item
    public function show($id)
    {
        try{

            $item = OrderItem::with(['product', 'options'])->findOrFail($id);
            return response()->json([
                'message'=> 'Order fetched successfully',
                'data' => $item
            ],200);

        }catch(\Exeptution $e){
            Log::error('Failed to fetch other by id: '. $e->getMessage());
                    return response()->json([
                        'message'=> 'Failed to fetch orders by id',
                        'error'=> $e->getMessage()
                    ],500);
        }
        
    }

    // Create a new order item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'  =>'required|exists:orders,id',
            'product_id' =>'nullable|exists:products,id',
            'quantity'  =>'required|integer|min:1',
            'base_price'=> 'required|numeric|min:0',
            'price'     => 'required|numeric|min:0',
            'discount'  =>  'nullable|numeric|min:0',
            'note'      =>  'nullable|string|max:500',
        ]);
        $orderItem= OrderItem::create($validated);
        return response()->json([
            'message' => 'Order created successfully',
            'orderItem'   => $orderItem
        ], 201);

    }

    // Update an existing order item
    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::find($id);
        if(!$orderItem){
            return response()->json(['message'=> 'Order Item not found'], 404);
        }
        $validated =$request->validate([
            'quantity'  =>'sometimes|required|integer|min:1',
            'base_price'=>'sometimes|required|numeric|min:0',
            'price'     =>'sometimes|required|numeric|min:0',
            'discount'  =>'nullable|numeric|min:0',
            'note'      =>'nullable|string|max:500',
        ]);
        $orderItem->update($validated);
        return response()->json($orderItem);
        }

    // Delete an order item
    public function destroy($id)
    {
        $orderItem = OrderItem::find($id);
        if(!$orderItem){
            return response()->json(['message'=> 'Order item not found'],404);

        }
        $orderItem->delete();
        return response()->json(['message'=> 'Order deleted successfully'],200);
    }
}
