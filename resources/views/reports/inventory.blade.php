@extends('layouts.app')
@section('title','Inventory Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Inventory Report</h4><p class="text-muted small mb-0">Stock levels and valuation</p></div>
    {{-- Excel export available when maatwebsite/excel is configured --}}
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Items</p><h4 class="fw-bold" style="color:var(--secondary)">{{ $stats['total_items'] ?? 0 }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Value</p><h4 class="fw-bold text-success">৳{{ number_format($stats['total_value'] ?? 0,2) }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Low Stock</p><h4 class="fw-bold text-warning">{{ $stats['low_stock'] ?? 0 }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Out of Stock</p><h4 class="fw-bold text-danger">{{ $stats['out_of_stock'] ?? 0 }}</h4></div></div></div>
</div>
<div class="card"><div class="card-header fw-semibold">Inventory Status</div>
<div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Item</th><th>SKU</th><th>Category</th><th>Qty</th><th>Min Qty</th><th>Unit</th><th>Unit Cost</th><th>Total Value</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr class="{{ $item->quantity==0?'table-danger':($item->isLowStock()?'table-warning':'') }}">
                <td class="fw-semibold">{{ $item->name }}</td>
                <td class="text-muted small">{{ $item->sku }}</td>
                <td>{{ $item->category ?? '—' }}</td>
                <td class="fw-bold {{ $item->quantity==0?'text-danger':($item->isLowStock()?'text-warning':'text-success') }}">{{ number_format($item->quantity,2) }}</td>
                <td>{{ number_format($item->min_quantity,2) }}</td>
                <td>{{ $item->unit }}</td>
                <td>৳{{ number_format($item->unit_cost,2) }}</td>
                <td class="fw-semibold">৳{{ number_format($item->total_value,2) }}</td>
                <td>
                    @if($item->quantity == 0) <span class="badge bg-danger">Out</span>
                    @elseif($item->isLowStock()) <span class="badge bg-warning text-dark">Low</span>
                    @else <span class="badge bg-success">OK</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No items found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
@endsection
