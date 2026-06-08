@extends('layouts.app')
@section('title','Add Inventory Item')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add Inventory Item</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('inventory.store') }}">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">SKU</label><input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Auto-generated if empty"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Category</label><input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="e.g. Dairy, Spices"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                <select name="unit" class="form-select" required>
                    @foreach(['kg','g','liter','ml','piece','box','bottle','bag','dozen'] as $u)
                    <option value="{{ $u }}" {{ old('unit')==$u?'selected':'' }}>{{ ucfirst($u) }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Initial Quantity</label><input type="number" name="quantity" class="form-control" value="{{ old('quantity',0) }}" step="0.01" min="0"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Min Stock Level <span class="text-danger">*</span></label><input type="number" name="min_quantity" class="form-control" value="{{ old('min_quantity',0) }}" step="0.01" min="0" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Unit Cost (৳)</label><input type="number" name="unit_cost" class="form-control" value="{{ old('unit_cost',0) }}" step="0.01" min="0"></div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="track_inventory" id="trackInv" value="1" {{ old('track_inventory',1)?'checked':'' }}>
                    <label class="form-check-label" for="trackInv">Track Inventory</label>
                </div>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Supplier</label>
                <select name="supplier_id" class="form-select"><option value="">None</option>
                @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Storage Location</label><input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Shelf A1"></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Add Item</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
