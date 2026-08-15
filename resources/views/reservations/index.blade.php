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
                                        <a href="{{ route('reservations.show', $r) }}" class="btn btn-sm btn-outline-info py-0 px-2" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
                                        <option value="{{ $t->id }}" data-table-number="{{ $t->table_number }}"
                                            data-status="{{ $t->status }}" data-capacity="{{ $t->capacity }}"
                                            data-location="{{ $t->location }}">
                                            {{ $t->table_number }}
                                            {{ $t->status !== 'available' ? '(' . ucfirst($t->status) . ')' : '' }}
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
    <style>
        .select2-container {
            font-family: 'Inter', sans-serif;
        }

        .select2-container .select2-selection--multiple,
        .select2-container--bootstrap-5 .select2-selection--multiple {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            min-height: 38px !important;
            padding: 3px 8px !important;
            transition: all 0.2s ease-in-out !important;
            background-color: #fff !important;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        .select2-container .select2-selection--multiple::-webkit-scrollbar,
        .select2-container--bootstrap-5 .select2-selection--multiple::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
            background: transparent !important;
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 4px !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            align-items: center;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
            background: transparent !important;
        }

        .select2-container.select2-container--focus .select2-selection--multiple,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.15) !important;
            background-color: #fff !important;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            padding: 2px 10px 2px 28px !important;
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            color: #334155 !important;
            position: relative !important;
            transition: none !important;
            display: inline-flex !important;
            align-items: center !important;
            margin: 0 !important;
            cursor: default !important;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice:hover,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice:hover {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice__remove,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            position: absolute !important;
            left: 6px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: transparent !important;
            border: none !important;
            background-color: transparent !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ef4444' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'%3E%3C/line%3E%3Cline x1='6' y1='6' x2='18' y2='18'%3E%3C/line%3E%3C/svg%3E") !important;
            background-size: 14px 14px !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            border-radius: 50% !important;
            width: 18px !important;
            height: 18px !important;
            transition: all 0.2s ease !important;
            padding: 0 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: #fee2e2 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23dc2626' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'%3E%3C/line%3E%3Cline x1='6' y1='6' x2='18' y2='18'%3E%3C/line%3E%3C/svg%3E") !important;
        }

        .select2-container .select2-selection--multiple .select2-search,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-search {
            display: inline-flex !important;
            margin: 0 !important;
            padding: 0 !important;
            flex-grow: 1;
        }

        .select2-container .select2-selection--multiple .select2-search .select2-search__field,
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-search .select2-search__field {
            margin: 0 !important;
            padding: 4px 0 !important;
            font-family: inherit !important;
            font-size: 0.82rem !important;
            color: #475569 !important;
        }

        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden;
            z-index: 1060 !important;
            background: #fff;
            animation: select2FadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes select2FadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(0.95);
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .select2-results__options {
            padding: 6px !important;
            max-height: 250px !important;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        .select2-results__options::-webkit-scrollbar {
            display: none !important;
            width: 0px !important;
            height: 0px !important;
            background: transparent !important;
        }

        .select2-results__option {
            border-radius: 8px !important;
            padding: 6px 10px !important;
            margin-bottom: 2px !important;
            transition: all 0.15s ease !important;
            font-size: 0.85rem !important;
            display: flex;
            align-items: center;
        }

        .select2-results__option--highlighted,
        .select2-results__option--highlighted[aria-selected],
        .select2-container--bootstrap-5 .select2-results__option--highlighted,
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected],
        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--highlighted {
            background-color: transparent !important;
            color: #2d3748 !important;
        }

        .select2-results__option[aria-selected=true],
        .select2-container--bootstrap-5 .select2-results__option[aria-selected=true],
        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option[aria-selected=true] {
            background-color: #f1f5f9 !important;
            color: #2d3748 !important;
            font-weight: 500;
        }

        .select2-results__option[aria-selected=true].select2-results__option--highlighted,
        .select2-container--bootstrap-5 .select2-results__option[aria-selected=true].select2-results__option--highlighted,
        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option[aria-selected=true].select2-results__option--highlighted {
            background-color: #f1f5f9 !important;
            color: #2d3748 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatTableOption(state) {
                if (!state.id) {
                    return state.text;
                }
                const element = $(state.element);
                let tableNumber = element.data('table-number') || state.text.split(' ')[0];
                tableNumber = tableNumber.replace(/^T0+/, 'T').replace(/^0+/, '');
                const status = element.data('status') || 'available';
                const capacity = element.data('capacity') || '';
                const location = element.data('location') || '';

                let statusColor = '#10b981'; // green for available
                let statusBgColor = '#ecfdf5';
                let statusLabel = 'Available';
                let statusIndicator =
                    `<span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: ${statusColor};"></span>`;

                if (status === 'occupied') {
                    statusColor = '#ef4444'; // red for occupied
                    statusBgColor = '#fef2f2';
                    statusLabel = 'Occupied';
                    statusIndicator =
                        `<span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: ${statusColor};"></span>`;
                } else if (status === 'reserved') {
                    statusColor = '#ef4444'; // red for reserved
                    statusBgColor = '#fef2f2'; // light red background
                    statusLabel = 'Reserved';
                    statusIndicator =
                        `<i class="bi bi-check-lg text-danger" style="font-size: 0.85rem; font-weight: 900; line-height: 1;"></i>`;
                } else if (status !== 'available') {
                    statusColor = '#64748b'; // grey for others
                    statusBgColor = '#f8fafc';
                    statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
                    statusIndicator =
                        `<span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: ${statusColor};"></span>`;
                }

                const locationBadge = location ?
                    `<span class="badge bg-light text-secondary ms-1" style="font-size: 0.65rem; font-weight: normal; border: 1px solid #e2e8f0;">${location}</span>` :
                    '';
                const capacityText = capacity ?
                    `<span class="text-muted small d-inline-flex align-items-center gap-1"><i class="bi bi-people" style="font-size: 0.75rem;"></i>${capacity}</span>` :
                    '';

                return $(`
                    <div class="d-flex align-items-center justify-content-between w-100 py-0.5" style="user-select: none;">
                        <div class="d-flex align-items-center gap-2">
                            ${statusIndicator}
                            <span class="fw-semibold text-dark">${tableNumber}</span>
                            <span class="badge" style="font-size: 0.65rem; padding: 2px 6px; background-color: ${statusBgColor}; color: ${statusColor}; font-weight: 600;">${statusLabel}</span>
                            ${locationBadge}
                        </div>
                        ${capacityText}
                    </div>
                `);
            }

            function formatTableSelection(state) {
                if (!state.id) {
                    return state.text;
                }
                const element = $(state.element);
                let tableNumber = element.data('table-number') || state.text.split(' ')[0];
                tableNumber = tableNumber.replace(/^T0+/, 'T').replace(/^0+/, '');
                const status = element.data('status') || 'available';

                if (status === 'reserved') {
                    return $(`
                        <span class="d-inline-flex align-items-center gap-1 text-danger">
                            <i class="bi bi-check-lg text-danger" style="font-size: 0.8rem; font-weight: bold;"></i>
                            <span>${tableNumber} (Reserved)</span>
                        </span>
                    `);
                }

                let dotColor = '#10b981'; // green for available
                if (status === 'occupied') {
                    dotColor = '#ef4444'; // red for occupied
                }

                return $(`
                    <span class="d-inline-flex align-items-center gap-1.5">
                        <span class="d-inline-block rounded-circle animate-pulse" style="width: 6px; height: 6px; background-color: ${dotColor};"></span>
                        <span>${tableNumber}</span>
                    </span>
                `);
            }

            $('#edit_table_ids').select2({
                placeholder: "Select table(s)",
                allowClear: true,
                dropdownParent: $('#editReservationModal'),
                templateResult: formatTableOption,
                templateSelection: formatTableSelection,
                escapeMarkup: function(m) {
                    return m;
                }
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
