<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryOrder::with(['order.customer', 'rider']);
        if ($request->status) $query->where('status', $request->status);
        $deliveries = $query->latest()->paginate(15);
        $riders = User::role('delivery_staff')->where('status', 'active')->get();
        return view('delivery.index', compact('deliveries', 'riders'));
    }

    public function assign(Request $request, DeliveryOrder $delivery)
    {
        $request->validate(['rider_id' => 'required|exists:users,id']);
        $delivery->update(['rider_id' => $request->rider_id, 'status' => 'assigned', 'assigned_at' => now()]);
        return back()->with('success', 'Rider assigned.');
    }

    public function updateStatus(Request $request, DeliveryOrder $delivery)
    {
        $request->validate(['status' => 'required|in:pending,assigned,picked_up,on_way,delivered,failed,cancelled']);
        $updateData = ['status' => $request->status];
        if ($request->status === 'picked_up') $updateData['picked_up_at'] = now();
        if ($request->status === 'delivered') $updateData['delivered_at'] = now();
        $delivery->update($updateData);
        if ($request->status === 'delivered') {
            $delivery->order?->update(['status' => 'completed', 'completed_at' => now()]);
        }
        return back()->with('success', 'Status updated.');
    }
}
