<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\KitchenOrder;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['tables', 'customer', 'items', 'payment']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->type) $query->where('type', $request->type);
        if ($request->date) $query->whereDate('created_at', $request->date);
        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        $orders = $query->latest()->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['tables', 'customer', 'items.menuItem', 'payment', 'invoice', 'delivery', 'waiter', 'kitchenOrders']);
        return view('orders.show', compact('order'));
    }

    public function print(Order $order)
    {
        $order->load(['tables', 'customer', 'items', 'payment']);
        return view('orders.print', compact('order'));
    }

    public function create()
    {
        $tables = Table::available()->get();
        $customers = Customer::where('status', 'active')->get();
        $categories = \App\Models\Category::with('activeMenuItems')->where('status', true)->get();
        return view('orders.create', compact('tables', 'customers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:dine_in,takeaway,delivery',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'table_ids' => 'nullable|array',
            'table_ids.*' => 'exists:tables,id',
        ]);

        DB::beginTransaction();
        try {
            $setting = \App\Models\RestaurantSetting::first();
            $orderNumber = ($setting->order_prefix ?? 'ORD-') . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $taxAmount = 0;
            $items = [];

            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $price = $menuItem->effective_price;
                $qty = $item['quantity'];
                $tax = round($price * $qty * $menuItem->tax_rate / 100, 2);
                $itemSubtotal = round($price * $qty, 2);

                $subtotal += $itemSubtotal;
                $taxAmount += $tax;
                $items[] = [
                    'menu_item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'unit_price' => $price,
                    'quantity' => $qty,
                    'tax_rate' => $menuItem->tax_rate,
                    'tax_amount' => $tax,
                    'subtotal' => $itemSubtotal + $tax,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $total = $subtotal + $taxAmount;

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'waiter_id' => $request->waiter_id,
                'type' => $request->type,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($items as $item) {
                $orderItem = $order->items()->create($item);
                KitchenOrder::create([
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'status' => 'pending',
                ]);
            }

            if (!empty($request->table_ids)) {
                $order->tables()->sync($request->table_ids);
                Table::whereIn('id', $request->table_ids)->update(['status' => 'occupied']);
            }

            // Create invoice
            $invoiceNumber = ($setting->invoice_prefix ?? 'INV-') . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
                'status' => 'issued',
                'issued_at' => now(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('orders.show', $order)->with('success', 'Order #' . $orderNumber . ' created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled']);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $timestamps = [
            'confirmed' => 'confirmed_at',
            'ready' => 'ready_at',
            'served' => 'served_at',
            'completed' => 'completed_at',
        ];

        $updateData = ['status' => $newStatus];
        if (isset($timestamps[$newStatus])) {
            $updateData[$timestamps[$newStatus]] = now();
        }

        $order->update($updateData);

        if (in_array($newStatus, ['completed', 'cancelled']) && $order->tables->count() > 0) {
            Table::whereIn('id', $order->tables->pluck('id'))->update(['status' => 'available']);
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $newStatus]);
        }
        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus));
    }

    public function settlePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,mobile_banking,split',
            'payment_amount' => 'required|numeric|min:' . $order->total_amount,
        ]);

        DB::beginTransaction();
        try {
            $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'payment_number' => $paymentNumber,
                'amount' => $order->total_amount,
                'method' => $request->payment_method,
                'status' => 'completed',
                'change_amount' => max(0, $request->payment_amount - $order->total_amount),
                'split_details' => $request->split_details,
                'processed_by' => auth()->id(),
                'paid_at' => now(),
            ]);

            if ($order->invoice) {
                $order->invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            $order->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            if ($order->tables->count() > 0) {
                Table::whereIn('id', $order->tables->pluck('id'))->update(['status' => 'available']);
            }

            DB::commit();
            return back()->with('success', 'Payment settled successfully. Order completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to settle payment: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        if (in_array($order->status, ['completed'])) {
            return back()->with('error', 'Cannot delete completed orders.');
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
