@extends('layouts.app')
@section('title','Add User')

@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<style>
    .filepond--root { font-family: 'Inter', sans-serif; }
    .filepond--panel-root { background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 10px; }
    .filepond--drop-label { color: #64748b; font-size: 0.875rem; }
    .filepond--label-action { color: var(--primary, #8B0000); font-weight: 600; text-decoration: underline; }
    .filepond--item-panel { background: var(--secondary, #0A2647); }
    .filepond--file-action-button { background: rgba(139,0,0,0.85); }
    [data-filepond-item-state='processing-complete'] .filepond--item-panel { background: #1a7f5a; }
    [data-filepond-item-state='error'] .filepond--item-panel,
    [data-filepond-item-state='aborted'] .filepond--item-panel { background: #dc2626; }
</style>
@endpush
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add User</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)<option value="{{ $role->name }}" {{ old('role')==$role->name?'selected':'' }}>{{ ucfirst($role->name) }}</option>@endforeach
                </select>@error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label><input type="password" name="password_confirmation" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Avatar</label><input type="file" id="userAvatar" name="avatar" accept="image/*"></div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType
    );

    FilePond.create(document.querySelector('#userAvatar'), {
        allowMultiple: false,
        maxFileSize: '2MB',
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        imagePreviewHeight: 140,
        server: null,
        instantUpload: false,
        storeAsFile: true,
        labelIdle: '<i class="bi bi-person-circle" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Upload Avatar</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
    });
</script>
@endpush
