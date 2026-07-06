@extends('layouts.app')
@section('title', 'Add Employee')

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
        <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add Employee</h4>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">@csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">Full Name <span
                                        class="text-danger">*</span></label><input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email"
                                    name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Phone <span
                                        class="text-danger">*</span></label><input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Role <span
                                        class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Select Role</option>
                                    @foreach (['manager', 'cashier', 'waiter', 'kitchen_staff', 'delivery_staff'] as $r)
                                        <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $r)) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Salary (৳) <span
                                        class="text-danger">*</span></label><input type="number" name="salary"
                                    class="form-control" value="{{ old('salary') }}" step="0.01" required></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Hire Date <span
                                        class="text-danger">*</span></label><input type="date" name="hire_date"
                                    class="form-control" value="{{ old('hire_date', today()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Date of Birth</label><input
                                    type="date" name="date_of_birth" class="form-control"
                                    value="{{ old('date_of_birth') }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">NID Number</label>
                                <input type="text" name="nid" class="form-control @error('nid') is-invalid @enderror"
                                    value="{{ old('nid') }}" maxlength="20" placeholder="Max 20 characters">
                                @error('nid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">National ID Number</div>
                            </div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Department</label><input
                                    type="text" name="department" class="form-control" value="{{ old('department') }}">
                            </div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Emergency Contact</label><input
                                    type="text" name="emergency_contact" class="form-control"
                                    value="{{ old('emergency_contact') }}" placeholder="Name - Phone"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>InActive
                                    </option>
                                    <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave
                                    </option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label fw-semibold">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Profile Photo</label>
                                <input type="file" id="employeeAvatar" name="avatar" accept="image/*">
                                <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                                    <i class="bi bi-info-circle me-1"></i>JPG or PNG — max 2MB
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NID Photo</label>
                                <input type="file" id="employeeNidPhoto" name="nid_photo" accept="image/*">
                                <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                                    <i class="bi bi-info-circle me-1"></i>Photo of NID card — max 4MB
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add
                                Employee</button>
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

    const commonConfig = {
        allowMultiple: false,
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        server: null,
        instantUpload: false,
        storeAsFile: true,
    };

    FilePond.create(document.querySelector('#employeeAvatar'), {
        ...commonConfig,
        maxFileSize: '2MB',
        imagePreviewHeight: 150,
        labelIdle: '<i class="bi bi-person-bounding-box" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Profile Photo</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
    });

    FilePond.create(document.querySelector('#employeeNidPhoto'), {
        ...commonConfig,
        maxFileSize: '4MB',
        imagePreviewHeight: 150,
        labelIdle: '<i class="bi bi-card-image" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">NID Photo</span><br><span style="color:#6b7280;font-size:0.82rem">or <span class="filepond--label-action">Browse</span></span>',
    });
</script>
@endpush
