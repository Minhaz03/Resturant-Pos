@extends('layouts.app')
@section('title', 'Create Role — Access Control')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Create New Role</h4>
        <p class="text-muted mb-0 small">Enter role details and select access control permissions.</p>
    </div>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Roles
    </a>
</div>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-bold">Role Title <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           placeholder="e.g. Order Specialist, Shift Manager" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text small">Role name will be converted to lower-case identifier automatically.</div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAllGlobalBtn">
                        <i class="bi bi-check-all me-1"></i>Select All Permissions
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllGlobalBtn">
                        <i class="bi bi-x-circle me-1"></i>Deselect All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3" style="color:var(--secondary)"><i class="bi bi-grid-3x3-gap me-2"></i>Configure Module Permissions</h5>

    <div class="row g-4">
        @foreach($permissionGroups as $groupName => $groupPermissions)
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 perm-group-card" style="border-radius: 12px;">
                <div class="card-header bg-light py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="fw-bold mb-0 text-dark small">{{ $groupName }}</h6>
                    <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 toggle-group-btn" style="font-size: 0.78rem;">
                        Select Group
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-2">
                        @foreach($groupPermissions as $permission)
                        <div class="form-check form-switch">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" 
                                   value="{{ $permission }}" id="perm_{{ Str::slug($permission) }}"
                                   {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>
                            <label class="form-check-label small font-mono" for="perm_{{ Str::slug($permission) }}">
                                {{ $permission }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 text-end">
        <a href="{{ route('roles.index') }}" class="btn btn-light border px-4 me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-4 fw-bold">
            <i class="bi bi-check-lg me-1.5"></i>Create Role
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Group
    document.querySelectorAll('.toggle-group-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.perm-group-card');
            const checkboxes = card.querySelectorAll('.perm-checkbox');
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            checkboxes.forEach(c => c.checked = !allChecked);
            this.textContent = allChecked ? 'Select Group' : 'Deselect Group';
        });
    });

    // Select All Global
    document.getElementById('selectAllGlobalBtn').addEventListener('click', function() {
        document.querySelectorAll('.perm-checkbox').forEach(c => c.checked = true);
        document.querySelectorAll('.toggle-group-btn').forEach(b => b.textContent = 'Deselect Group');
    });

    // Deselect All Global
    document.getElementById('deselectAllGlobalBtn').addEventListener('click', function() {
        document.querySelectorAll('.perm-checkbox').forEach(c => c.checked = false);
        document.querySelectorAll('.toggle-group-btn').forEach(b => b.textContent = 'Select Group');
    });
});
</script>
@endpush
@endsection
