@extends('layouts.app')
@section('title','Create Coupon')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('coupons.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Create Coupon</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('coupons.store') }}">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                <div class="input-group"><input type="text" name="code" id="couponCode" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. SAVE20" required style="text-transform:uppercase">
                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()"><i class="bi bi-shuffle"></i></button></div>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Coupon Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                <select name="type" id="couponType" class="form-select" onchange="toggleMaxDiscount()">
                    <option value="percentage" {{ old('type')=='percentage'?'selected':'' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type')=='fixed'?'selected':'' }}>Fixed Amount (৳)</option>
                </select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                <div class="input-group"><span class="input-group-text" id="discountSymbol">%</span><input type="number" name="value" class="form-control" value="{{ old('value') }}" step="0.01" min="0" required></div></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Min Order Amount (৳)</label><input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount',0) }}" step="0.01" min="0"></div>
            <div class="col-md-6" id="maxDiscountDiv"><label class="form-label fw-semibold">Max Discount (৳)</label><input type="number" name="max_discount" class="form-control" value="{{ old('max_discount') }}" step="0.01" min="0" placeholder="Leave empty for no limit"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Usage Limit</label><input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" min="1" placeholder="Leave empty for unlimited"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Per User Limit</label><input type="number" name="per_user_limit" class="form-control" value="{{ old('per_user_limit',1) }}" min="1"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date',today()->toDateString()) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Expiry Date</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"></div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="isActive" value="1" {{ old('status',1)?'checked':'' }}>
                    <label class="form-check-label" for="isActive">Active</label>
                </div>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Create Coupon</button>
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@push('scripts')
<script>
function toggleMaxDiscount() {
    const t = document.getElementById('couponType').value;
    document.getElementById('maxDiscountDiv').style.display = t==='percentage'?'block':'none';
    document.getElementById('discountSymbol').textContent = t==='percentage'?'%':'৳';
}
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for(let i=0;i<8;i++) code += chars.charAt(Math.floor(Math.random()*chars.length));
    document.getElementById('couponCode').value = code;
}
toggleMaxDiscount();
</script>
@endpush
@endsection
