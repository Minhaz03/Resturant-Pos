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

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_password' => 'required|min:8|confirmed',
        ]);

        $tenant = \App\Models\Tenant::create([
            'name' => $data['name'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Temporarily bind the tenant so the user gets the correct tenant_id via trait
        app()->instance('tenant', $tenant);

        \App\Models\User::create([
            'name' => 'Owner',
            'email' => $data['owner_email'],
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
            'is_active' => 'boolean'
        ]);

        $tenant->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(\App\Models\Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
