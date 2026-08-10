@extends('admin.layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
    <div>
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2 tracking-tight">
            <i class="bi bi-wallet2 text-blue-500"></i> Subscriptions Audit Log
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">Monitor tenant subscription status, renewal history, and payment gateway transaction receipts.</p>
    </div>
    <a href="{{ route('admin.subscriptions.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 shrink-0">
        <i class="bi bi-plus-lg"></i> Assign Subscription
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-semibold flex items-center gap-3 shadow-lg">
            <i class="bi bi-check-circle-fill text-emerald-400 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Modern Filter Form Card -->
    <div class="bg-slate-900/80 backdrop-blur-xl p-5 rounded-2xl shadow-xl border border-slate-800">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
            
            <div class="lg:col-span-3">
                <label for="tenant_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    <i class="bi bi-shop text-blue-400 me-1"></i> Tenant Store
                </label>
                <select name="tenant_id" id="tenant_id" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">All Tenants</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label for="plan_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    <i class="bi bi-tags text-purple-400 me-1"></i> Subscription Plan
                </label>
                <select name="plan_id" id="plan_id" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">All Plans</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="status" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    <i class="bi bi-activity text-emerald-400 me-1"></i> Status
                </label>
                <select name="status" id="status" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="transaction_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                    <i class="bi bi-hash text-amber-400 me-1"></i> Transaction ID
                </label>
                <input type="text" name="transaction_id" id="transaction_id" value="{{ request('transaction_id') }}" placeholder="TRX-..." class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-3 py-2 text-slate-200 text-xs placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="py-2 px-3 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl border border-slate-700 transition-colors">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Subscriptions Log Table -->
    <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
        
        <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-slate-900/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                    <i class="bi bi-receipt-cutoff text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Subscription Records</h3>
                    <p class="text-xs text-slate-400">Total Filtered Entries: {{ $subscriptions->total() }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-[11px] uppercase tracking-wider">
                        <th class="py-3.5 px-5 font-semibold">Tenant Store</th>
                        <th class="py-3.5 px-5 font-semibold">Plan</th>
                        <th class="py-3.5 px-5 font-semibold">Transaction ID</th>
                        <th class="py-3.5 px-5 font-semibold">Status</th>
                        <th class="py-3.5 px-5 font-semibold">Starts At</th>
                        <th class="py-3.5 px-5 font-semibold">Ends At</th>
                        <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-xs">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-slate-800/40 transition-colors group">
                        <td class="py-3.5 px-5 font-bold text-white">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs">
                                    <i class="bi bi-shop"></i>
                                </div>
                                <span>{{ $subscription->tenant->name ?? 'Deleted Tenant' }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 bg-purple-500/10 text-purple-300 rounded-lg border border-purple-500/20 font-semibold text-[11px]">
                                {{ $subscription->plan->name ?? 'Custom Plan' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($subscription->transaction_id)
                                <span class="px-2 py-0.5 bg-slate-950 text-slate-400 rounded border border-slate-800 font-mono text-[11px]">
                                    {{ $subscription->transaction_id }}
                                </span>
                            @else
                                <span class="text-slate-600 italic">Manual Entry</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5">
                            @if($subscription->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[11px] font-semibold border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            @elseif($subscription->status == 'expired')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-500/10 text-amber-400 rounded-full text-[11px] font-semibold border border-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Expired
                                </span>
                            @elseif($subscription->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-500/10 text-orange-400 rounded-full text-[11px] font-semibold border border-orange-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 text-rose-400 rounded-full text-[11px] font-semibold border border-rose-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Canceled
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-slate-400 font-mono text-[11px]">
                            {{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-400 font-mono text-[11px]">
                            {{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 flex items-center justify-center transition-colors" title="View Gateway Response">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 flex items-center justify-center transition-colors" title="Edit Subscription">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" onclick="deleteSubscription({{ $subscription->id }})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 flex items-center justify-center transition-colors" title="Delete Entry">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $subscription->id }}" action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-500">
                            <i class="bi bi-inbox text-3xl block mb-2 opacity-30"></i>
                            No subscription records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $subscriptions->links() }}
        </div>
        @endif

    </div>

</div>

<script>
    function deleteSubscription(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this subscription deletion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#334155',
            confirmButtonText: 'Yes, delete it!',
            background: '#0f172a',
            color: '#f8fafc'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection

