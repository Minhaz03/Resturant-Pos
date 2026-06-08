@extends('layouts.app')
@section('title','Edit Supplier')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $supplier->name }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('suppliers.update',$supplier) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Supplier Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$supplier->name) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Company</label><input type="text" name="company" class="form-control" value="{{ old('company',$supplier->company) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Contact Person</label><input type="text" name="contact_person" class="form-control" value="{{ old('contact_person',$supplier->contact_person) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$supplier->phone) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$supplier->email) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">City</label><input type="text" name="city" class="form-control" value="{{ old('city',$supplier->city) }}"></div>
            <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$supplier->address) }}</textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Payment Terms</label><input type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms',$supplier->payment_terms) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status',$supplier->status)=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status',$supplier->status)=='inactive'?'selected':'' }}>Inactive</option>
                </select></div>
            <div class="col-12"><label class="form-label fw-semibold">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$supplier->notes) }}</textarea></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Supplier</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
