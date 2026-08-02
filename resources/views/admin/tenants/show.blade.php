@extends('admin.layouts.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <span>Tenant Details: {{ $tenant->name }}</span>
    <a href="{{ route('admin.tenants.index') }}" class="text-sm bg-gray-700 hover:bg-gray-600 px-3 py-1.5 rounded-lg transition-colors border border-gray-600">
        &larr; Back to Tenants
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Tenant Info Card -->
    <div class="lg:col-span-1 bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-6 flex flex-col">
        <div class="text-center mb-6">
            <div class="w-24 h-24 bg-blue-900 text-blue-300 rounded-full flex items-center justify-center text-4xl font-bold mx-auto mb-4 border-4 border-gray-700 shadow-inner">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <h2 class="text-2xl font-bold text-white">{{ $tenant->name }}</h2>
            <p class="text-gray-400 mt-1">{{ $tenant->subdomain ? $tenant->subdomain . '.example.com' : 'No Subdomain' }}</p>
            <div class="mt-4">
                @if($tenant->is_active)
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold border border-green-500/30">Active</span>
                @else
                    <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm font-semibold border border-red-500/30">Inactive</span>
                @endif
            </div>
        </div>

        <div class="space-y-4 flex-1">
            @if($tenant->email)
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Email</span>
                <span class="text-white font-medium">{{ $tenant->email }}</span>
            </div>
            @endif
            @if($tenant->phone)
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Phone</span>
                <span class="text-white font-medium">{{ $tenant->phone }}</span>
            </div>
            @endif
            @if($tenant->address)
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Address</span>
                <span class="text-white font-medium text-right max-w-[200px]">{{ $tenant->address }}</span>
            </div>
            @endif
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Created At</span>
                <span class="text-white font-medium">{{ $tenant->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Updated At</span>
                <span class="text-white font-medium">{{ $tenant->updated_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-700 pb-2">
                <span class="text-gray-400">Current Plan</span>
                <span class="text-blue-400 font-bold">
                    {{ $activeSubscription ? $activeSubscription->plan->name : 'No Active Plan' }}
                </span>
            </div>
            <div class="flex justify-between pb-2">
                <span class="text-gray-400">Plan Status</span>
                @if($activeSubscription)
                    @if($activeSubscription->status === 'trialing')
                        <span class="text-yellow-400 font-medium">Trialing (ends {{ $activeSubscription->ends_at->format('M d') }})</span>
                    @else
                        <span class="text-green-400 font-medium">Active (ends {{ $activeSubscription->ends_at->format('M d') }})</span>
                    @endif
                @else
                    <span class="text-red-400 font-medium">Expired/None</span>
                @endif
            </div>
        </div>
        
        <div class="mt-6 grid grid-cols-2 gap-3">
            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors font-medium">Edit Tenant</a>
            <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this tenant and all its data? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white py-2 rounded-lg transition-colors font-medium border border-red-600/30">Delete</button>
            </form>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-6 flex items-center">
            <div class="w-14 h-14 bg-purple-900/50 text-purple-400 rounded-xl flex items-center justify-center text-2xl me-4 border border-purple-500/20">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wide">Total Users</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalUsers }}</h3>
            </div>
        </div>
        
        <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-6 flex items-center">
            <div class="w-14 h-14 bg-blue-900/50 text-blue-400 rounded-xl flex items-center justify-center text-2xl me-4 border border-blue-500/20">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wide">Total Orders</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-6 flex items-center">
            <div class="w-14 h-14 bg-orange-900/50 text-orange-400 rounded-xl flex items-center justify-center text-2xl me-4 border border-orange-500/20">
                <i class="bi bi-menu-button-wide"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wide">Menu Items</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalMenuItems }}</h3>
            </div>
        </div>

        <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-6 flex items-center">
            <div class="w-14 h-14 bg-green-900/50 text-green-400 rounded-xl flex items-center justify-center text-2xl me-4 border border-green-500/20">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm font-semibold uppercase tracking-wide">Revenue (To Admin)</p>
                <h3 class="text-3xl font-black text-white mt-1">৳{{ number_format($totalRevenue) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Users List -->
    <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
        <div class="p-5 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white"><i class="bi bi-person-badge me-2 text-purple-400"></i>Tenant Users</h3>
        </div>
        <div class="p-0 overflow-y-auto max-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider sticky top-0">
                    <tr>
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50 text-sm">
                    @forelse($tenant->users as $user)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="p-4 font-medium text-white">{{ $user->name }}</td>
                        <td class="p-4 text-gray-300">{{ $user->email }}</td>
                        <td class="p-4">
                            @if($user->hasRole('owner') || $user->role === 'owner')
                                <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-lg text-xs font-medium border border-purple-500/30">Owner</span>
                            @else
                                <span class="px-2 py-1 bg-gray-700 text-gray-300 rounded-lg text-xs font-medium border border-gray-600">User</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Subscriptions History -->
    <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
        <div class="p-5 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white"><i class="bi bi-clock-history me-2 text-blue-400"></i>Subscription History</h3>
        </div>
        <div class="p-0 overflow-y-auto max-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider sticky top-0">
                    <tr>
                        <th class="p-4 font-semibold">Plan</th>
                        <th class="p-4 font-semibold">Amount</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50 text-sm">
                    @forelse($tenant->subscriptions as $sub)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="p-4 font-medium text-white">{{ $sub->plan->name }}</td>
                        <td class="p-4 text-gray-300">৳{{ number_format($sub->amount) }}</td>
                        <td class="p-4">
                            @if($sub->status == 'active')
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs font-medium border border-green-500/30">Active</span>
                            @elseif($sub->status == 'trialing')
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs font-medium border border-yellow-500/30">Trial</span>
                            @elseif($sub->status == 'expired')
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs font-medium border border-red-500/30">Expired</span>
                            @elseif($sub->status == 'pending')
                                <span class="px-2 py-1 bg-orange-500/20 text-orange-400 rounded text-xs font-medium border border-orange-500/30">Pending</span>
                            @else
                                <span class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs font-medium border border-gray-600">{{ ucfirst($sub->status) }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-400">{{ $sub->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">No subscriptions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
