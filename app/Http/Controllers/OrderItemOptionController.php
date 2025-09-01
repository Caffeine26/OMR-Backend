<?php

namespace App\Http\Controllers;

use App\Models\OrderItemOption;
use Illuminate\Http\Request;

class OrderItemOptionController extends Controller
{
    // List all order item options
    public function index()
    {
        $options = OrderItemOption::with(['Item', 'product'])->get();
        return response()->json($options, 200);
    }

    // Show a single order item option
    public function show($id)
    {
        $option = OrderItemOption::with(['Item', 'product'])->find($id);
        return response()->json($option, 200);
    }

    // Create a new order item option
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'product_id'    => 'nullable|exists:products,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
        ]);

        $option = OrderItemOption::create($validated);
        return response()->json($option, 201);
    }

    // Update an existing order item option
    public function update(Request $request, $id)
    {
        $option = OrderItemOption::findOrFail($id);

        $validated = $request->validate([
            'order_item_id' => 'sometimes|required|exists:order_items,id',
            'product_id'    => 'sometimes|nullable|exists:products,id',
            'name'          => 'sometimes|required|string|max:255',
            'price'         => 'sometimes|required|numeric|min:0',
        ]);

        $option->update($validated);
        return response()->json($option, 200);
    }

    // Delete an order item option
    public function destroy($id)
    {
        $option = OrderItemOption::findOrFail($id);
        $option->delete();

        return response()->json(['message' => 'Order item option deleted successfully'], 200);
    }
}
