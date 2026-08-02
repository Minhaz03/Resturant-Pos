@extends('admin.layouts.app')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 border border-gray-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="bi bi-shop text-6xl text-white"></i>
        </div>
        <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Total Tenants</h3>
        <p class="text-4xl font-black text-white">{{ $tenantCount ?? 0 }}</p>
    </div>
    
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 border border-gray-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="bi bi-star-fill text-6xl text-green-400"></i>
        </div>
        <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Active Subscriptions</h3>
        <p class="text-4xl font-black text-green-400">{{ $activeSubscriptions ?? 0 }}</p>
    </div>

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 border border-gray-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="bi bi-currency-dollar text-6xl text-blue-400"></i>
        </div>
        <h3 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">Monthly Revenue</h3>
        <p class="text-4xl font-black text-blue-400">৳{{ number_format($monthlyRevenue ?? 0) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-800/50">
            <h3 class="text-lg font-bold text-white"><i class="bi bi-building me-2 text-blue-500"></i>Recent Tenants</h3>
            <a href="{{ route('admin.tenants.index') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">View All &rarr;</a>
        </div>
        <div class="p-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Subdomain</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50 text-sm">
                    @forelse($recentTenants ?? [] as $tenant)
                    <tr class="hover:bg-gray-700/20 transition-colors">
                        <td class="p-4 font-medium text-white">{{ $tenant->name }}</td>
                        <td class="p-4 text-gray-400">{{ $tenant->subdomain ?? 'N/A' }}</td>
                        <td class="p-4">
                            @if($tenant->is_active)
                                <span class="px-2 py-1 bg-green-500/10 text-green-400 rounded-full text-xs font-medium border border-green-500/20">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-500/10 text-red-400 rounded-full text-xs font-medium border border-red-500/20">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-400">{{ $tenant->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">No tenants registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-800/50">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="bi bi-bell-fill me-2 text-yellow-500"></i> Notifications
                @if(($unreadCount ?? 0) > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                @endif
            </h3>
            @if(($unreadCount ?? 0) > 0)
            <form action="{{ route('admin.notifications.read') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-gray-400 hover:text-white transition-colors" title="Mark all as read">
                    <i class="bi bi-check2-all text-lg"></i>
                </button>
            </form>
            @endif
        </div>
        <div class="flex-1 p-0 overflow-y-auto max-h-[400px]">
            @forelse($notifications ?? [] as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-4 border-b border-gray-700/50 hover:bg-gray-700/30 transition-colors {{ is_null($notification->read_at) ? 'bg-gray-700/10' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="{{ $notification->data['icon'] ?? 'bi bi-bell' }} text-xl {{ is_null($notification->read_at) ? 'text-blue-400' : 'text-gray-500' }}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ is_null($notification->read_at) ? 'text-white' : 'text-gray-300' }}">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-gray-500 mt-2 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(is_null($notification->read_at))
                            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500 flex flex-col items-center">
                    <i class="bi bi-bell-slash text-4xl mb-3 opacity-20"></i>
                    <p>No recent notifications.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
