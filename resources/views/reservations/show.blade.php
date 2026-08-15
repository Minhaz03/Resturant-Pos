@extends('layouts.app')
@section('title', 'Reservation Details — ' . $reservation->reservation_number)

@push('styles')
    <style>
        .res-badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 0.8rem;
            border-radius: 50rem;
        }

        .info-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-uppercase: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        .detail-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .table-pill {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .table-pill .table-num {
            font-weight: 700;
            color: var(--secondary, #0A2647);
        }
    </style>
@endpush

@section('content')
    <!-- Top Action Bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $reservation->reservation_number }}</h4>
                    <span class="res-badge"
                        style="background:{{ match ($reservation->status) {'pending' => '#fef3c7','confirmed' => '#dbeafe','seated' => '#dcfce7','completed' => '#d1fae5','cancelled' => '#fee2e2','no_show' => '#f3f4f6',default => '#f3f4f6'} }};color:{{ match ($reservation->status) {'pending' => '#92400e','confirmed' => '#1e40af','seated' => '#166534','completed' => '#065f46','cancelled' => '#991b1b','no_show' => '#374151',default => '#374151'} }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>
                        {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                    </span>
                </div>
                <p class="text-muted small mb-0">Booked for {{ $reservation->reservation_date->format('l, d M Y') }} at {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i>Print</button>
            <a href="{{ route('pos.index', ['reservation_id' => $reservation->id]) }}" class="btn btn-success btn-sm"><i class="bi bi-receipt me-1"></i>Open on POS</a>
            <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Main Details Column -->
        <div class="col-lg-8">
            <!-- Card 1: Guest Information -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-person-bounding-box text-primary me-2"></i>Guest Profile</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-label">Customer Name</div>
                            <div class="info-value">{{ $reservation->customer_name }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">
                                <a href="tel:{{ $reservation->customer_phone }}" class="text-decoration-none text-dark">
                                    <i class="bi bi-telephone text-success me-1"></i>{{ $reservation->customer_phone }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $reservation->customer_email ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Reservation Booking Details -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-calendar-event text-info me-2"></i>Booking & Table Information</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="info-label">Date</div>
                            <div class="info-value"><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $reservation->reservation_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-label">Time</div>
                            <div class="info-value"><i class="bi bi-clock me-1 text-muted"></i>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-label">Guests</div>
                            <div class="info-value"><i class="bi bi-people me-1 text-muted"></i>{{ $reservation->guest_count }} {{ Str::plural('Person', $reservation->guest_count) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-label">Deposit Paid</div>
                            <div class="info-value text-success">
                                ৳{{ number_format($reservation->deposit_amount ?? 0, 2) }}
                                @if($reservation->deposit_payment_method)
                                    <small class="text-muted fw-normal">({{ ucfirst(str_replace('_', ' ', $reservation->deposit_payment_method)) }})</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Tables -->
                    <div class="info-label mb-2">Assigned Dining Tables</div>
                    @if($reservation->tables->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($reservation->tables as $t)
                                <div class="table-pill">
                                    <i class="bi bi-border-style text-primary"></i>
                                    <div>
                                        <span class="table-num">Table {{ $t->table_number }}</span>
                                        <small class="text-muted ms-1">({{ $t->capacity }} Seats &bull; {{ ucfirst($t->location) }})</small>
                                    </div>
                                    <span class="badge bg-secondary ms-1">{{ ucfirst($t->status) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning py-2 px-3 small mb-0 d-inline-block">
                            <i class="bi bi-exclamation-triangle me-1"></i>No tables assigned yet. Edit reservation to assign tables.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Special Notes -->
            <div class="card detail-card">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-chat-left-text text-warning me-2"></i>Special Requests & Notes</h6>
                </div>
                <div class="card-body pt-0">
                    @if($reservation->notes)
                        <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $reservation->notes }}</p>
                    @else
                        <p class="text-muted mb-0 small"><i class="bi bi-info-circle me-1"></i>No special notes or dietary requests recorded for this booking.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Status Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Status Change Card -->
            <div class="card detail-card mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-arrow-repeat text-primary me-2"></i>Update Reservation Status</h6>
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="customer_name" value="{{ $reservation->customer_name }}">
                        <input type="hidden" name="customer_phone" value="{{ $reservation->customer_phone }}">
                        <input type="hidden" name="customer_email" value="{{ $reservation->customer_email }}">
                        <input type="hidden" name="reservation_date" value="{{ $reservation->reservation_date->format('Y-m-d') }}">
                        <input type="hidden" name="reservation_time" value="{{ substr($reservation->reservation_time, 0, 5) }}">
                        <input type="hidden" name="guest_count" value="{{ $reservation->guest_count }}">

                        @foreach($reservation->tables as $t)
                            <input type="hidden" name="table_ids[]" value="{{ $t->id }}">
                        @endforeach

                        <div class="mb-3">
                            <label class="form-label info-label">Change Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="seated" {{ $reservation->status === 'seated' ? 'selected' : '' }}>Seated</option>
                                <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ $reservation->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="card detail-card">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i>Audit Information</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-3">
                        <div class="info-label">Created At</div>
                        <div class="info-value small fw-semibold text-muted">{{ $reservation->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    @if($reservation->confirmed_at)
                        <div class="mb-3">
                            <div class="info-label">Confirmed At</div>
                            <div class="info-value small fw-semibold text-muted">{{ $reservation->confirmed_at->format('d M Y, h:i A') }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="info-label">Created By</div>
                        <div class="info-value small fw-semibold text-muted">{{ $reservation->creator?->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
