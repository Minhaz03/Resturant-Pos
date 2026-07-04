<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menuItem', 'tables', 'customer'])->latest()->paginate(20);
        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load(['items.menuItem', 'tables', 'customer', 'payment']);
        return response()->json(['data' => $order]);
    }

    public function store(Request $request)
    {
        // Delegated to web controller logic
        return response()->json(['message' => 'Use web POS for order creation.'], 405);
    }

    public function update(Request $request, Order $order)
    {
        return response()->json(['message' => 'Use status update endpoint.'], 405);
    }

    public function destroy(Order $order)
    {
        return response()->json(['message' => 'Delete not allowed via API.'], 405);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return response()->json(['data' => $order]);
    }
}
