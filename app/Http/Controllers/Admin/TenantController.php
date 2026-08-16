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

    public function impersonate(Request $request, \App\Models\Tenant $tenant)
    {
        $user = $tenant->users()->first();

        if (!$user) {
            return redirect()->back()->with('error', 'No user account found for this tenant store to login as.');
        }

        return $this->performImpersonation($tenant, $user);
    }

    public function impersonateUser(Request $request, \App\Models\Tenant $tenant, \App\Models\User $user)
    {
        if ($user->tenant_id !== $tenant->id) {
            return redirect()->back()->with('error', 'Selected user does not belong to this tenant store.');
        }

        return $this->performImpersonation($tenant, $user);
    }

    protected function performImpersonation(\App\Models\Tenant $tenant, \App\Models\User $user)
    {
        $adminId = auth('admin')->id() ?? session('impersonated_by_admin');
        if ($adminId) {
            session()->put('impersonated_by_admin', $adminId);
        }

        auth('web')->login($user);
        session()->put('tenant_id', $tenant->id);
        app()->instance('tenant', $tenant);

        return redirect()->route('dashboard')->with('success', "Logged in as {$user->name} ({$tenant->name}).");
    }

    public function leaveImpersonation(Request $request)
    {
        if (!session()->has('impersonated_by_admin')) {
            return redirect()->route('admin.dashboard');
        }

        $adminId = session()->get('impersonated_by_admin');
        
        auth('web')->logout();
        session()->forget(['impersonated_by_admin', 'tenant_id']);

        if ($adminId && $admin = \App\Models\Admin::find($adminId)) {
            auth('admin')->login($admin);
        }

        return redirect()->route('admin.tenants.index')->with('success', 'Returned to Super Admin panel.');
    }
}
