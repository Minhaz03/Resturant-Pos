@extends('layouts.app')
@section('title', 'Tables')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Table Management</h4>
        <p class="text-muted small mb-0">
            <span class="badge bg-success me-1">{{ $stats['available'] }} Available</span>
            <span class="badge bg-danger me-1">{{ $stats['occupied'] }} Occupied</span>
            <span class="badge bg-warning text-dark me-1">{{ $stats['reserved'] }} Reserved</span>
            <span class="badge bg-secondary">{{ $stats['total'] }} Total</span>
        </p>
    </div>
    @can('create tables')
    <a href="{{ route('tables.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Table</a>
    @endcan
</div>

<div class="row g-3">
    @forelse($tables as $table)
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card text-center" style="border-top: 4px solid {{ match($table->status) { 'available'=>'#22c55e', 'occupied'=>'#ef4444', 'reserved'=>'#f59e0b', default=>'#94a3b8' } }}">
            <div class="card-body py-3">
                <div class="fw-bold fs-4 mb-1" style="color:var(--secondary)">{{ $table->table_number }}</div>
                <div class="text-muted small mb-2">{{ $table->name ?? '' }}</div>
                <div class="mb-2"><i class="bi bi-people text-muted me-1"></i><span class="small">{{ $table->capacity }} seats</span></div>
                <div class="mb-2"><span class="badge" style="background:{{ match($table->status){'available'=>'#dcfce7','occupied'=>'#fee2e2','reserved'=>'#fef3c7',default=>'#f3f4f6'} }};color:{{ match($table->status){'available'=>'#166534','occupied'=>'#991b1b','reserved'=>'#92400e',default=>'#374151'} }}">{{ ucfirst($table->status) }}</span></div>
                @if($table->activeOrder)<div class="text-muted" style="font-size:0.72rem">{{ $table->activeOrder->order_number }}</div>@endif
                @can('edit tables')
                <a href="{{ route('tables.edit',$table) }}" class="btn btn-sm btn-outline-secondary mt-2 py-0 px-2"><i class="bi bi-pencil"></i></a>
                @endcan
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted"><i class="bi bi-grid fs-1 d-block mb-2"></i>No tables found.</div>
    @endforelse
</div>
@endsection
