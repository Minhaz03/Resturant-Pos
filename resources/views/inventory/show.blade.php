@extends('layouts.app')
@section('title','Inventory Item')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $item->name }}</h4>
        <p class="text-muted small mb-0">{{ $item->sku }}</p>
    </div>
    <div class="ms-auto d-flex gap-2">
        @can('edit inventory')<a href="{{ route('inventory.edit',$item) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="text-center mb-3">
                    @php $isLow = $item->isLowStock(); @endphp
                    <div class="display-4 fw-bold {{ $item->quantity==0?'text-danger':($isLow?'text-warning':'text-success') }}">{{ number_format($item->quantity,2) }}</div>
                    <div class="text-muted">{{ $item->unit }}</div>
                    <span class="badge mt-1 {{ $item->quantity==0?'bg-danger':($isLow?'bg-warning text-dark':'bg-success') }}">
                        {{ $item->quantity==0?'Out of Stock':($isLow?'Low Stock':'In Stock') }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Min Level</span><span class="fw-semibold">{{ number_format($item->min_quantity,2) }} {{ $item->unit }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Unit Cost</span><span class="fw-semibold">৳{{ number_format($item->unit_cost,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Total Value</span><span class="fw-semibold text-success">৳{{ number_format($item->total_value,2) }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Category</span><span class="fw-semibold">{{ $item->category ?? '—' }}</span></div>
            </div>
        </div>
        @can('edit inventory')
        <div class="card">
            <div class="card-header fw-semibold">Quick Adjust</div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventory.adjust',$item) }}">@csrf
                    <div class="mb-2"><select name="type" class="form-select form-select-sm"><option value="adjustment">Adjustment</option><option value="purchase">Purchase</option><option value="waste">Waste</option><option value="usage">Usage</option></select></div>
                    <div class="mb-2"><input type="number" name="quantity" class="form-control form-control-sm" placeholder="Qty (+ or -)" step="0.01" required></div>
                    <div class="mb-2"><textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Notes..."></textarea></div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">Apply Adjustment</button>
                </form>
            </div>
        </div>
        @endcan
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Item Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="text-muted small">Supplier</label><p class="fw-semibold mb-0">{{ $item->supplier?->name ?? '—' }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Location</label><p class="fw-semibold mb-0">{{ $item->location ?? '—' }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Track Inventory</label><p class="fw-semibold mb-0">{{ $item->track_inventory?'Yes':'No' }}</p></div>
                    <div class="col-12"><label class="text-muted small">Description</label><p class="fw-semibold mb-0">{{ $item->description ?? '—' }}</p></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Transaction History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Date</th><th>Type</th><th>Qty Change</th><th>Balance</th><th>Notes</th></tr></thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td class="text-muted small">{{ $tx->created_at->format('d M y, h:i A') }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst($tx->type) }}</span></td>
                                <td class="{{ $tx->quantity >= 0?'text-success':'text-danger' }}">{{ $tx->quantity >= 0?'+':'' }}{{ number_format($tx->quantity,2) }}</td>
                                <td class="fw-semibold">{{ number_format($tx->balance_after,2) }}</td>
                                <td class="text-muted small">{{ $tx->notes ?? '' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">No transactions yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
