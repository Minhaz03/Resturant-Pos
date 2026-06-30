@extends('layouts.app')
@section('title','Suppliers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Suppliers</h4><p class="text-muted small mb-0">Manage product suppliers</p></div>
    @can('create suppliers')<a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="max-width:250px">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>City</th><th>Payment Terms</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($suppliers as $s)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $s->name }}</div>
                    <div class="text-muted small">{{ $s->company ?? '' }}</div>
                </td>
                <td>{{ $s->contact_person ?? '—' }}</td>
                <td>{{ $s->phone ?? '—' }}</td>
                <td>{{ $s->email ?? '—' }}</td>
                <td>{{ $s->city ?? '—' }}</td>
                <td><span class="badge bg-light text-dark">{{ $s->payment_terms ?? '—' }}</span></td>
                <td><span class="badge {{ $s->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($s->status ?? 'active') }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-info py-0 px-2"
                            onclick="viewSupplier({{ json_encode($s) }})"
                            title="View Details"><i class="bi bi-eye"></i></button>
                        @can('edit suppliers')<a href="{{ route('suppliers.edit',$s) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete suppliers')<form method="POST" action="{{ route('suppliers.destroy',$s) }}" onsubmit="return confirm('Delete supplier?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No suppliers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($suppliers->hasPages())<div class="card-footer">{{ $suppliers->links() }}</div>@endif</div>

{{-- View Supplier Modal --}}
<div class="modal fade" id="viewSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background:var(--primary);color:#fff">
                <h5 class="modal-title fw-bold"><i class="bi bi-building me-2"></i><span id="vs-name">Supplier Details</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="text-muted small">Company</label><div class="fw-semibold" id="vs-company">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Contact Person</label><div class="fw-semibold" id="vs-contact">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Phone</label><div class="fw-semibold" id="vs-phone">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Email</label><div class="fw-semibold" id="vs-email">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">City</label><div class="fw-semibold" id="vs-city">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Payment Terms</label><div class="fw-semibold" id="vs-terms">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Tax Number</label><div class="fw-semibold" id="vs-tax">—</div></div>
                    <div class="col-md-6"><label class="text-muted small">Status</label><div id="vs-status">—</div></div>
                    <div class="col-12"><label class="text-muted small">Address</label><div class="fw-semibold" id="vs-address">—</div></div>
                    <div class="col-12"><label class="text-muted small">Notes</label><div class="text-muted" id="vs-notes">—</div></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewSupplier(s) {
    document.getElementById('vs-name').textContent    = s.name || '—';
    document.getElementById('vs-company').textContent = s.company || '—';
    document.getElementById('vs-contact').textContent = s.contact_person || '—';
    document.getElementById('vs-phone').textContent   = s.phone || '—';
    document.getElementById('vs-email').textContent   = s.email || '—';
    document.getElementById('vs-city').textContent    = s.city || '—';
    document.getElementById('vs-terms').textContent   = s.payment_terms || '—';
    document.getElementById('vs-tax').textContent     = s.tax_number || '—';
    document.getElementById('vs-address').textContent = s.address || '—';
    document.getElementById('vs-notes').textContent   = s.notes || '—';
    const active = s.status === 'active';
    document.getElementById('vs-status').innerHTML = `<span class="badge ${active?'bg-success':'bg-secondary'}">${s.status||'active'}</span>`;
    new bootstrap.Modal(document.getElementById('viewSupplierModal')).show();
}
</script>
@endpush
@endsection
