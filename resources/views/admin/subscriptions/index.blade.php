@extends('admin.layouts.app')
@section('header', 'Manage Subscriptions')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h3 class="text-xl font-bold text-white">Subscriptions</h3>
    <a href="{{ route('admin.subscriptions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign Subscription</a>
</div>

@if(session('success'))
    <div class="bg-green-600 text-white p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<!-- Filter Form -->
<div class="bg-gray-800 p-4 rounded shadow mb-6 border border-gray-700">
    <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label for="tenant_id" class="block text-sm font-medium text-gray-400 mb-1">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:outline-none focus:border-blue-500">
                <option value="">All Tenants</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label for="plan_id" class="block text-sm font-medium text-gray-400 mb-1">Plan</label>
            <select name="plan_id" id="plan_id" class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:outline-none focus:border-blue-500">
                <option value="">All Plans</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label for="status" class="block text-sm font-medium text-gray-400 mb-1">Status</label>
            <select name="status" id="status" class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:outline-none focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label for="transaction_id" class="block text-sm font-medium text-gray-400 mb-1">Transaction ID</label>
            <input type="text" name="transaction_id" id="transaction_id" value="{{ request('transaction_id') }}" placeholder="Search ID..." class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded transition">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-600 hover:bg-gray-500 text-white font-medium py-2 px-4 rounded transition ml-2">
                Clear
            </a>
        </div>
    </form>
</div>

<div class="bg-gray-800 rounded shadow overflow-hidden border border-gray-700">
    <table class="min-w-full divide-y divide-gray-700 text-sm">
        <thead class="bg-gray-700 text-gray-300 uppercase">
            <tr>
                <th class="px-6 py-3 text-left tracking-wider">Tenant</th>
                <th class="px-6 py-3 text-left tracking-wider">Plan</th>
                <th class="px-6 py-3 text-left tracking-wider">Transaction ID</th>
                <th class="px-6 py-3 text-left tracking-wider">Status</th>
                <th class="px-6 py-3 text-left tracking-wider">Starts At</th>
                <th class="px-6 py-3 text-left tracking-wider">Ends At</th>
                <th class="px-6 py-3 text-right tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700 text-gray-300">
            @foreach($subscriptions as $subscription)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->tenant->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->plan->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-400">{{ $subscription->transaction_id ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($subscription->status == 'active')
                        <span class="text-green-400 font-semibold">Active</span>
                    @elseif($subscription->status == 'expired')
                        <span class="text-yellow-400 font-semibold">Expired</span>
                    @else
                        <span class="text-red-400 font-semibold">Canceled</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->starts_at ? $subscription->starts_at->format('Y-m-d') : '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-lg">
                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="text-green-400 hover:text-green-300 mr-3 transition" title="View Details">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="text-blue-400 hover:text-blue-300 mr-3 transition" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button type="button" onclick="deleteSubscription({{ $subscription->id }})" class="text-red-400 hover:text-red-300 transition" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                    <form id="delete-form-{{ $subscription->id }}" action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $subscriptions->links() }}
    </div>
</div>

<script>
    function deleteSubscription(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this subscription deletion!",
            icon: 'warning',
            showCancelButton: true,
            background: '#1f2937',
            color: '#fff',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#4b5563',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection
