@extends('admin.layouts.app')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gray-800 rounded-lg shadow p-6 border border-gray-700">
        <h3 class="text-gray-400 text-sm font-semibold uppercase mb-2">Total Tenants</h3>
        <p class="text-3xl font-bold text-white">{{ $tenantCount ?? 0 }}</p>
    </div>
    
    <div class="bg-gray-800 rounded-lg shadow p-6 border border-gray-700">
        <h3 class="text-gray-400 text-sm font-semibold uppercase mb-2">Active Subscriptions</h3>
        <p class="text-3xl font-bold text-green-400">0</p>
    </div>

    <div class="bg-gray-800 rounded-lg shadow p-6 border border-gray-700">
        <h3 class="text-gray-400 text-sm font-semibold uppercase mb-2">Monthly Revenue</h3>
        <p class="text-3xl font-bold text-blue-400">$0.00</p>
    </div>
</div>

<div class="bg-gray-800 rounded-lg shadow border border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h3 class="text-lg font-semibold text-white">Recent Tenants</h3>
    </div>
    <div class="p-6 text-gray-400">
        <p>Tenant listing functionality will be placed here.</p>
    </div>
</div>
@endsection
