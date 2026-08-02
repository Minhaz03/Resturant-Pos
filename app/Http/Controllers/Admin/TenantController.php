<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = \App\Models\Tenant::with('subscriptions.plan')->latest()->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function show(\App\Models\Tenant $tenant)
    {
        $tenant->load(['users', 'subscriptions.plan' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        // Basic stats
        $totalUsers = $tenant->users()->count();
        $totalOrders = \App\Models\Order::where('tenant_id', $tenant->id)->count();
        $totalMenuItems = \App\Models\MenuItem::where('tenant_id', $tenant->id)->count();
        $totalRevenue = \App\Models\Subscription::where('tenant_id', $tenant->id)->where('status', '!=', 'pending')->sum('amount');
        
        $activeSubscription = $tenant->subscriptions->where('status', 'active')->first() 
                           ?? $tenant->subscriptions->where('status', 'trialing')->first();

        return view('admin.tenants.show', compact('tenant', 'totalUsers', 'totalOrders', 'totalMenuItems', 'totalRevenue', 'activeSubscription'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_phone' => 'required|string|max:20',
            'owner_address' => 'required|string',
            'owner_password' => 'required|min:8|confirmed',
        ]);

        // Fallback logic for tenant contact info
        $tenantEmail = $data['email'] ?: $data['owner_email'];
        $tenantPhone = $data['phone'] ?: $data['owner_phone'];
        $tenantAddress = $data['address'] ?: $data['owner_address'];

        $tenant = \App\Models\Tenant::create([
            'name' => $data['name'],
            'email' => $tenantEmail,
            'phone' => $tenantPhone,
            'address' => $tenantAddress,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Temporarily bind the tenant so the user gets the correct tenant_id via trait
        app()->instance('tenant', $tenant);

        \App\Models\User::create([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'phone' => $data['owner_phone'],
            'address' => $data['owner_address'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['owner_password']),
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant and Owner account created successfully.');
    }

    public function edit(\App\Models\Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, \App\Models\Tenant $tenant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $tenant->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(\App\Models\Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
