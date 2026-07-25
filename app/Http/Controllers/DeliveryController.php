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

        $stats = [
            'total'      => DeliveryOrder::count(),
            'pending'    => DeliveryOrder::whereIn('status', ['pending', 'assigned'])->count(),
            'in_transit' => DeliveryOrder::whereIn('status', ['picked_up', 'on_way'])->count(),
            'delivered'  => DeliveryOrder::where('status', 'delivered')->count(),
            'failed'     => DeliveryOrder::whereIn('status', ['failed', 'cancelled'])->count(),
        ];

        return view('delivery.index', compact('deliveries', 'riders', 'stats'));
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

    public function riders(Request $request)
    {
        $query = User::role('delivery_staff');
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }
        $riders = $query->latest()->paginate(15);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('delivery.riders', compact('riders', 'roles'));
    }

    public function storeRider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|max:2048',
            'nid_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'status' => $data['status'],
        ];

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->hasFile('nid_photo')) {
            $userData['nid_photo'] = $request->file('nid_photo')->store('nid_photos', 'public');
        }

        $user = User::create($userData);
        $user->assignRole($data['role']);

        return back()->with('success', 'Rider added successfully.');
    }

    public function updateRider(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|max:2048',
            'nid_photo' => 'nullable|image|max:2048',
        ]);

        $updateData = ['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'], 'status' => $data['status']];
        
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        if ($request->hasFile('nid_photo')) {
            if ($user->nid_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->nid_photo);
            }
            $updateData['nid_photo'] = $request->file('nid_photo')->store('nid_photos', 'public');
        }
        
        $user->update($updateData);
        $user->syncRoles([$data['role']]);

        return back()->with('success', 'Rider updated successfully.');
    }

    public function destroyRider(User $user)
    {
        $user->delete();
        return back()->with('success', 'Rider removed.');
    }
}
