@extends('layouts.app')
@section('title','Order Details')
@push('styles')
<style>
@media print {
    #main, #sidebar, #topbar, .btn, .dropdown, .no-print { display: none !important; }
    #printable-invoice { display: block !important; }
    body { background: white !important; }
}
#printable-invoice { display: none; }
</style>
@endpush
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $order->order_number }}</h4>
        <span class="badge" style="font-size:0.78rem;background:{{ match($order->status){'pending'=>'#fef3c7','confirmed'=>'#dbeafe','preparing'=>'#ede9fe','ready'=>'#d1fae5','served'=>'#cffafe','completed'=>'#dcfce7','cancelled'=>'#fee2e2',default=>'#f3f4f6'} }};color:{{ match($order->status){'pending'=>'#92400e','confirmed'=>'#1e40af','preparing'=>'#5b21b6','ready'=>'#065f46','served'=>'#164e63','completed'=>'#166534','cancelled'=>'#991b1b',default=>'#374151'} }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <div class="ms-auto d-flex gap-2">
        @if(!in_array($order->status,['completed','cancelled']))
        <div class="dropdown">
            <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Update Status</button>
            <ul class="dropdown-menu border-0 shadow">
                @foreach(['confirmed','preparing','ready','served','completed','cancelled'] as $s)
                @if($s != $order->status)
                <li><form method="POST" action="{{ route('orders.update-status',$order) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $s }}">
                    <button type="submit" class="dropdown-item small {{ $s=='cancelled'?'text-danger':'' }}">{{ ucfirst($s) }}</button>
                </form></li>
                @endif
                @endforeach
            </ul>
        </div>
        @endif
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i>Print Invoice</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Order Items</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Tax</th><th>Subtotal</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->item_name }}</td>
                            <td>৳{{ number_format($item->unit_price,2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-muted">৳{{ number_format($item->tax_amount,2) }}</td>
                            <td class="fw-semibold">৳{{ number_format($item->subtotal,2) }}</td>
                            <td><span class="badge bg-light text-dark" style="font-size:0.72rem">{{ ucfirst($item->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">Order Summary</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Subtotal:</span><span>৳{{ number_format($order->subtotal,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Tax:</span><span>৳{{ number_format($order->tax_amount,2) }}</span></div>
                @if($order->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Discount:</span><span class="text-danger">-৳{{ number_format($order->discount_amount,2) }}</span></div>
                @endif
                @if($order->coupon_discount > 0)
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Coupon ({{ $order->coupon_code }}):</span><span class="text-danger">-৳{{ number_format($order->coupon_discount,2) }}</span></div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total:</span><span style="color:var(--primary)">৳{{ number_format($order->total_amount,2) }}</span></div>
                @if($order->payment)
                <hr>
                <div class="d-flex justify-content-between small"><span class="text-muted">Paid via:</span><span class="text-capitalize">{{ str_replace('_',' ',$order->payment->method) }}</span></div>
                <div class="d-flex justify-content-between small"><span class="text-muted">Status:</span><span class="text-success">{{ ucfirst($order->payment->status) }}</span></div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">Order Info</div>
            <div class="card-body small">
                <div class="mb-2"><span class="text-muted">Type:</span> {{ str_replace('_',' ',ucfirst($order->type)) }}</div>
                @if($order->table)<div class="mb-2"><span class="text-muted">Table:</span> {{ $order->table->table_number }}</div>@endif
                @if($order->customer)<div class="mb-2"><span class="text-muted">Customer:</span> {{ $order->customer->name }}</div>@endif
                @if($order->waiter)<div class="mb-2"><span class="text-muted">Waiter:</span> {{ $order->waiter->name }}</div>@endif
                <div class="mb-2"><span class="text-muted">Created:</span> {{ $order->created_at->format('d M Y, H:i') }}</div>
                @if($order->notes)<div><span class="text-muted">Notes:</span> {{ $order->notes }}</div>@endif
            </div>
        </div>
    </div>
</div>
@endsection
