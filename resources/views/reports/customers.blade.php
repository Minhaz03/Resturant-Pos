@extends('layouts.app')
@section('title','Customer Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Customer Report</h4><p class="text-muted small mb-0">Customer analytics and loyalty</p></div>
    {{-- Excel export available when maatwebsite/excel is configured --}}
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Customers</p><h4 class="fw-bold" style="color:var(--secondary)">{{ $stats['total'] ?? 0 }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Active This Month</p><h4 class="fw-bold text-success">{{ $stats['active_this_month'] ?? 0 }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Loyalty Points</p><h4 class="fw-bold text-warning">{{ number_format($stats['total_points'] ?? 0) }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Avg Spend / Customer</p><h4 class="fw-bold" style="color:var(--primary)">৳{{ number_format($stats['avg_spend'] ?? 0,2) }}</h4></div></div></div>
</div>
<div class="card"><div class="card-header fw-semibold">Top Customers by Spend</div>
<div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Customer</th><th>Phone</th><th>Total Orders</th><th>Total Spent</th><th>Loyalty Points</th><th>Last Visit</th></tr></thead>
        <tbody>
            @forelse($customers as $i => $c)
            <tr>
                <td class="text-muted">{{ $i+1 }}</td>
                <td>
                    <div class="fw-semibold">{{ $c->name }}</div>
                    <div class="text-muted small">{{ $c->email ?? '' }}</div>
                </td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->total_orders }}</td>
                <td class="fw-bold" style="color:var(--primary)">৳{{ number_format($c->total_spent,2) }}</td>
                <td><span class="badge bg-warning text-dark">{{ number_format($c->loyalty_points) }} pts</span></td>
                <td class="text-muted small">{{ $c->updated_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No customer data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
@endsection
