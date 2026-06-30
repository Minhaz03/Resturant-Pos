@extends('layouts.app')
@section('title','Edit Coupon')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('coupons.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: <code>{{ $coupon->code }}</code></h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('coupons.update',$coupon) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Coupon Code</label><input type="text" name="code" class="form-control" value="{{ old('code',$coupon->code) }}" required style="text-transform:uppercase"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Coupon Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$coupon->name) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Discount Type</label>
                <select name="type" id="couponType" class="form-select" onchange="toggleMaxDiscount()">
                    <option value="percentage" {{ old('type',$coupon->type)=='percentage'?'selected':'' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type',$coupon->type)=='fixed'?'selected':'' }}>Fixed Amount (৳)</option>
                </select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Discount Value</label>
                <div class="input-group"><span class="input-group-text" id="discountSymbol">{{ $coupon->type=='percentage'?'%':'৳' }}</span><input type="number" name="value" class="form-control" value="{{ old('value',$coupon->value) }}" step="0.01" min="0" required></div></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Min Order Amount (৳)</label><input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount',$coupon->min_order_amount) }}" step="0.01" min="0"></div>
            <div class="col-md-6" id="maxDiscountDiv"><label class="form-label fw-semibold">Max Discount (৳)</label><input type="number" name="max_discount" class="form-control" value="{{ old('max_discount',$coupon->max_discount) }}" step="0.01" min="0"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Usage Limit</label><input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit',$coupon->usage_limit) }}" min="1"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Used Count</label><input type="number" class="form-control" value="{{ $coupon->used_count ?? 0 }}" disabled></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Per User Limit <span class="text-danger">*</span></label><input type="number" name="per_user_limit" class="form-control" value="{{ old('per_user_limit',$coupon->per_user_limit ?? 1) }}" min="1" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date',$coupon->start_date?->format('Y-m-d')) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Expiry Date</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date',$coupon->end_date?->format('Y-m-d')) }}"></div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="isActive" value="1" {{ old('status', $coupon->status) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active</label>
                </div>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description',$coupon->description) }}</textarea></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Coupon</button>
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
toggleMaxDiscount();
</script>
@endpush
@endsection
