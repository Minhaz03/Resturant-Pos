@extends('layouts.app')
@section('title','Tax Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Tax Report</h4><p class="text-muted small mb-0">VAT/Tax collection summary</p></div>
    {{-- Excel export available when maatwebsite/excel is configured --}}
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4"><label class="form-label fw-semibold">From Date</label><input type="date" name="from" class="form-control" value="{{ request('from',now()->startOfMonth()->toDateString()) }}"></div>
        <div class="col-md-4"><label class="form-label fw-semibold">To Date</label><input type="date" name="to" class="form-control" value="{{ request('to',now()->toDateString()) }}"></div>
        <div class="col-md-4"><button type="submit" class="btn btn-primary w-100">Generate</button></div>
    </form>
</div></div>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Revenue</p><h4 class="fw-bold" style="color:var(--secondary)">৳{{ number_format($summary['total_revenue'] ?? 0,2) }}</h4></div></div></div>
    <div class="col-md-4"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Tax Collected</p><h4 class="fw-bold" style="color:var(--primary)">৳{{ number_format($summary['total_tax'] ?? 0,2) }}</h4></div></div></div>
    <div class="col-md-4"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Taxable Orders</p><h4 class="fw-bold text-success">{{ $summary['taxable_orders'] ?? 0 }}</h4></div></div></div>
</div>
<div class="card"><div class="card-header fw-semibold">Tax Breakdown</div>
<div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Date</th><th>Order #</th><th>Subtotal</th><th>Tax Rate</th><th>Tax Amount</th><th>Total</th></tr></thead>
        <tbody>
            @forelse($taxData as $row)
            <tr>
                <td class="text-muted small">{{ $row['date'] }}</td>
                <td class="fw-semibold" style="color:var(--secondary)">{{ $row['order_number'] }}</td>
                <td>৳{{ number_format($row['subtotal'],2) }}</td>
                <td>{{ $row['tax_rate'] }}%</td>
                <td class="text-warning fw-semibold">৳{{ number_format($row['tax_amount'],2) }}</td>
                <td class="fw-bold">৳{{ number_format($row['total'],2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No tax data for selected period.</td></tr>
            @endforelse
        </tbody>
        @if(count($taxData ?? []) > 0)
        <tfoot class="table-light">
            <tr><td colspan="4" class="text-end fw-bold">Totals:</td>
                <td class="fw-bold text-warning">৳{{ number_format($summary['total_tax'] ?? 0,2) }}</td>
                <td class="fw-bold">৳{{ number_format($summary['total_revenue'] ?? 0,2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div></div></div>
@endsection
