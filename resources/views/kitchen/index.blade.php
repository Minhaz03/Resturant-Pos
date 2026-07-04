@extends('layouts.app')
@section('title','Kitchen Display System')
@push('styles')
<style>
    .kds-card { border-radius:12px; border:none; box-shadow:0 2px 12px rgba(0,0,0,0.1); overflow:hidden; transition:all 0.3s; }
    .kds-card .kds-header { padding:12px 16px; font-weight:700; font-size:0.9rem; display:flex; justify-content:space-between; align-items:center; }
    .kds-card.pending .kds-header { background:var(--primary); color:#fff; }
    .kds-card.preparing .kds-header { background:#0A2647; color:#fff; }
    .kds-card.ready .kds-header { background:#166534; color:#fff; }
    .kds-item { padding:8px 14px; border-bottom:1px solid #f1f5f9; }
    .kds-item:last-child { border-bottom:none; }
    .kds-item-row { display:flex; justify-content:space-between; align-items:center; }
    .elapsed { font-size:0.75rem; padding:3px 8px; border-radius:10px; font-weight:600; }
    .elapsed.ok { background:#d1fae5; color:#065f46; }
    .elapsed.warn { background:#fef3c7; color:#92400e; }
    .elapsed.late { background:#fee2e2; color:#991b1b; }
    .kds-timer { font-size:0.85rem; font-weight:600; }
    body { background:#1a1a2e; }
    .page-content { background:#1a1a2e; }
    .kds-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:16px; }

    /* Grocery / ingredient list inside each kitchen card item */
    .kds-ingredients { margin-top:5px; padding:6px 10px; background:#f8fafc; border-radius:7px; }
    .kds-ingredients .ing-title { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; margin-bottom:3px; display:flex; align-items:center; gap:4px; }
    .kds-ingredient-row { display:flex; justify-content:space-between; align-items:center; font-size:0.76rem; color:#374151; padding:1px 0; }
    .kds-ingredient-row .ing-name { font-weight:500; }
    .kds-ingredient-row .ing-qty { color:#0A2647; font-weight:700; white-space:nowrap; margin-left:8px; }
    .kds-ingredient-row .ing-qty.low-stock { color:#dc2626; }
    .no-ingredients { font-size:0.72rem; color:#94a3b8; font-style:italic; }
</style>
@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-white">Kitchen Display System</h4>
        <p class="text-white-50 small mb-0">Real-time order tracking · Grocery requirements per dish</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-danger px-3 py-2" id="pendingCount">{{ $kitchenOrders->count() }} Pending</span>
        <span class="badge bg-success px-3 py-2" id="readyCount">{{ $readyOrders->count() }} Ready</span>
        <button class="btn btn-sm btn-outline-info" data-bs-toggle="offcanvas" data-bs-target="#historyOffcanvas"><i class="bi bi-clock-history"></i> History</button>
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
                    <div>
                        <span><i class="bi bi-receipt me-1"></i>{{ $order->order_number }}</span>
                        <span>
                            @if($order->type === 'dine_in')
                                Dine In {!! $order->tables->count() > 0 ? '<span class="badge bg-light text-dark ms-1">'.$order->tables->pluck('table_number')->implode(', ').'</span>' : '' !!}
                            @else
                                {{ ucfirst(str_replace('_', ' ', $order->type)) }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="p-2 bg-white">
                    <div class="text-muted small mb-2 px-1">{{ $order->created_at->format('H:i') }} · {{ $order->created_at->diffForHumans() }}</div>
                    @foreach($items as $ki)
                    @php
                        $menuItem = $ki->orderItem?->menuItem;
                        $qty      = $ki->orderItem?->quantity ?? 1;
                        $elapsed  = (int) abs($ki->started_at ? now()->diffInMinutes($ki->started_at) : now()->diffInMinutes($ki->created_at));
                    @endphp
                    <div class="kds-item">
                        <div class="kds-item-row">
                            <div>
                                <div class="fw-semibold" style="font-size:0.88rem">{{ $menuItem?->name }}</div>
                                <div class="text-muted small">x{{ $qty }}
                                    @if($ki->orderItem?->notes)<span class="text-warning"> · {{ $ki->orderItem->notes }}</span>@endif
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                <span class="elapsed {{ $elapsed < 10 ? 'ok' : ($elapsed < 20 ? 'warn' : 'late') }}">{{ $elapsed }}min</span>
                                @if($ki->status === 'pending')
                                <button onclick="updateKitchenStatus({{ $ki->id }},'preparing')" class="btn btn-sm btn-warning py-0 px-2" style="font-size:0.75rem">Start</button>
                                @elseif($ki->status === 'preparing')
                                <button onclick="updateKitchenStatus({{ $ki->id }},'ready')" class="btn btn-sm btn-success py-0 px-2" style="font-size:0.75rem">Ready</button>
                                @endif
                            </div>
                        </div>
                        {{-- Grocery / Ingredient Requirements --}}
                        @if($menuItem && $menuItem->ingredients->isNotEmpty())
                        <div class="kds-ingredients mt-2">
                            <div class="ing-title">
                                <i class="bi bi-basket2-fill"></i> Grocery Needed (×{{ $qty }})
                            </div>
                            @foreach($menuItem->ingredients as $ing)
                            @php
                                $needed       = round($ing->quantity * $qty, 3);
                                $inStock      = $ing->inventoryItem?->quantity ?? 0;
                                $isLow        = $inStock < $needed;
                                $unit         = $ing->inventoryItem?->unit ?? '';
                            @endphp
                            <div class="kds-ingredient-row">
                                <span class="ing-name">{{ $ing->inventoryItem?->name ?? '—' }}</span>
                                <span class="ing-qty {{ $isLow ? 'low-stock' : '' }}" title="{{ $isLow ? 'Low stock! Only '.$inStock.' '.$unit.' available' : 'In stock' }}">
                                    {{ $needed }} {{ $unit }}
                                    @if($isLow)<i class="bi bi-exclamation-triangle-fill ms-1" style="font-size:0.7rem"></i>@endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="kds-ingredients mt-2">
                            <span class="no-ingredients"><i class="bi bi-info-circle me-1"></i>No recipe/ingredients recorded for this item.</span>
                        </div>
                        @endif

                    </div>
                    @endforeach
                    @if($items->count() > 1)
                        <div class="mt-2">
                            @if($items->first()->status === 'pending')
                                <button onclick="updateOrderKitchenStatus({{ $order->id }}, 'preparing')" class="btn btn-sm btn-warning w-100 fw-bold" title="Start All">Start All</button>
                            @elseif($items->first()->status === 'preparing')
                                <button onclick="updateOrderKitchenStatus({{ $order->id }}, 'ready')" class="btn btn-sm btn-success w-100 fw-bold" title="Ready All"><i class="bi bi-check-all"></i> Ready All</button>
                            @endif
                        </div>
                    @endif
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
                <div>
                    <span class="me-2">{{ $order->order_number }}</span>
                    <span class="badge bg-light text-dark">
                        @if($order->type === 'dine_in')
                            Dine In {{ $order->tables->count() > 0 ? '('.$order->tables->pluck('table_number')->implode(', ').')' : '' }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $order->type)) }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="bg-white p-2">
                @foreach($items as $ki)
                <div class="kds-item" style="padding:6px 10px;">
                    <div class="kds-item-row">
                        <span style="font-size:0.85rem">{{ $ki->orderItem?->menuItem?->name }}</span>
                        <div>
                            <span class="badge bg-success" style="font-size:0.7rem">Ready</span>
                            <button onclick="updateKitchenStatus({{ $ki->id }},'served')" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size:0.7rem" title="Mark as Served"><i class="bi bi-check-all"></i></button>
                        </div>
                    </div>
                    @php $menuItem = $ki->orderItem?->menuItem; $qty = $ki->orderItem?->quantity ?? 1; @endphp
                    @if($menuItem && $menuItem->ingredients->isNotEmpty())
                    <div class="kds-ingredients mt-1">
                        <div class="ing-title"><i class="bi bi-basket2-fill"></i> Grocery Used (×{{ $qty }})</div>
                        @foreach($menuItem->ingredients as $ing)
                        <div class="kds-ingredient-row">
                            <span class="ing-name">{{ $ing->inventoryItem?->name ?? '—' }}</span>
                            <span class="ing-qty">{{ round($ing->quantity * $qty, 3) }} {{ $ing->inventoryItem?->unit ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @if($items->count() > 1)
                <div class="mt-2">
                    <button onclick="serveOrder({{ $order->id }})" class="btn btn-sm btn-success w-100 fw-bold" title="Serve Entire Order"><i class="bi bi-check-all"></i> Serve All</button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center text-white-50 small py-3">No ready orders</div>
        @endforelse
    </div>
</div>

<!-- Offcanvas for History -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="historyOffcanvas" aria-labelledby="historyOffcanvasLabel" style="background: #1a1a2e; color: #fff; width: 400px;">
  <div class="offcanvas-header border-bottom border-secondary">
    <h5 class="offcanvas-title" id="historyOffcanvasLabel"><i class="bi bi-clock-history me-2"></i>Today's History</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="row g-2 mb-4">
        <div class="col-6">
            <div class="p-3 rounded bg-dark border border-secondary text-center">
                <h3 class="mb-0 text-success">{{ $readyOrders->count() }}</h3>
                <small class="text-white-50">Orders Ready</small>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 rounded bg-dark border border-secondary text-center">
                <h3 class="mb-0 text-info">{{ $servedOrders->count() }}</h3>
                <small class="text-white-50">Orders Served</small>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 rounded bg-dark border border-secondary text-center">
                <h3 class="mb-0 text-success">{{ $readyOrders->sum(fn($items) => $items->count()) }}</h3>
                <small class="text-white-50">Items Ready</small>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 rounded bg-dark border border-secondary text-center">
                <h3 class="mb-0 text-info">{{ $servedOrders->sum(fn($items) => $items->count()) }}</h3>
                <small class="text-white-50">Items Served</small>
            </div>
        </div>
    </div>
    
    <h6 class="text-white-50 text-uppercase mb-3" style="font-size:0.75rem;letter-spacing:1px">Recently Served</h6>
    @forelse($servedOrders as $orderId => $items)
        @php $order = $items->first()->order @endphp
        <div class="kds-card mb-3 border border-secondary">
            <div class="kds-header" style="background: #0f172a;">
                <span>{{ $order->order_number }}</span>
                <span class="badge bg-secondary text-white">
                    @if($order->type === 'dine_in')
                        Dine In {{ $order->tables->count() > 0 ? '('.$order->tables->pluck('table_number')->implode(', ').')' : '' }}
                    @else
                        {{ ucfirst(str_replace('_', ' ', $order->type)) }}
                    @endif
                </span>
            </div>
            <div class="bg-dark p-2 text-white">
                @foreach($items as $ki)
                <div class="kds-item border-secondary">
                    <span style="font-size:0.85rem">{{ $ki->orderItem?->menuItem?->name }}</span>
                    <span style="font-size:0.7rem" class="text-muted">{{ $ki->updated_at->format('H:i') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center text-white-50 small py-3">No served orders today</div>
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

function serveOrder(orderId) {
    fetch(`/kitchen/order/${orderId}/serve`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(r => r.json()).then(d => {
        if (d.success) setTimeout(() => location.reload(), 500);
    });
}

function updateOrderKitchenStatus(orderId, status) {
    fetch(`/kitchen/order/${orderId}/status`, {
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
