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

<div class="bg-gray-800 rounded shadow overflow-hidden border border-gray-700">
    <table class="min-w-full divide-y divide-gray-700 text-sm">
        <thead class="bg-gray-700 text-gray-300 uppercase">
            <tr>
                <th class="px-6 py-3 text-left tracking-wider">Tenant</th>
                <th class="px-6 py-3 text-left tracking-wider">Plan</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="text-blue-400 hover:text-blue-300 mr-3">Edit</a>
                    <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
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
@endsection
