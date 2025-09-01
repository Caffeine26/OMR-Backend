<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // List all tables
    public function index()
    {
        return response()->json(Table::all());
    }

    // Show a single table
    public function show($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        return response()->json($table);
    }

    // Create a new table
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|integer|unique:tables,table_number',
            'location_des' => 'nullable|string',
            'status' => 'nullable|in:reserved,available',
            'qr_code' => 'nullable|string',
        ]);

        $table = Table::create($validated);

        return response()->json($table, 201);
    }

    // Update an existing table
    public function update(Request $request, $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $validated = $request->validate([
            'table_number' => 'required|integer|unique:tables,table_number,' . $table->id,
            'location_des' => 'nullable|string',
            'status' => 'nullable|in:reserved,available',
            'qr_code' => 'nullable|string',
        ]);

        $table->update($validated);

        return response()->json($table);
    }

    // Delete a table
    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $table->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }
}
