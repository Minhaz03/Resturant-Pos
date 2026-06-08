@extends('layouts.app')
@section('title','Edit Customer')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $customer->name }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('customers.update',$customer) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$customer->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$customer->phone) }}" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$customer->email) }}"></div>
            <div class="col-md-6"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" value="{{ old('dob',$customer->dob?->format('Y-m-d')) }}"></div>
            <div class="col-md-6"><label class="form-label">Gender</label>
                <select name="gender" class="form-select"><option value="">Select</option>
                @foreach(['male','female','other'] as $g)<option value="{{ $g }}" {{ old('gender',$customer->gender)==$g?'selected':'' }}>{{ ucfirst($g) }}</option>@endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select"><option value="active" {{ old('status',$customer->status)=='active'?'selected':'' }}>Active</option><option value="inactive" {{ old('status',$customer->status)=='inactive'?'selected':'' }}>Inactive</option></select></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$customer->address) }}</textarea></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$customer->notes) }}</textarea></div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
