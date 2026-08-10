@extends('admin.layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
    <div>
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2 tracking-tight">
            <i class="bi bi-card-checklist text-blue-500"></i> Subscription Plans
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">Configure pricing tiers, feature limits, and billing intervals for restaurant tenants.</p>
    </div>
    <a href="{{ route('admin.plans.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 shrink-0">
        <i class="bi bi-plus-lg"></i> Add New Plan
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

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-lg">
            <i class="bi bi-exclamation-triangle-fill text-rose-400 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
        
        <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-slate-900/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                    <i class="bi bi-tags text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Configured Pricing Tiers</h3>
                    <p class="text-xs text-slate-400">Total Available Plans: {{ count($plans) }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-[11px] uppercase tracking-wider">
                        <th class="py-3.5 px-5 font-semibold">Plan ID</th>
                        <th class="py-3.5 px-5 font-semibold">Plan Name</th>
                        <th class="py-3.5 px-5 font-semibold">Price</th>
                        <th class="py-3.5 px-5 font-semibold">Billing Cycle</th>
                        <th class="py-3.5 px-5 font-semibold">Status</th>
                        <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-xs">
                    @forelse($plans as $plan)
                    <tr class="hover:bg-slate-800/40 transition-colors group">
                        <td class="py-3.5 px-5 font-mono text-slate-500">
                            #PLN-{{ str_pad($plan->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="py-3.5 px-5 font-bold text-white">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs">
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="text-slate-100 text-sm">{{ $plan->name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-5 font-bold text-emerald-400 text-sm">
                            ৳{{ number_format($plan->price, 2) }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-300 font-medium capitalize">
                            <span class="px-2.5 py-1 bg-slate-800 rounded-lg border border-slate-700 font-mono text-[11px]">
                                {{ $plan->billing_cycle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($plan->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[11px] font-semibold border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 text-rose-400 rounded-full text-[11px] font-semibold border border-rose-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.plans.edit', $plan->id) }}" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 flex items-center justify-center transition-colors" title="Edit Plan">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 flex items-center justify-center transition-colors btn-delete" title="Delete Plan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-500">
                            <i class="bi bi-inbox text-3xl block mb-2 opacity-30"></i>
                            No subscription plans created yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($plans, 'hasPages') && $plans->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $plans->links() }}
        </div>
        @endif

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! Ensure no active subscriptions are bound to this plan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#334155',
                    confirmButtonText: 'Yes, delete plan!',
                    background: '#0f172a',
                    color: '#f8fafc'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
</script>
@endsection

