@extends('layouts.app')
@section('title','Customer Profile')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $customer->name }}</h4>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px">
                    <span class="fw-bold fs-2" style="color:var(--primary)">{{ strtoupper(substr($customer->name,0,1)) }}</span>
                </div>
                <h5 class="fw-bold">{{ $customer->name }}</h5>
                <p class="text-muted small mb-0">{{ $customer->phone }}</p>
                <p class="text-muted small">{{ $customer->email ?? '' }}</p>
                <span class="badge {{ $customer->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($customer->status) }}</span>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Stats</div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    <div class="col-4"><div class="fw-bold fs-5" style="color:var(--primary)">{{ $customer->total_orders }}</div><div class="small text-muted">Orders</div></div>
                    <div class="col-4"><div class="fw-bold fs-5" style="color:var(--secondary)">৳{{ number_format($customer->total_spent,0) }}</div><div class="small text-muted">Spent</div></div>
                    <div class="col-4"><div class="fw-bold fs-5 text-warning">{{ $customer->loyalty_points }}</div><div class="small text-muted">Points</div></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Info</div>
            <div class="card-body small">
                @if($customer->address)<div class="mb-1"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $customer->address }}</div>@endif
                @if($customer->dob)<div class="mb-1"><i class="bi bi-cake me-2 text-muted"></i>{{ $customer->dob->format('d M Y') }}</div>@endif
                @if($customer->gender)<div><i class="bi bi-person me-2 text-muted"></i>{{ ucfirst($customer->gender) }}</div>@endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header">Recent Orders</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Order #</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($customer->orders->take(10) as $order)
                        <tr>
                            <td><a href="{{ route('orders.show',$order) }}" class="fw-semibold text-decoration-none">{{ $order->order_number }}</a></td>
                            <td>{{ $order->items->count() }}</td>
                            <td class="fw-semibold">৳{{ number_format($order->total_amount,0) }}</td>
                            <td><span class="badge bg-{{ match($order->status){'completed'=>'success','cancelled'=>'danger',default=>'primary'} }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Loyalty Points History</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Type</th><th>Points</th><th>Balance</th><th>Description</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($customer->loyaltyTransactions->take(10) as $lt)
                        <tr>
                            <td><span class="badge {{ $lt->type=='earn'?'bg-success':'bg-warning text-dark' }}">{{ ucfirst($lt->type) }}</span></td>
                            <td class="{{ $lt->points>0?'text-success':'text-danger' }} fw-semibold">{{ $lt->points > 0 ? '+' : '' }}{{ $lt->points }}</td>
                            <td>{{ $lt->balance_after }}</td>
                            <td class="text-muted small">{{ $lt->description }}</td>
                            <td class="text-muted small">{{ $lt->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No loyalty history</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
