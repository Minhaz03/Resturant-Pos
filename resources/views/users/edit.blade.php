@extends('layouts.app')
@section('title','Edit User')

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
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $user->name }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('users.update',$user) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
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
                <input type="file" id="userAvatar" name="avatar" accept="image/*">
                <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                    <i class="bi bi-info-circle me-1"></i>Upload to replace current avatar. Max 2MB.
                </div>
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

    @php
        $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
    @endphp

    const pondConfig = {
        allowMultiple: false,
        maxFileSize: '2MB',
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        imagePreviewHeight: 140,
        server: null,
        instantUpload: false,
        storeAsFile: true,
        labelIdle: '<i class="bi bi-person-circle" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Upload Avatar</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
        onremovefile: function() {
            document.getElementById('removeAvatarFlag').value = '1';
        },
        onaddfile: function() {
            document.getElementById('removeAvatarFlag').value = '0';
        },
    };

    @if($avatarUrl)
    pondConfig.files = [{
        source: '{{ $avatarUrl }}',
        options: {
            type: 'local',
            file: { name: 'current-avatar.jpg', size: 0, type: 'image/jpeg' },
            metadata: { poster: '{{ $avatarUrl }}' },
        },
    }];
    pondConfig.server = {
        load: (source, load, error, progress, abort, headers) => {
            fetch(source)
                .then(res => res.blob())
                .then(blob => load(blob))
                .catch(e => error(e));
            return { abort: () => abort() };
        },
    };
    @endif

    FilePond.create(document.querySelector('#userAvatar'), pondConfig);
</script>
@endpush
