@extends('layouts.app')
@section('title','Edit Inventory Item')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('inventory.show',$item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $item->name }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('inventory.update',$item) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Item Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$item->name) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">SKU</label><input type="text" name="sku" class="form-control" value="{{ old('sku',$item->sku) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Category</label><input type="text" name="category" class="form-control" value="{{ old('category',$item->category) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Unit</label>
                <select name="unit" class="form-select">
                    @foreach(['kg','g','liter','ml','piece','box','bottle','bag','dozen'] as $u)
                    <option value="{{ $u }}" {{ old('unit',$item->unit)==$u?'selected':'' }}>{{ ucfirst($u) }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Current Quantity</label><input type="number" class="form-control" value="{{ $item->quantity }}" disabled></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Min Stock Level</label><input type="number" name="min_quantity" class="form-control" value="{{ old('min_quantity',$item->min_quantity) }}" step="0.01" min="0" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Unit Cost (৳)</label><input type="number" name="unit_cost" class="form-control" value="{{ old('unit_cost',$item->unit_cost) }}" step="0.01" min="0"></div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="track_inventory" id="trackInv" value="1" {{ old('track_inventory',$item->track_inventory)?'checked':'' }}>
                    <label class="form-check-label" for="trackInv">Track Inventory</label>
                </div>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description',$item->description) }}</textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Supplier</label>
                <select name="supplier_id" class="form-select"><option value="">None</option>
                @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id',$item->supplier_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Storage Location</label><input type="text" name="location" class="form-control" value="{{ old('location',$item->location) }}"></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Item</button>
            <a href="{{ route('inventory.show',$item) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
