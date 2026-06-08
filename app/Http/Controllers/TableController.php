<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('activeOrder.customer')->orderBy('table_number')->get();
        $stats = [
            'total' => $tables->count(),
            'available' => $tables->where('status', 'available')->count(),
            'occupied' => $tables->where('status', 'occupied')->count(),
            'reserved' => $tables->where('status', 'reserved')->count(),
        ];
        return view('tables.index', compact('tables', 'stats'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|unique:tables',
            'name' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'nullable|string|max:100',
        ]);
        $data['status'] = 'available';
        Table::create($data);
        return redirect()->route('tables.index')->with('success', 'Table created successfully.');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $data = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number,' . $table->id,
            'name' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,inactive',
        ]);
        $table->update($data);
        return redirect()->route('tables.index')->with('success', 'Table updated successfully.');
    }

    public function destroy(Table $table)
    {
        if ($table->activeOrder) {
            return back()->with('error', 'Cannot delete table with active order.');
        }
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }

    public function updateStatus(Request $request, Table $table)
    {
        $request->validate(['status' => 'required|in:available,occupied,reserved,inactive']);
        $table->update(['status' => $request->status]);
        return response()->json(['success' => true, 'status' => $table->status]);
    }
}
