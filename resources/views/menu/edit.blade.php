@extends('layouts.app')
@section('title', 'Edit Menu Item')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $menuItem->name }}</h4>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('menu.update',$menuItem) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name',$menuItem->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id',$menuItem->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description',$menuItem->description) }}</textarea></div>
                        <div class="col-md-4"><label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',$menuItem->price) }}" required></div>
                        <div class="col-md-4"><label class="form-label">Cost Price (৳)</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price',$menuItem->cost_price) }}"></div>
                        <div class="col-md-4"><label class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount',$menuItem->discount) }}"></div>
                        <div class="col-md-4"><label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate',$menuItem->tax_rate) }}"></div>
                        <div class="col-md-4"><label class="form-label">Prep Time (min)</label>
                            <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time',$menuItem->prep_time) }}"></div>
                        <div class="col-md-4"><label class="form-label">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit',$menuItem->unit) }}"></div>
                        <div class="col-md-6"><label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku',$menuItem->sku) }}"></div>
                        <div class="col-md-6"><label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode',$menuItem->barcode) }}"></div>
                        <div class="col-12">
                            <label class="form-label">Image</label>
                            @if($menuItem->image)<div class="mb-2"><img src="{{ asset('storage/'.$menuItem->image) }}" height="70" style="border-radius:8px"></div>@endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="avail" {{ old('is_available',$menuItem->is_available)?'checked':'' }}>
                                <label class="form-check-label" for="avail">Available</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured',$menuItem->is_featured)?'checked':'' }}>
                                <label class="form-check-label" for="feat">Featured</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="stat" {{ old('status',$menuItem->status)?'checked':'' }}>
                                <label class="form-check-label" for="stat">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Update</button>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
