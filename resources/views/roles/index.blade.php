@extends('layouts.app')
@section('title', 'Roles & Permissions Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Roles & Permissions Management</h4>
        <p class="text-muted mb-0 small">Define custom user roles and configure precise access control permissions.</p>
    </div>
    @can('create roles')
    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
        <i class="bi bi-shield-plus me-1.5"></i>Create New Role
    </a>
    @endcan
</div>

<div class="row g-4">
    @forelse($roles as $role)
    <div class="col-xl-4 col-md-6">
        <div class="card h-100 shadow-sm border-0" style="border-radius: 14px;">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                         style="width: 38px; height: 38px; background: {{ match($role->name) { 'super_admin' => '#fee2e2', 'owner' => '#fef3c7', 'manager' => '#e0e7ff', 'cashier' => '#dcfce7', default => '#f3f4f6' } }}; color: {{ match($role->name) { 'super_admin' => '#991b1b', 'owner' => '#92400e', 'manager' => '#3730a3', 'cashier' => '#166534', default => '#374151' } }};">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-capitalize" style="color:var(--secondary)">
                            {{ str_replace('_', ' ', $role->name) }}
                        </h6>
                        <span class="badge bg-light text-muted border font-mono small" style="font-size: 0.68rem;">{{ $role->guard_name }} guard</span>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    @can('edit roles')
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5" title="Edit Role Permissions">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    @endcan
                    @can('delete roles')
                    @if(!in_array($role->name, ['super_admin', 'owner']))
                    <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline" data-confirm="Are you sure you want to delete this role?" data-confirm-button="Yes, Delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2.5" title="Delete Role">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2.5 bg-light rounded-3">
                    <span class="small text-muted fw-medium"><i class="bi bi-people me-1"></i>Assigned Users</span>
                    <span class="badge bg-secondary px-2.5 py-1.5" style="font-size: 0.78rem;">{{ $role->users_count }} Users</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted fw-semibold">Granted Permissions ({{ $role->permissions_count }})</span>
                </div>

                <div class="d-flex flex-wrap gap-1" style="max-height: 120px; overflow-y: auto;">
                    @forelse($role->permissions->take(12) as $perm)
                        <span class="badge bg-white text-dark border px-2 py-1" style="font-size: 0.7rem; font-weight: 500;">
                            {{ $perm->name }}
                        </span>
                    @empty
                        <span class="text-muted small fst-italic">No specific permissions granted</span>
                    @endforelse
                    @if($role->permissions_count > 12)
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small" style="font-size: 0.7rem;">
                            +{{ $role->permissions_count - 12 }} more
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
            <i class="bi bi-shield-x text-muted display-4 d-block mb-3 opacity-50"></i>
            <h5>No Roles Found</h5>
            <p class="text-muted small mb-3">Create your first role to assign permissions to your staff.</p>
            @can('create roles')
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">Create Role</a>
            @endcan
        </div>
    </div>
    @endforelse
</div>
@endsection
