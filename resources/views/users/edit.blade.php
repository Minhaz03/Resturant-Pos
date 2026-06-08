@extends('layouts.app')
@section('title','Edit User')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $user->name }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('users.update',$user) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)<option value="{{ $role->name }}" {{ ($user->roles->first()?->name ?? '')==$role->name?'selected':'' }}>{{ ucfirst($role->name) }}</option>@endforeach
                </select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">New Password <small class="text-muted">(leave empty to keep current)</small></label><input type="password" name="password" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status',$user->status)=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status',$user->status)=='inactive'?'selected':'' }}>Inactive</option>
                </select></div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Avatar</label>
                @if($user->avatar)<img src="{{ asset('storage/'.$user->avatar) }}" class="d-block mb-1 rounded-circle" width="40" height="40" style="object-fit:cover">@endif
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
