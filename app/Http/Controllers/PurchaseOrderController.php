<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');
        if ($request->status) $query->where('status', $request->status);
        $purchaseOrders = $query->latest()->paginate(15);
        return view('purchases.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $inventoryItems = InventoryItem::where('status', 'active')->get();
        return view('purchases.create', compact('suppliers', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
            }

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_date' => $request->expected_date,
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'status' => 'ordered',
                'payment_status' => 'unpaid',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $po->items()->create([
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['quantity'] * $item['unit_cost'],
                    'unit' => $item['unit'] ?? 'kg',
                ]);
            }

            Supplier::find($request->supplier_id)?->increment('total_purchased', $subtotal);
            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Purchase Order #' . $poNumber . ' created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.inventoryItem', 'createdBy']);
        return view('purchases.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        DB::beginTransaction();
        try {
            foreach ($purchaseOrder->items as $item) {
                $receivedQty = $request->received[$item->id] ?? $item->quantity;
                $item->update(['received_quantity' => $receivedQty]);
                $invItem = $item->inventoryItem;
                $newBalance = $invItem->quantity + $receivedQty;
                $invItem->update(['quantity' => $newBalance, 'total_value' => $newBalance * $invItem->unit_cost]);
                InventoryTransaction::create(['inventory_item_id' => $item->inventory_item_id, 'type' => 'purchase', 'quantity' => $receivedQty, 'unit_cost' => $item->unit_cost, 'total_cost' => $receivedQty * $item->unit_cost, 'balance_after' => $newBalance, 'reference' => $purchaseOrder->po_number, 'created_by' => auth()->id()]);
            }
            $purchaseOrder->update(['status' => 'received', 'received_date' => now(), 'payment_status' => 'paid']);
            DB::commit();
            return back()->with('success', 'Purchase order received and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
