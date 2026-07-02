@extends('layouts.app')
@section('title','Coupons')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Coupons & Discounts</h4><p class="text-muted small mb-0">Manage promotional codes</p></div>
    @can('create coupons')<a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Create Coupon</a>@endcan
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Min Order</th><th>Usage</th><th>Validity</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($coupons as $c)
            @php $expired = $c->end_date && \Carbon\Carbon::parse($c->end_date)->isPast(); $limitReached = $c->usage_limit && $c->used_count >= $c->usage_limit; @endphp
            <tr>
                <td><code class="fw-bold" style="color:var(--primary)">{{ $c->code }}</code></td>
                <td>{{ $c->name }}</td>
                <td><span class="badge bg-light text-dark">{{ ucfirst($c->type) }}</span></td>
                <td class="fw-semibold">{{ $c->type=='percentage'?$c->value.'%':'৳'.number_format($c->value,0) }}</td>
                <td>{{ $c->min_order_amount?'৳'.number_format($c->min_order_amount,0):'—' }}</td>
                <td>{{ $c->used_count ?? 0 }}{{ $c->usage_limit?'/'.($c->usage_limit):'/ ∞' }}</td>
                <td class="text-muted small">
                    @if($c->start_date && $c->end_date) {{ \Carbon\Carbon::parse($c->start_date)->format('d M y') }} – {{ \Carbon\Carbon::parse($c->end_date)->format('d M y') }}
                    @elseif($c->end_date) Expires {{ \Carbon\Carbon::parse($c->end_date)->format('d M Y') }}
                    @else <span class="text-success">No Expiry</span>
                    @endif
                </td>
                <td>
                    @if(!$c->status) <span class="badge bg-secondary">Inactive</span>
                    @elseif($expired) <span class="badge bg-danger">Expired</span>
                    @elseif($limitReached) <span class="badge bg-warning text-dark">Limit Reached</span>
                    @else <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        @can('edit coupons')<a href="{{ route('coupons.edit',$c) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete coupons')<form method="POST" action="{{ route('coupons.destroy',$c) }}" data-confirm="Delete coupon?" data-confirm-button="Yes, delete!">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No coupons found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($coupons->hasPages())<div class="card-footer">{{ $coupons->links() }}</div>@endif</div>
@endsection
