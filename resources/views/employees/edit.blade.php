@extends('layouts.app')
@section('title','Edit Employee')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $employee->name }}</h4>
    <span class="badge bg-light text-dark ms-auto">{{ $employee->employee_id }}</span>
</div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('employees.update',$employee) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$employee->name) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$employee->email) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$employee->phone) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select" required>
                    @foreach(['manager','cashier','waiter','kitchen_staff','delivery_staff'] as $r)
                    <option value="{{ $r }}" {{ old('role',$employee->role)==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Salary (৳)</label><input type="number" name="salary" class="form-control" value="{{ old('salary',$employee->salary) }}" step="0.01" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Hire Date</label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date',$employee->hire_date->format('Y-m-d')) }}" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth',$employee->date_of_birth?->format('Y-m-d')) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">NID Number</label><input type="text" name="nid" class="form-control" value="{{ old('nid',$employee->nid) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Department</label><input type="text" name="department" class="form-control" value="{{ old('department',$employee->department) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact',$employee->emergency_contact) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    @foreach(['active','inactive','on_leave','terminated'] as $s)
                    <option value="{{ $s }}" {{ old('status',$employee->status)==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select></div>
            <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$employee->address) }}</textarea></div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Profile Photo</label>
                @if($employee->avatar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $employee->avatar) }}" alt="Avatar" class="img-thumbnail" style="max-height: 80px;">
                    </div>
                @endif
                <input type="file" name="avatar" class="form-control" accept="image/*">
                <div class="form-text">JPG or PNG — max 2MB</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">NID Photo</label>
                @if($employee->nid_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $employee->nid_photo) }}" alt="NID Photo" class="img-thumbnail" style="max-height: 80px;">
                    </div>
                @endif
                <input type="file" name="nid_photo" class="form-control" accept="image/*">
                <div class="form-text">Photo of NID card — max 4MB</div>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$employee->notes) }}</textarea></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Employee</button>
            <a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
