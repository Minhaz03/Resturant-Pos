<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = \App\Models\Subscription::with(['tenant', 'plan'])->latest()->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $tenants = \App\Models\Tenant::all();
        $plans = \App\Models\Plan::all();
        return view('admin.subscriptions.create', compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,canceled,expired',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at'
        ]);

        \App\Models\Subscription::create($data);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(\App\Models\Subscription $subscription)
    {
        $tenants = \App\Models\Tenant::all();
        $plans = \App\Models\Plan::all();
        return view('admin.subscriptions.edit', compact('subscription', 'tenants', 'plans'));
    }

    public function update(Request $request, \App\Models\Subscription $subscription)
    {
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,canceled,expired',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at'
        ]);

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(\App\Models\Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
