@extends('layouts.app')
@section('title','Edit Employee')

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
    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $employee->name }}</h4>
    <span class="badge bg-light text-dark ms-auto">{{ $employee->employee_id }}</span>
</div>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('employees.update',$employee) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
        <input type="hidden" name="remove_nid_photo" id="removeNidPhotoFlag" value="0">
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
                <input type="file" id="employeeAvatar" name="avatar" accept="image/*">
                <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                    <i class="bi bi-info-circle me-1"></i>Upload to replace current photo. JPG or PNG — max 2MB
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">NID Photo</label>
                <input type="file" id="employeeNidPhoto" name="nid_photo" accept="image/*">
                <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                    <i class="bi bi-info-circle me-1"></i>Upload to replace current NID photo. Max 4MB
                </div>
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
        $avatarUrl    = $employee->avatar    ? asset('storage/' . $employee->avatar)    : null;
        $nidPhotoUrl  = $employee->nid_photo ? asset('storage/' . $employee->nid_photo) : null;
    @endphp

    function makeFilePondConfig(existingUrl, removeFieldId, labelText, maxSize) {
        const cfg = {
            allowMultiple: false,
            maxFileSize: maxSize,
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            imagePreviewHeight: 150,
            server: null,
            instantUpload: false,
            storeAsFile: true,
            labelIdle: labelText,
            onremovefile: function() {
                document.getElementById(removeFieldId).value = '1';
            },
            onaddfile: function() {
                document.getElementById(removeFieldId).value = '0';
            },
        };
        if (existingUrl) {
            cfg.files = [{
                source: existingUrl,
                options: {
                    type: 'local',
                    file: { name: 'current-image.jpg', size: 0, type: 'image/jpeg' },
                    metadata: { poster: existingUrl },
                },
            }];
            cfg.server = {
                load: (source, load, error, progress, abort, headers) => {
                    fetch(source)
                        .then(res => res.blob())
                        .then(blob => load(blob))
                        .catch(e => error(e));
                    return { abort: () => abort() };
                },
            };
        }
        return cfg;
    }

    FilePond.create(
        document.querySelector('#employeeAvatar'),
        makeFilePondConfig(
            @json($avatarUrl),
            'removeAvatarFlag',
            '<i class="bi bi-person-bounding-box" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Profile Photo</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
            '2MB'
        )
    );

    FilePond.create(
        document.querySelector('#employeeNidPhoto'),
        makeFilePondConfig(
            @json($nidPhotoUrl),
            'removeNidPhotoFlag',
            '<i class="bi bi-card-image" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">NID Photo</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
            '4MB'
        )
    );
</script>
@endpush
