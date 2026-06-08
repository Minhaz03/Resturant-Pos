@extends('layouts.app')
@section('title',$menuItem->name)
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $menuItem->name }}</h4>
    <div class="ms-auto d-flex gap-2">
        @can('edit menu_items')<a href="{{ route('menu.edit',$menuItem) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div style="height:250px;overflow:hidden;border-radius:8px 8px 0 0">
                @if($menuItem->image)
                <img src="{{ asset('storage/'.$menuItem->image) }}" class="w-100 h-100" style="object-fit:cover">
                @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background:#f5f7fa"><i class="bi bi-image text-muted" style="font-size:4rem"></i></div>
                @endif
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="h4 fw-bold mb-0" style="color:var(--primary)">৳{{ number_format($menuItem->effective_price,2) }}</div>
                        @if($menuItem->discount > 0)<div class="text-muted small text-decoration-line-through">৳{{ number_format($menuItem->price,2) }}</div>@endif
                    </div>
                    <span class="badge {{ $menuItem->is_available?'bg-success':'bg-secondary' }}">{{ $menuItem->is_available?'Available':'Unavailable' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">SKU</span><span class="fw-semibold">{{ $menuItem->sku }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Category</span><span class="fw-semibold">{{ $menuItem->category?->name ?? '—' }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Cost Price</span><span class="fw-semibold">৳{{ number_format($menuItem->cost_price ?? 0,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax Rate</span><span class="fw-semibold">{{ $menuItem->tax_rate ?? 0 }}%</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Prep Time</span><span class="fw-semibold">{{ $menuItem->prep_time ? $menuItem->prep_time.' min' : '—' }}</span></div>
                @if($menuItem->discount > 0)<div class="d-flex justify-content-between"><span class="text-muted">Discount</span><span class="fw-semibold text-danger">{{ $menuItem->discount }}%</span></div>@endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Item Details</div>
            <div class="card-body">
                @if($menuItem->description)<p class="text-muted mb-3">{{ $menuItem->description }}</p>@endif
                <div class="row g-2">
                    @if($menuItem->is_featured)<div class="col-auto"><span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span></div>@endif
                    @if($menuItem->barcode)<div class="col-auto"><span class="badge bg-light text-dark">Barcode: {{ $menuItem->barcode }}</span></div>@endif
                </div>
                @if($menuItem->allergens || $menuItem->ingredients)
                <hr>
                @if($menuItem->ingredients)<div class="mb-2"><small class="text-muted fw-semibold d-block">Ingredients</small>{{ $menuItem->ingredients }}</div>@endif
                @if($menuItem->allergens)<div><small class="text-muted fw-semibold d-block">Allergens</small>{{ $menuItem->allergens }}</div>@endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
