<?php

namespace App\Http\Controllers;

use App\Models\KitchenOrder;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $kitchenOrders = KitchenOrder::with([
                'order.tables',
                'orderItem.menuItem.ingredients.inventoryItem',
            ])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at')
            ->get()
            ->groupBy('order_id');

        $readyOrders = KitchenOrder::with([
                'order.tables',
                'orderItem.menuItem.ingredients.inventoryItem',
            ])
            ->where('status', 'ready')
            ->whereDate('created_at', today())
            ->latest('completed_at')
            ->take(20)->get()
            ->groupBy('order_id');

        $servedOrders = KitchenOrder::with(['order.tables', 'orderItem.menuItem'])
            ->where('status', 'served')
            ->whereDate('created_at', today())
            ->latest('updated_at')
            ->get()
            ->groupBy('order_id');

        return view('kitchen.index', compact('kitchenOrders', 'readyOrders', 'servedOrders'));
    }

    public function updateStatus(Request $request, KitchenOrder $kitchenOrder)
    {
        $request->validate(['status' => 'required|in:preparing,ready,served']);

        $updateData = ['status' => $request->status];
        if ($request->status === 'preparing') $updateData['started_at'] = now();
        if ($request->status === 'ready') $updateData['completed_at'] = now();

        $kitchenOrder->update($updateData);

        // Update order item status too
        $kitchenOrder->orderItem?->update(['status' => $request->status]);

        // Check if all kitchen orders for this order are ready or served
        if ($request->status === 'ready') {
            $order = $kitchenOrder->order;
            $pendingItems = $order->kitchenOrders()->whereNotIn('status', ['ready', 'served'])->count();
            if ($pendingItems === 0 && !in_array($order->status, ['ready', 'served', 'completed'])) {
                $order->update(['status' => 'ready', 'ready_at' => now()]);
            }
        } elseif ($request->status === 'served') {
            $order = $kitchenOrder->order;
            $pendingItems = $order->kitchenOrders()->whereNotIn('status', ['served'])->count();
            if ($pendingItems === 0 && !in_array($order->status, ['served', 'completed'])) {
                $order->update(['status' => 'served', 'served_at' => now()]);
            }
        }

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    public function serveOrder(Order $order)
    {
        $kitchenOrders = $order->kitchenOrders()->where('status', 'ready')->get();
        foreach ($kitchenOrders as $ko) {
            $ko->update(['status' => 'served']);
            $ko->orderItem?->update(['status' => 'served']);
        }
        
        $pendingItems = $order->kitchenOrders()->whereNotIn('status', ['served'])->count();
        if ($pendingItems === 0 && !in_array($order->status, ['served', 'completed'])) {
            $order->update(['status' => 'served', 'served_at' => now()]);
        }
        
        return response()->json(['success' => true]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $status = $request->status;
        
        // Ensure status is valid for bulk update
        if (!in_array($status, ['preparing', 'ready'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }

        // Only update items that make sense (pending to preparing, preparing to ready)
        $currentStatus = $status === 'preparing' ? 'pending' : 'preparing';
        
        $kitchenOrders = $order->kitchenOrders()->where('status', $currentStatus)->get();
        
        foreach ($kitchenOrders as $ko) {
            $ko->update(['status' => $status]);
            if ($status === 'preparing' && !$ko->started_at) {
                $ko->update(['started_at' => now()]);
            }
            $ko->orderItem?->update(['status' => $status]);
        }
        
        if ($status === 'ready') {
            $pendingItems = $order->kitchenOrders()->whereNotIn('status', ['ready', 'served'])->count();
            if ($pendingItems === 0 && !in_array($order->status, ['ready', 'served', 'completed'])) {
                $order->update(['status' => 'ready', 'ready_at' => now()]);
            }
        }
        
        return response()->json(['success' => true]);
    }

    public function getNewOrders()
    {
        $orders = KitchenOrder::with(['order.tables', 'orderItem.menuItem'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at')
            ->get()->groupBy('order_id');

        return response()->json(['orders' => $orders->map(function($items, $orderId) {
            $order = $items->first()->order;
            return ['order_id' => $orderId, 'order_number' => $order->order_number, 'table' => $order->tables->pluck('table_number')->implode(', ') ?: null, 'items' => $items->map(fn($k) => ['id' => $k->id, 'name' => $k->orderItem?->menuItem?->name, 'qty' => $k->orderItem?->quantity, 'notes' => $k->orderItem?->notes, 'status' => $k->status, 'elapsed' => $k->elapsed_time]), 'created_at' => $order->created_at->diffForHumans()];
        })->values()]);
    }
}
