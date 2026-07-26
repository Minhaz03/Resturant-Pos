@extends('layouts.app')
@section('title', 'Reservations')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--secondary)">Reservations</h4>
            <p class="text-muted small mb-0"><span class="badge bg-primary">{{ $upcomingCount }}</span> upcoming reservations
            </p>
        </div>
        @can('create reservations')
            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New
                Reservation</a>
        @endcan
    </div>
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}"
                    style="max-width:180px">
                <select name="status" class="form-select form-select-sm" style="max-width:160px">
                    <option value="">All Status</option>
                    @foreach (['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Res #</th>
                            <th>Guest</th>
                            <th>Phone</th>
                            <th>Date & Time</th>
                            <th>Table</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $r)
                            <tr>
                                <td class="fw-semibold" style="color:var(--secondary)">{{ $r->reservation_number }}</td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->customer_phone }}</td>
                                <td>{{ $r->reservation_date->format('d M Y') }}<br><small
                                        class="text-muted">{{ \Carbon\Carbon::parse($r->reservation_time)->format('h:i A') }}</small>
                                </td>
                                <td>{{ $r->tables->isNotEmpty() ? $r->tables->pluck('table_number')->join(', ') : '—' }}
                                </td>
                                <td><i class="bi bi-people me-1 text-muted"></i>{{ $r->guest_count }}</td>
                                <td>
                                    <span class="badge"
                                        style="background:{{ match ($r->status) {'pending' => '#fef3c7','confirmed' => '#dbeafe','seated' => '#dcfce7','completed' => '#d1fae5','cancelled' => '#fee2e2','no_show' => '#f3f4f6',default => '#f3f4f6'} }};color:{{ match ($r->status) {'pending' => '#92400e','confirmed' => '#1e40af','seated' => '#166534','completed' => '#065f46','cancelled' => '#991b1b','no_show' => '#374151',default => '#374151'} }}">
                                        {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @can('edit reservations')
                                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2"
                                                onclick="openEditModal(this)" data-id="{{ $r->id }}"
                                                data-name="{{ $r->customer_name }}" data-phone="{{ $r->customer_phone }}"
                                                data-email="{{ $r->customer_email }}"
                                                data-date="{{ $r->reservation_date->format('Y-m-d') }}"
                                                data-time="{{ substr($r->reservation_time, 0, 5) }}"
                                                data-guests="{{ $r->guest_count }}" data-deposit="{{ $r->deposit_amount }}"
                                                data-method="{{ $r->deposit_payment_method }}"
                                                data-status="{{ $r->status }}"
                                                data-tables="{{ $r->tables->pluck('id')->implode(',') }}"
                                                data-notes="{{ $r->notes }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endcan
                                        @can('delete reservations')
                                            <form method="POST" action="{{ route('reservations.destroy', $r) }}"
                                                data-confirm="Delete this reservation?" data-confirm-button="Yes, delete!">@csrf
                                                @method('DELETE')<button type="submit"
                                                    class="btn btn-sm btn-outline-danger py-0 px-2"><i
                                                        class="bi bi-trash"></i></button></form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($reservations->hasPages())
            <div class="card-footer">{{ $reservations->links() }}</div>
        @endif
    </div>

    <!-- Edit Reservation Modal -->
    <div class="modal fade" id="editReservationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editReservationForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Guest Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Guest Name</label>
                                <input type="text" name="customer_name" id="edit_customer_name" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="customer_phone" id="edit_customer_phone" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="customer_email" id="edit_customer_email" class="form-control">
                            </div>
                        </div>

                        <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Reservation Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="reservation_date" id="edit_reservation_date"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Time</label>
                                <input type="time" name="reservation_time" id="edit_reservation_time"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Guests</label>
                                <input type="number" name="guest_count" id="edit_guest_count" class="form-control"
                                    min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Deposit (৳)</label>
                                <div class="input-group">
                                    <input type="number" name="deposit_amount" id="edit_deposit_amount"
                                        class="form-control" min="0" step="0.01">
                                    <select name="deposit_payment_method" id="edit_deposit_payment_method"
                                        class="form-select">
                                        <option value="">Method...</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="mobile_banking">Mobile Banking</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" id="edit_status" class="form-select">
                                    @foreach (['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'] as $s)
                                        <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Table</label>
                                <select name="table_ids[]" id="edit_table_ids" class="form-select select2-multiple"
                                    multiple style="width: 100%;">
                                    @foreach ($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->table_number }}
                                            ({{ $t->capacity }}p)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit_table_ids').select2({
                theme: 'bootstrap-5',
                placeholder: "Select tables",
                dropdownParent: $('#editReservationModal')
            });
        });

        function openEditModal(btn) {
            const id = btn.dataset.id;
            document.getElementById('edit_customer_name').value = btn.dataset.name || '';
            document.getElementById('edit_customer_phone').value = btn.dataset.phone || '';
            document.getElementById('edit_customer_email').value = btn.dataset.email || '';
            document.getElementById('edit_reservation_date').value = btn.dataset.date || '';
            document.getElementById('edit_reservation_time').value = btn.dataset.time || '';
            document.getElementById('edit_guest_count').value = btn.dataset.guests || '';
            document.getElementById('edit_deposit_amount').value = btn.dataset.deposit || '';
            document.getElementById('edit_deposit_payment_method').value = btn.dataset.method || '';
            document.getElementById('edit_status').value = btn.dataset.status || 'pending';
            document.getElementById('edit_notes').value = btn.dataset.notes || '';

            // Update Select2 for tables
            let tableIds = btn.dataset.tables ? btn.dataset.tables.split(',') : [];
            $('#edit_table_ids').val(tableIds).trigger('change');

            document.getElementById('editReservationForm').action = `{{ url('reservations') }}/${id}`;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editReservationModal')).show();
        }
    </script>
@endpush
