@extends('layouts.app')
@section('title','Sales Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Sales Report</h4><p class="text-muted small mb-0">Revenue and transaction analysis</p></div>
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export</button>
        <ul class="dropdown-menu shadow border-0">
            <li><a class="dropdown-item" href="{{ route('reports.sales.pdf', request()->query()) }}"><i class="bi bi-file-pdf me-2 text-danger"></i>Download PDF</a></li>
            <li><a class="dropdown-item" href="{{ route('reports.sales.csv', request()->query()) }}"><i class="bi bi-filetype-csv me-2 text-success"></i>Download CSV</a></li>
            <li><a class="dropdown-item" href="{{ route('reports.sales.excel', request()->query()) }}"><i class="bi bi-file-earmark-excel me-2 text-success"></i>Download Excel</a></li>
        </ul>
    </div>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold">From Date</label><input type="date" name="from" class="form-control" value="{{ request('from',now()->startOfMonth()->toDateString()) }}"></div>
        <div class="col-md-3"><label class="form-label fw-semibold">To Date</label><input type="date" name="to" class="form-control" value="{{ request('to',now()->toDateString()) }}"></div>
        <div class="col-md-3"><label class="form-label fw-semibold">Group By</label>
            <select name="group_by" class="form-select">
                <option value="day" {{ request('group_by','day')=='day'?'selected':'' }}>Day</option>
                <option value="week" {{ request('group_by')=='week'?'selected':'' }}>Week</option>
                <option value="month" {{ request('group_by')=='month'?'selected':'' }}>Month</option>
            </select></div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Generate Report</button></div>
    </form>
</div></div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Revenue</p><h4 class="fw-bold" style="color:var(--primary)">৳{{ number_format($summary['total_revenue'] ?? 0,2) }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Orders</p><h4 class="fw-bold" style="color:var(--secondary)">{{ $summary['total_orders'] ?? 0 }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Avg Order Value</p><h4 class="fw-bold text-success">৳{{ number_format($summary['avg_order_value'] ?? 0,2) }}</h4></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Tax</p><h4 class="fw-bold text-warning">৳{{ number_format($summary['total_tax'] ?? 0,2) }}</h4></div></div></div>
</div>

{{-- DATA TABLE first --}}
<div class="card mb-4"><div class="card-header fw-semibold d-flex justify-content-between align-items-center">Sales Data
</div>
<div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Period</th><th>Orders</th><th>Revenue</th><th>Tax</th><th>Discount</th><th>Net</th></tr></thead>
        <tbody>
            @forelse($salesData as $row)
            <tr>
                <td class="fw-semibold">{{ $row['period'] }}</td>
                <td>{{ $row['orders'] }}</td>
                <td>৳{{ number_format($row['revenue'],2) }}</td>
                <td class="text-warning">৳{{ number_format($row['tax'] ?? 0,2) }}</td>
                <td class="text-danger">৳{{ number_format($row['discount'] ?? 0,2) }}</td>
                <td class="fw-bold text-success">৳{{ number_format($row['net'] ?? $row['revenue'],2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No data for selected period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>

{{-- CHARTS below the data table --}}
<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="card"><div class="card-header fw-semibold">Revenue Trend</div>
        <div class="card-body"><canvas id="salesChart" height="280"></canvas></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card"><div class="card-header fw-semibold">Payment Methods</div>
        <div class="card-body"><canvas id="paymentChart" height="280"></canvas></div></div>
    </div>
</div>

@push('scripts')
<script>
const salesData = @json($salesData ?? []);
const labels = salesData.map(r => r.period);
const revenues = salesData.map(r => r.revenue);
new Chart(document.getElementById('salesChart'), {
    type:'bar',
    data:{ labels, datasets:[{ label:'Revenue (৳)', data:revenues, backgroundColor:'rgba(139,0,0,0.7)', borderColor:'#8B0000', borderWidth:1 }] },
    options:{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
});
const payMethods = @json($paymentMethods ?? []);
new Chart(document.getElementById('paymentChart'), {
    type:'doughnut',
    data:{ labels:payMethods.map(p=>p.method), datasets:[{ data:payMethods.map(p=>p.total), backgroundColor:['#8B0000','#0A2647','#D4AF37','#28a745','#dc3545'] }] },
    options:{ responsive:true, plugins:{ legend:{ position:'bottom' } } }
});
</script>
@endpush
@endsection
