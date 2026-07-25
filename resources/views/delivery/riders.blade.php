@extends('layouts.app')
@section('title', 'Manage Riders')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--secondary)">Delivery Riders</h4>
            <p class="text-muted small mb-0">Manage delivery personnel</p>
        </div>
        <div>
            <a href="{{ route('delivery.index') }}" class="btn btn-outline-secondary me-2">Back to Orders</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRiderModal">
                <i class="bi bi-plus-lg me-1"></i>Add Rider
            </button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Search by name, email, phone..." value="{{ request('search') }}" style="max-width:300px">
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                <a href="{{ route('delivery.riders') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Rider</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riders as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($u->avatar)
                                            <img src="{{ asset('storage/' . $u->avatar) }}" class="rounded-circle"
                                                width="35" height="35" style="object-fit:cover">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:35px;height:35px;background:var(--secondary);color:#fff;font-weight:700">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $u->name }}</div>
                                            <div class="text-muted small">#{{ $u->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->phone ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ ($u->status ?? 'active') == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($u->status ?? 'active') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal"
                                            data-bs-target="#editRiderModal{{ $u->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('delivery.riders.destroy', $u) }}"
                                                data-confirm="Remove this rider?" data-confirm-button="Yes, remove!">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editRiderModal{{ $u->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('delivery.riders.update', $u) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Rider</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $u->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $u->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ $u->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <select name="role" class="form-select" required>
                                                        @foreach($roles as $r)
                                                            <option value="{{ $r->name }}"
                                                                {{ $u->hasRole($r->name) ? 'selected' : '' }}>
                                                                {{ ucfirst($r->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="active" {{ $u->status == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $u->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Photo</label>
                                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                                    @if($u->avatar)
                                                        <div class="mt-2 text-muted small">Current photo:
                                                            <img src="{{ asset('storage/' . $u->avatar) }}" height="40"
                                                                class="rounded ms-2 rider-img-preview"
                                                                data-img-src="{{ asset('storage/' . $u->avatar) }}"
                                                                data-img-title="Profile Photo — {{ $u->name }}"
                                                                style="cursor:zoom-in" title="Click to preview">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Upload NID Photo</label>
                                                    <input type="file" name="nid_photo" class="form-control" accept="image/*">
                                                    @if($u->nid_photo)
                                                        <div class="mt-2 text-muted small">Current NID:
                                                            <img src="{{ asset('storage/' . $u->nid_photo) }}" height="40"
                                                                class="rounded ms-2 rider-img-preview"
                                                                data-img-src="{{ asset('storage/' . $u->nid_photo) }}"
                                                                data-img-title="NID Photo — {{ $u->name }}"
                                                                style="cursor:zoom-in" title="Click to preview">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No riders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($riders->hasPages())
            <div class="card-footer">{{ $riders->links() }}</div>
        @endif
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addRiderModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('delivery.riders.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Rider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}"
                                        {{ $r->name == 'delivery_staff' ? 'selected' : '' }}>
                                        {{ ucfirst($r->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Photo</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload NID Photo</label>
                            <input type="file" name="nid_photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Rider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div class="modal fade" id="imgLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0 pb-1">
                    <h6 class="modal-title text-white" id="imgLightboxTitle"></h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-2">
                    <img id="imgLightboxImg" src="" alt="Preview"
                        style="max-width:100%;max-height:80vh;border-radius:6px;object-fit:contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lightboxModal = new bootstrap.Modal(document.getElementById('imgLightboxModal'));
        const lightboxImg   = document.getElementById('imgLightboxImg');
        const lightboxTitle = document.getElementById('imgLightboxTitle');

        document.querySelectorAll('.rider-img-preview').forEach(function (img) {
            img.addEventListener('click', function (e) {
                e.stopPropagation();
                lightboxImg.src = this.dataset.imgSrc;
                lightboxTitle.textContent = this.dataset.imgTitle || 'Image Preview';
                lightboxModal.show();
            });
        });
    });
</script>
@endpush
