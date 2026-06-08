@extends('layouts.app')
@section('title','Kitchen Display System')
@push('styles')
<style>
    .kds-card { border-radius:12px; border:none; box-shadow:0 2px 12px rgba(0,0,0,0.1); overflow:hidden; transition:all 0.3s; }
    .kds-card .kds-header { padding:12px 16px; font-weight:700; font-size:0.9rem; display:flex; justify-content:space-between; align-items:center; }
    .kds-card.pending .kds-header { background:var(--primary); color:#fff; }
    .kds-card.preparing .kds-header { background:#0A2647; color:#fff; }
    .kds-card.ready .kds-header { background:#166534; color:#fff; }
    .kds-item { padding:8px 14px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
    .kds-item:last-child { border-bottom:none; }
    .elapsed { font-size:0.75rem; padding:3px 8px; border-radius:10px; font-weight:600; }
    .elapsed.ok { background:#d1fae5; color:#065f46; }
    .elapsed.warn { background:#fef3c7; color:#92400e; }
    .elapsed.late { background:#fee2e2; color:#991b1b; }
    .kds-timer { font-size:0.85rem; font-weight:600; }
    body { background:#1a1a2e; }
    .page-content { background:#1a1a2e; }
    .kds-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px; }
</style>
@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-white">Kitchen Display System</h4>
        <p class="text-white-50 small mb-0">Real-time order tracking</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-danger px-3 py-2" id="pendingCount">{{ $kitchenOrders->count() }} Pending</span>
        <span class="badge bg-success px-3 py-2" id="readyCount">{{ $readyOrders->count() }} Ready</span>
        <button class="btn btn-sm btn-outline-light" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
    </div>
</div>

<div class="row g-3">
    <!-- Active Orders -->
    <div class="col-xl-9">
        <h6 class="text-white-50 text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:1px">Active Kitchen Orders</h6>
        @if($kitchenOrders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
            <p class="text-white-50">All clear! No pending orders.</p>
        </div>
        @else
        <div class="kds-grid">
            @foreach($kitchenOrders as $orderId => $items)
            @php $order = $items->first()->order @endphp
            <div class="kds-card {{ $items->first()->status }}" id="order-{{ $orderId }}">
                <div class="kds-header">
                    <span><i class="bi bi-receipt me-1"></i>{{ $order->order_number }}</span>
                    <span>{{ $order->table?->table_number ?? 'Takeaway' }}</span>
                </div>
                <div class="p-2 bg-white">
                    <div class="text-muted small mb-2 px-1">{{ $order->created_at->format('H:i') }} · {{ $order->created_at->diffForHumans() }}</div>
                    @foreach($items as $ki)
                    <div class="kds-item">
                        <div>
                            <div class="fw-semibold" style="font-size:0.88rem">{{ $ki->orderItem?->menuItem?->name }}</div>
                            <div class="text-muted small">x{{ $ki->orderItem?->quantity }}
                                @if($ki->orderItem?->notes)<span class="text-warning"> · {{ $ki->orderItem->notes }}</span>@endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            @php $elapsed = $ki->started_at ? now()->diffInMinutes($ki->started_at) : now()->diffInMinutes($ki->created_at); @endphp
                            <span class="elapsed {{ $elapsed < 10 ? 'ok' : ($elapsed < 20 ? 'warn' : 'late') }}">{{ $elapsed }}min</span>
                            @if($ki->status === 'pending')
                            <button onclick="updateKitchenStatus({{ $ki->id }},'preparing')" class="btn btn-sm btn-warning py-0 px-2" style="font-size:0.75rem">Start</button>
                            @elseif($ki->status === 'preparing')
                            <button onclick="updateKitchenStatus({{ $ki->id }},'ready')" class="btn btn-sm btn-success py-0 px-2" style="font-size:0.75rem">Ready</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Ready Column -->
    <div class="col-xl-3">
        <h6 class="text-white-50 text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:1px">Ready to Serve</h6>
        @forelse($readyOrders as $orderId => $items)
        @php $order = $items->first()->order @endphp
        <div class="kds-card ready mb-3">
            <div class="kds-header">
                <span>{{ $order->order_number }}</span>
                <span>{{ $order->table?->table_number ?? '—' }}</span>
            </div>
            <div class="bg-white p-2">
                @foreach($items as $ki)
                <div class="kds-item">
                    <span style="font-size:0.85rem">{{ $ki->orderItem?->menuItem?->name }}</span>
                    <span class="badge bg-success" style="font-size:0.7rem">Ready</span>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center text-white-50 small py-3">No ready orders</div>
        @endforelse
    </div>
</div>
@endsection
@push('scripts')
<script>
function updateKitchenStatus(id, status) {
    fetch(`/kitchen/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ status })
    }).then(r => r.json()).then(d => {
        if (d.success) setTimeout(() => location.reload(), 500);
    });
}

// Auto-refresh every 30 seconds
setInterval(() => location.reload(), 30000);
</script>
@endpush
