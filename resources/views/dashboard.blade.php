@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Dashboard</h4>
        <p class="text-muted mb-0 small">Welcome back, <strong>{{ auth()->user()->name }}</strong>! Here's what's happening today.</p>
    </div>
    <span class="badge bg-success px-3 py-2" style="font-size:0.78rem">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('D, d M Y') }}
    </span>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-primary">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">৳{{ number_format($todaySales,0) }}</div>
            <div class="stat-label">Today's Sales</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-secondary">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-label">Today's Orders</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-success">
            <div class="stat-icon"><i class="bi bi-calendar-week"></i></div>
            <div class="stat-value">৳{{ number_format($weeklySales,0) }}</div>
            <div class="stat-label">Weekly Sales</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-info">
            <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
            <div class="stat-value">৳{{ number_format($monthlySales,0) }}</div>
            <div class="stat-label">Monthly Sales</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-warning">
            <div class="stat-icon"><i class="bi bi-grid-3x3-gap"></i></div>
            <div class="stat-value">{{ $activeTables }}/{{ $totalTables }}</div>
            <div class="stat-label">Tables Active</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card bg-grad-purple">
            <div class="stat-icon"><i class="bi bi-fire"></i></div>
            <div class="stat-value">{{ $kitchenPending }}</div>
            <div class="stat-label">Kitchen Pending</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-graph-up-arrow me-2" style="color:var(--primary)"></i>Revenue & Orders (Last 7 Days)</div>
            <div class="card-body"><canvas id="revenueChart" height="90"></canvas></div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2" style="color:var(--primary)"></i>Order Status Today</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="orderStatusChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables -->
<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2" style="color:var(--primary)"></i>Active Orders</span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($activeOrders->take(7) as $order)
                <div class="d-flex align-items-center px-3 py-2 border-bottom justify-content-between" style="font-size:0.855rem">
                    <div>
                        <a href="{{ route('orders.show',$order) }}" class="fw-semibold text-decoration-none" style="color:var(--secondary)">{{ $order->order_number }}</a>
                        <span class="text-muted ms-2">{{ $order->tables->count() > 0 ? $order->tables->pluck('table_number')->implode(', ') : 'Takeaway' }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">{{ $order->items->count() }} items</span>
                        <span class="fw-semibold">৳{{ number_format($order->total_amount,0) }}</span>
                        <span class="badge" style="font-size:0.72rem;background:{{ match($order->status){'pending'=>'#fef3c7','confirmed'=>'#dbeafe','preparing'=>'#ede9fe','ready'=>'#d1fae5','served'=>'#cffafe',default=>'#f3f4f6'} }};color:{{ match($order->status){'pending'=>'#92400e','confirmed'=>'#1e40af','preparing'=>'#5b21b6','ready'=>'#065f46','served'=>'#164e63',default=>'#374151'} }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No active orders</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><i class="bi bi-trophy me-2 text-warning"></i>Top Items (30 Days)</div>
                    <div class="card-body p-0">
                        @forelse($topSellingItems as $i => $item)
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="font-size:0.85rem">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill" style="background:var(--primary);font-size:0.72rem">{{ $i+1 }}</span>
                                <span>{{ $item->name }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small">{{ $item->total_qty }}x</span>
                                <span class="fw-semibold">৳{{ number_format($item->total_revenue,0) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted small">No sales data</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header"><i class="bi bi-lightning-fill me-2 text-warning"></i>Quick Actions</div>
                    <div class="card-body">
                        <div class="row g-2">
                            @can('access pos')
                            <div class="col-6"><a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm w-100"><i class="bi bi-cart3 me-1"></i>POS</a></div>
                            @endcan
                            @can('create orders')
                            <div class="col-6"><a href="{{ route('orders.create') }}" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-plus me-1"></i>New Order</a></div>
                            @endcan
                            @can('create reservations')
                            <div class="col-6"><a href="{{ route('reservations.create') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-calendar-plus me-1"></i>Reserve</a></div>
                            @endcan
                            @can('view reports')
                            <div class="col-6"><a href="{{ route('reports.sales') }}" class="btn btn-outline-info btn-sm w-100"><i class="bi bi-bar-chart me-1"></i>Reports</a></div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($lowStockItems->isNotEmpty())
<div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:10px">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong>Low Stock Alert!</strong> {{ $lowStockItems->count() }} item(s) are below minimum stock level.
        <a href="{{ route('inventory.index', ['low_stock'=>1]) }}" class="alert-link ms-2">View inventory →</a>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('revenueChart').getContext('2d'), {
    data: {
        labels: {!! json_encode(collect($revenueChart)->pluck('date')) !!},
        datasets: [
            { type:'bar', label:'Revenue (৳)', data: {!! json_encode(collect($revenueChart)->pluck('revenue')) !!}, backgroundColor:'rgba(139,0,0,0.75)', borderRadius:6 },
            { type:'line', label:'Orders', data: {!! json_encode(collect($revenueChart)->pluck('orders')) !!}, borderColor:'#D4AF37', backgroundColor:'rgba(212,175,55,0.1)', tension:0.4, yAxisID:'y2', pointRadius:4, pointBackgroundColor:'#D4AF37' }
        ]
    },
    options: {
        responsive:true, plugins:{legend:{labels:{font:{size:11}}}},
        scales: {
            y: { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{callback:v=>'৳'+Number(v).toLocaleString()} },
            y2: { beginAtZero:true, position:'right', grid:{display:false} }
        }
    }
});

const sd = {!! json_encode($orderStatusDist) !!};
if(sd.length) {
    new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
        type:'doughnut',
        data: {
            labels: sd.map(s=>s.status.charAt(0).toUpperCase()+s.status.slice(1)),
            datasets:[{ data:sd.map(s=>s.count), backgroundColor:['#fbbf24','#60a5fa','#a78bfa','#34d399','#2dd4bf','#4ade80','#f87171'], borderWidth:0 }]
        },
        options: { cutout:'68%', plugins:{legend:{position:'bottom',labels:{font:{size:10},padding:6}}} }
    });
}
</script>
@endpush
