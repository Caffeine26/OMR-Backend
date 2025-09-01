<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // List all customers
    public function index()
    {
        return response()->json(Customer::all());
    }

    // Show a single customer
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    // Create a new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:customers,phone_number',
            'email' => 'nullable|email|unique:customers,email',
            'telegram_url' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    // Update an existing customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|unique:customers,phone_number,' . $customer->id,
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'telegram_url' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    // Delete a customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
