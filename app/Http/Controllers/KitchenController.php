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
        $kitchenOrders = KitchenOrder::with(['order.table', 'orderItem.menuItem'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at')
            ->get()
            ->groupBy('order_id');

        $readyOrders = KitchenOrder::with(['order.table', 'orderItem.menuItem'])
            ->where('status', 'ready')
            ->whereDate('created_at', today())
            ->latest('completed_at')
            ->take(20)->get()
            ->groupBy('order_id');

        return view('kitchen.index', compact('kitchenOrders', 'readyOrders'));
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

        // Check if all kitchen orders for this order are ready
        if ($request->status === 'ready') {
            $order = $kitchenOrder->order;
            $pendingItems = $order->kitchenOrders()->whereNotIn('status', ['ready', 'served'])->count();
            if ($pendingItems === 0) {
                $order->update(['status' => 'ready', 'ready_at' => now()]);
            }
        }

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    public function getNewOrders()
    {
        $orders = KitchenOrder::with(['order.table', 'orderItem.menuItem'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at')
            ->get()->groupBy('order_id');

        return response()->json(['orders' => $orders->map(function($items, $orderId) {
            $order = $items->first()->order;
            return ['order_id' => $orderId, 'order_number' => $order->order_number, 'table' => $order->table?->table_number, 'items' => $items->map(fn($k) => ['id' => $k->id, 'name' => $k->orderItem?->menuItem?->name, 'qty' => $k->orderItem?->quantity, 'notes' => $k->orderItem?->notes, 'status' => $k->status, 'elapsed' => $k->elapsed_time]), 'created_at' => $order->created_at->diffForHumans()];
        })->values()]);
    }
}
