@extends('layouts.app')
@section('title','Orders')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Orders</h4>
        <p class="text-muted small mb-0">Manage all restaurant orders</p></div>
    @can('create orders')
    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Order</a>
    @endcan
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Order number..." value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','preparing','ready','served','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="dine_in" {{ request('type')=='dine_in'?'selected':'' }}>Dine In</option>
                <option value="takeaway" {{ request('type')=='takeaway'?'selected':'' }}>Takeaway</option>
                <option value="delivery" {{ request('type')=='delivery'?'selected':'' }}>Delivery</option>
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}"></div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Order #</th><th>Table/Type</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Time</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><a href="{{ route('orders.show',$order) }}" class="fw-semibold text-decoration-none" style="color:var(--secondary)">{{ $order->order_number }}</a></td>
                <td>
                    <span class="badge bg-light text-dark">{{ str_replace('_',' ',ucfirst($order->type)) }}</span>
                    @if($order->tables->count() > 0)<br><small class="text-muted">{{ $order->tables->pluck('table_number')->implode(', ') }}</small>@endif
                </td>
                <td>{{ $order->customer?->name ?? 'Walk-in' }}</td>
                <td><span class="badge bg-primary">{{ $order->items->count() }}</span></td>
                <td class="fw-semibold">৳{{ number_format($order->total_amount,0) }}</td>
                <td>
                    @if($order->payment)
                        <span class="badge bg-success" style="font-size:0.73rem">Paid</span>
                    @else
                        <span class="badge bg-danger" style="font-size:0.73rem">Unpaid</span>
                    @endif
                </td>
                <td>
                    <span class="badge" style="font-size:0.73rem;background:{{ match($order->status){'pending'=>'#fef3c7','confirmed'=>'#dbeafe','preparing'=>'#ede9fe','ready'=>'#d1fae5','served'=>'#cffafe','completed'=>'#dcfce7','cancelled'=>'#fee2e2',default=>'#f3f4f6'} }};color:{{ match($order->status){'pending'=>'#92400e','confirmed'=>'#1e40af','preparing'=>'#5b21b6','ready'=>'#065f46','served'=>'#164e63','completed'=>'#166534','cancelled'=>'#991b1b',default=>'#374151'} }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('orders.show',$order) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-eye"></i></a>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2 dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu border-0 shadow">
                                @if(!in_array($order->status,['completed','cancelled']))
                                @foreach(['confirmed','preparing','ready','served','completed','cancelled'] as $s)
                                @if($s != $order->status)
                                <li>
                                    <form method="POST" action="{{ route('orders.update-status',$order) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $s }}">
                                        <button type="submit" class="dropdown-item small {{ $s=='cancelled'?'text-danger':'' }}">
                                            <i class="bi {{ match($s){'confirmed'=>'bi-check','preparing'=>'bi-fire','ready'=>'bi-bell','served'=>'bi-person-check','completed'=>'bi-check-circle','cancelled'=>'bi-x-circle',default=>'bi-arrow-right'} }} me-2"></i>
                                            {{ ucfirst($s) }}
                                        </button>
                                    </form>
                                </li>
                                @endif
                                @endforeach
                                @endif
                                @if(!$order->payment)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item small text-success fw-bold" data-bs-toggle="modal" data-bs-target="#settleModal-{{ $order->id }}">
                                        <i class="bi bi-cash-coin me-2"></i>Settle Payment
                                    </button>
                                </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="{{ route('orders.print', $order) }}" target="_blank" class="dropdown-item small text-secondary">
                                        <i class="bi bi-printer me-2"></i>Print Invoice
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>
@if($orders->hasPages())<div class="card-footer">{{ $orders->links() }}</div>@endif
</div>

@foreach($orders as $order)
@if(!$order->payment && !in_array($order->status, ['completed', 'cancelled']))
<div class="modal fade" id="settleModal-{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('orders.settle', $order) }}" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Settle Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="text-muted small">Total Due</div>
                    <div class="fw-bold" style="font-size:1.8rem;color:var(--primary)">৳{{ number_format($order->total_amount, 2) }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile_banking">Mobile Banking</option>
                        <option value="split">Split Payment</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Amount Received</label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number" name="payment_amount" class="form-control" step="0.01" min="{{ $order->total_amount }}" value="{{ $order->total_amount }}" required oninput="calcSettleChange(this, {{ $order->total_amount }})">
                    </div>
                </div>
                <div class="bg-light rounded p-2 text-center">
                    <span class="text-muted small">Change: </span><span class="fw-bold text-success settle-change">৳0.00</span>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Confirm & Complete</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<script>
function calcSettleChange(input, total) {
    const received = parseFloat(input.value) || 0;
    const change = Math.max(0, received - total);
    input.closest('.modal-body').querySelector('.settle-change').textContent = '৳' + change.toFixed(2);
}
</script>
@endsection
