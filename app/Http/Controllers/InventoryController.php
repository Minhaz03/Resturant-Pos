<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('supplier');
        if ($request->category) $query->where('category', $request->category);
        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%');
        if ($request->low_stock) $query->lowStock();
        $items = $query->latest()->paginate(15);
        $lowStockCount = InventoryItem::lowStock()->count();
        return view('inventory.index', compact('items', 'lowStockCount'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        return view('inventory.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|unique:inventory_items',
            'category' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'unit' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'min_quantity' => 'required|numeric|min:0',
            'max_quantity' => 'nullable|numeric',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $data['total_value'] = $data['quantity'] * $data['unit_cost'];
        $data['status'] = 'active';
        $item = InventoryItem::create($data);

        if ($data['quantity'] > 0) {
            InventoryTransaction::create(['inventory_item_id' => $item->id, 'type' => 'purchase', 'quantity' => $data['quantity'], 'unit_cost' => $data['unit_cost'], 'total_cost' => $data['total_value'], 'balance_after' => $data['quantity'], 'notes' => 'Initial stock', 'created_by' => auth()->id()]);
        }

        return redirect()->route('inventory.index')->with('success', 'Inventory item created.');
    }

    public function edit(InventoryItem $inventory)
    {
        $inventoryItem = $inventory;
        $suppliers = Supplier::where('status', 'active')->get();
        return view('inventory.edit', compact('inventoryItem', 'suppliers'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $inventoryItem = $inventory;
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|unique:inventory_items,sku,' . $inventoryItem->id,
            'category' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit' => 'required|string',
            'min_quantity' => 'required|numeric|min:0',
            'max_quantity' => 'nullable|numeric',
            'unit_cost' => 'required|numeric|min:0',
        ]);
        $data['total_value'] = $inventoryItem->quantity * $data['unit_cost'];
        $inventoryItem->update($data);
        return redirect()->route('inventory.index')->with('success', 'Inventory item updated.');
    }

    public function adjust(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'type' => 'required|in:purchase,usage,adjustment,waste,return',
            'quantity' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $qty = $request->type === 'usage' || $request->type === 'waste' ? -abs($request->quantity) : abs($request->quantity);
        $newBalance = max(0, $inventoryItem->quantity + $qty);

        $inventoryItem->update(['quantity' => $newBalance, 'total_value' => $newBalance * $inventoryItem->unit_cost]);
        InventoryTransaction::create(['inventory_item_id' => $inventoryItem->id, 'type' => $request->type, 'quantity' => $qty, 'unit_cost' => $inventoryItem->unit_cost, 'total_cost' => abs($qty) * $inventoryItem->unit_cost, 'balance_after' => $newBalance, 'notes' => $request->notes, 'created_by' => auth()->id()]);

        return back()->with('success', 'Stock adjusted successfully.');
    }

    public function show(InventoryItem $inventory)
    {
        $inventoryItem = $inventory;
        $transactions = $inventoryItem->transactions()->with('createdBy')->latest()->paginate(20);
        return view('inventory.show', compact('inventoryItem', 'transactions'));
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventoryItem = $inventory;
        $inventoryItem->delete();
        return redirect()->route('inventory.index')->with('success', 'Item deleted.');
    }
}
