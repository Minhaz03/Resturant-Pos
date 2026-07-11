<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $tenant = \App\Models\Tenant::create([
            'name' => $request->restaurant_name,
            'is_active' => true,
        ]);

        // We bind the tenant to the container so that BelongsToTenant works correctly
        app()->instance('tenant', $tenant);

        // Create the owner user for this tenant
        $user = \App\Models\User::create([
            'name' => 'Owner',
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin', // assuming role column exists based on typical setups
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('login')->with('success', 'Restaurant registered successfully! You can now log in.');
    }
}
