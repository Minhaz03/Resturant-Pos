@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">My Profile</h4>
        <p class="text-muted small mb-0">Manage your account information and security</p>
    </div>
</div>

@if(session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i> Profile updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Profile Info --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-person-circle me-2"></i>Account Information</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <div class="form-text text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Email not verified.</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->getRoleNames()->first() ?? 'No Role') }}" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold"><i class="bi bi-shield-lock me-2"></i>Change Password</div>
            <div class="card-body">
                @if(session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Password changed successfully. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password','updatePassword') is-invalid @enderror">
                        @error('current_password','updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password','updatePassword') is-invalid @enderror">
                        @error('password','updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-key me-1"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Account Meta --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white fs-3"
                    style="width:70px;height:70px;background:var(--primary)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $user->name }}</div>
                    <div class="text-muted small">{{ $user->email }}</div>
                    <div class="text-muted small mt-1">
                        <i class="bi bi-calendar3 me-1"></i>Member since {{ $user->created_at->format('M d, Y') }}
                    </div>
                    <div class="mt-2">
                        @foreach($user->getRoleNames() as $role)
                            <span class="badge text-white me-1" style="background:var(--primary)">{{ ucfirst($role) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
