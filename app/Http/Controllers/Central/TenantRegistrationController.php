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
            'restaurant_email' => 'nullable|email|max:255',
            'restaurant_phone' => 'nullable|string|max:20',
            'restaurant_address' => 'nullable|string',
            
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_phone' => 'required|string|max:20',
            'owner_address' => 'required|string',
            'password' => 'required|min:8|confirmed',
            
            'plan_id' => 'required|exists:plans,id',
        ]);

        // Fallback logic: If restaurant details are empty, use owner details
        $tenantEmail = $request->restaurant_email ?: $request->owner_email;
        $tenantPhone = $request->restaurant_phone ?: $request->owner_phone;
        $tenantAddress = $request->restaurant_address ?: $request->owner_address;

        $tenant = \App\Models\Tenant::create([
            'name' => $request->restaurant_name,
            'email' => $tenantEmail,
            'phone' => $tenantPhone,
            'address' => $tenantAddress,
            'is_active' => true,
        ]);

        // We bind the tenant to the container so that BelongsToTenant works correctly
        app()->instance('tenant', $tenant);

        // Create the owner user for this tenant
        $user = \App\Models\User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'phone' => $request->owner_phone,
            'address' => $request->owner_address,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'owner', // assuming role column exists based on typical setups
            'tenant_id' => $tenant->id,
        ]);

        $user->assignRole('owner');

        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        $subscription = \App\Models\Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
            'status' => 'trialing',
        ]);

        \Illuminate\Support\Facades\Notification::send(\App\Models\Admin::all(), new \App\Notifications\NewTenantRegistrationNotification($tenant));

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Your 7-day free trial has started.');
    }
}
