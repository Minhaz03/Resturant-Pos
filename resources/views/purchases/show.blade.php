@extends('layouts.app')
@section('title','Purchase Order')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $purchaseOrder->po_number }}</h4>
        <p class="text-muted small mb-0">{{ $purchaseOrder->supplier?->name }}</p>
    </div>
    <div class="ms-auto d-flex gap-2">
        @if($purchaseOrder->status === 'ordered' || $purchaseOrder->status === 'partial')
        @can('edit purchase_orders')
        <form method="POST" action="{{ route('purchases.receive',$purchaseOrder) }}" data-confirm="Mark all items as received?" data-confirm-title="Receive Items" data-confirm-button="Yes, receive!" data-confirm-icon="question">@csrf
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all me-1"></i>Receive All</button>
        </form>
        @endcan
        @endif
        <span class="badge {{ match($purchaseOrder->status){'draft'=>'bg-secondary','ordered'=>'bg-primary','partial'=>'bg-warning text-dark','received'=>'bg-success','cancelled'=>'bg-danger',default=>'bg-secondary'} }} align-self-center">
            {{ ucfirst($purchaseOrder->status) }}
        </span>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header fw-semibold">PO Information</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">PO Number</span><span class="fw-semibold">{{ $purchaseOrder->po_number }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Order Date</span><span>{{ $purchaseOrder->order_date?->format('d M Y') ?? $purchaseOrder->created_at->format('d M Y') }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Expected</span><span>{{ $purchaseOrder->expected_date?->format('d M Y') ?? '—' }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Received</span><span>{{ $purchaseOrder->received_date?->format('d M Y') ?? '—' }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Payment</span>
                    <span class="badge {{ $purchaseOrder->payment_status=='paid'?'bg-success':($purchaseOrder->payment_status=='partial'?'bg-warning text-dark':'bg-light text-dark') }}">
                        {{ ucfirst($purchaseOrder->payment_status ?? 'unpaid') }}
                    </span></div>
                @if($purchaseOrder->notes)<div class="mt-2 p-2 bg-light rounded"><small class="text-muted">{{ $purchaseOrder->notes }}</small></div>@endif
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Supplier Details</div>
            <div class="card-body">
                <p class="fw-semibold mb-1">{{ $purchaseOrder->supplier?->name ?? '—' }}</p>
                <p class="text-muted small mb-1">{{ $purchaseOrder->supplier?->contact_person ?? '' }}</p>
                <p class="text-muted small mb-1">{{ $purchaseOrder->supplier?->phone ?? '' }}</p>
                <p class="text-muted small mb-0">{{ $purchaseOrder->supplier?->email ?? '' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Order Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Item</th><th>Ordered Qty</th><th>Received Qty</th><th>Unit</th><th>Unit Price</th><th>Total</th></tr></thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->inventoryItem?->name ?? '—' }}</td>
                                <td>{{ number_format($item->quantity,2) }}</td>
                                <td class="{{ $item->received_quantity >= $item->quantity?'text-success':'text-warning' }}">{{ number_format($item->received_quantity ?? 0,2) }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>৳{{ number_format($item->unit_price,2) }}</td>
                                <td class="fw-semibold">৳{{ number_format($item->total_price,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr><td colspan="5" class="text-end fw-bold">Total:</td><td class="fw-bold" style="color:var(--primary)">৳{{ number_format($purchaseOrder->total_amount,2) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
