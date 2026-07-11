@extends('admin.layouts.app')
@section('header', 'Assign Subscription')

@section('content')
<div class="bg-gray-800 rounded shadow p-6 border border-gray-700 max-w-2xl">
    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Tenant</label>
            <select name="tenant_id" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
                <option value="">Select Tenant</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Plan</label>
            <select name="plan_id" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
                <option value="">Select Plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} (${{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Status</label>
            <select name="status" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Starts At</label>
                <input type="date" name="starts_at" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Ends At</label>
                <input type="date" name="ends_at" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
            </div>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign</button>
        <a href="{{ route('admin.subscriptions.index') }}" class="ml-2 text-gray-400 hover:text-white">Cancel</a>
    </form>
</div>
@endsection
