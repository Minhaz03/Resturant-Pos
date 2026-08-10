@extends('admin.layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
    <div>
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2 tracking-tight">
            Dashboard Overview
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">Welcome back, {{ auth('admin')->user()->name }}! Here's your multi-tenant ecosystem snapshot.</p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('admin.tenants.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
            <i class="bi bi-plus-lg"></i> Provision New Tenant
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    <!-- Key Performance Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Tenants -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-5 border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Total Tenants</span>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ number_format($tenantCount ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1.5 text-blue-400 font-semibold">
                    <i class="bi bi-arrow-up-right"></i> Active Stores
                </span>
                <span>Registered POS Tenants</span>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-5 border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Active Subscriptions</span>
                    <h3 class="text-3xl font-black text-emerald-400 tracking-tight">{{ number_format($activeSubscriptions ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-semibold">
                    <i class="bi bi-shield-check"></i> Provisioned
                </span>
                <span>Active & Trial Accounts</span>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-5 border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Monthly Revenue</span>
                    <h3 class="text-3xl font-black text-indigo-400 tracking-tight">৳{{ number_format($monthlyRevenue ?? 0) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1.5 text-indigo-400 font-semibold">
                    <i class="bi bi-calendar-check"></i> {{ date('F Y') }}
                </span>
                <span>Gateway Receipts</span>
            </div>
        </div>

        <!-- Platform Telemetry Status -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-5 border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">System Status</span>
                    <h3 class="text-3xl font-black text-amber-400 tracking-tight">100%</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-activity"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> All Systems Operational
                </span>
            </div>
        </div>

    </div>

    <!-- Main Content Area: Recent Tenants + Notifications Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Recent Tenants (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                
                <!-- Table Header -->
                <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                            <i class="bi bi-building text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Recent Restaurant Tenants</h3>
                            <p class="text-xs text-slate-400">Latest restaurant stores onboarded into the SaaS platform.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold flex items-center gap-1.5 hover:underline transition-all">
                        <span>View All Tenants</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-[11px] uppercase tracking-wider">
                                <th class="py-3.5 px-5 font-semibold">Restaurant Store</th>
                                <th class="py-3.5 px-5 font-semibold">Subdomain</th>
                                <th class="py-3.5 px-5 font-semibold">Status</th>
                                <th class="py-3.5 px-5 font-semibold">Joined</th>
                                <th class="py-3.5 px-5 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-xs">
                            @forelse($recentTenants ?? [] as $tenant)
                            <tr class="hover:bg-slate-800/40 transition-colors group">
                                <td class="py-3.5 px-5 font-semibold text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="text-slate-100 font-bold block">{{ $tenant->name }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $tenant->email ?? 'No email set' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-400">
                                    @if($tenant->subdomain)
                                        <span class="px-2 py-0.5 bg-slate-800 text-slate-300 rounded-md border border-slate-700 font-mono text-[11px]">
                                            {{ $tenant->subdomain }}
                                        </span>
                                    @else
                                        <span class="text-slate-600 italic">Default</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5">
                                    @if($tenant->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[11px] font-semibold border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 text-rose-400 rounded-full text-[11px] font-semibold border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-400">
                                    {{ $tenant->created_at->diffForHumans() }}
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-blue-600 text-slate-300 hover:text-white rounded-lg transition-colors border border-slate-700/80 font-medium inline-flex items-center gap-1">
                                        <span>Details</span>
                                        <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    <i class="bi bi-inbox text-3xl block mb-2 opacity-30"></i>
                                    No tenant stores created yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Action Grid Shortcuts -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.tenants.create') }}" class="p-4 rounded-2xl bg-slate-900/60 hover:bg-slate-800/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-shop-window"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-200">Provision Tenant</h4>
                        <p class="text-[10px] text-slate-400">Onboard new store</p>
                    </div>
                </a>

                <a href="{{ route('admin.plans.index') }}" class="p-4 rounded-2xl bg-slate-900/60 hover:bg-slate-800/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-200">Manage Plans</h4>
                        <p class="text-[10px] text-slate-400">Pricing & limits</p>
                    </div>
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" class="p-4 rounded-2xl bg-slate-900/60 hover:bg-slate-800/80 border border-slate-800 hover:border-slate-700 transition-all flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-200">Gateway Logs</h4>
                        <p class="text-[10px] text-slate-400">Subscription receipts</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Right Column: Notifications & Telemetry (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- System Notifications Feed -->
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-slate-900/60">
                    <div class="flex items-center gap-2.5">
                        <i class="bi bi-bell-fill text-amber-400 text-lg"></i>
                        <h3 class="text-sm font-bold text-white">Live Notifications</h3>
                        @if(($unreadCount ?? 0) > 0)
                            <span class="px-2 py-0.5 bg-rose-500/20 text-rose-300 text-[10px] font-bold rounded-full border border-rose-500/30">
                                {{ $unreadCount }} Unread
                            </span>
                        @endif
                    </div>
                    @if(($unreadCount ?? 0) > 0)
                    <form action="{{ route('admin.notifications.read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-white transition-colors" title="Mark all as read">
                            <i class="bi bi-check2-all text-lg"></i>
                        </button>
                    </form>
                    @endif
                </div>

                <div class="divide-y divide-slate-800/60 max-h-[380px] overflow-y-auto">
                    @forelse($notifications ?? [] as $notification)
                        <a href="{{ $notification->data['url'] ?? route('admin.notifications.index') }}" class="block p-4 hover:bg-slate-800/40 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-500/5' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="{{ $notification->data['icon'] ?? 'bi bi-bell' }} text-sm {{ is_null($notification->read_at) ? 'text-blue-400' : 'text-slate-500' }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold {{ is_null($notification->read_at) ? 'text-white' : 'text-slate-300' }} truncate">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2 leading-relaxed">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <span class="text-[10px] text-slate-500 mt-1.5 block font-medium">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            <i class="bi bi-bell-slash text-3xl block mb-2 opacity-30"></i>
                            <p class="text-xs">No recent notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Platform Security Card -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                        <i class="bi bi-shield-check text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Security & Environment</h4>
                        <span class="text-[10px] text-slate-400">Hardened Multi-Tenant Guard</span>
                    </div>
                </div>

                <div class="space-y-2 text-xs text-slate-400">
                    <div class="flex justify-between py-1 border-b border-slate-800/60">
                        <span>Environment</span>
                        <span class="text-slate-200 font-semibold uppercase">{{ app()->environment() }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-800/60">
                        <span>PHP Version</span>
                        <span class="text-slate-200 font-semibold">v{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Database Driver</span>
                        <span class="text-emerald-400 font-semibold">MySQL Connected</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

