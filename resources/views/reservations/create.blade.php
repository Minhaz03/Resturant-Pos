@extends('layouts.app')
@section('title','New Reservation')
@section('content')
<style>
.reservation-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
}
.reservation-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 2rem;
    color: white;
}
.section-title {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.input-group-text {
    background: #f8fafc;
    border-right: none;
    color: #64748b;
}
.form-control, .form-select {
    border-left: none;
}
.form-control:focus, .form-select:focus {
    box-shadow: none;
    border-color: #dee2e6;
}
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    border-radius: 0.375rem;
}
.input-group:focus-within .input-group-text, .input-group:focus-within .form-control, .input-group:focus-within .form-select {
    border-color: #86b7fe;
}
.btn-reserve {
    background: #3b82f6;
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s;
}
.btn-reserve:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.2);
}
</style>

<div class="row justify-content-center mb-5">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('reservations.index') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0 text-dark">Book a Table</h4>
        </div>
        
        <div class="card reservation-card">
            <div class="reservation-header">
                <h5 class="mb-1 fw-bold"><i class="bi bi-calendar-plus me-2"></i>New Reservation</h5>
                <p class="mb-0 text-light opacity-75 small">Fill in the details below to secure a table for your guests.</p>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    
                    <!-- Guest Details -->
                    <div class="section-title"><i class="bi bi-person-badge"></i> Guest Details</div>
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-medium small">Guest Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="customer_name" class="form-control" placeholder="e.g. John Doe" value="{{ old('customer_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium small">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="customer_phone" class="form-control" placeholder="e.g. 01700000000" value="{{ old('customer_phone') }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-medium small">Email Address <span class="text-muted fw-normal">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="customer_email" class="form-control" placeholder="e.g. john@example.com" value="{{ old('customer_email') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reservation Details -->
                    <div class="section-title"><i class="bi bi-clock-history"></i> Booking Details</div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-medium small">Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="reservation_date" class="form-control" value="{{ old('reservation_date',today()->toDateString()) }}" required min="{{ today()->toDateString() }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium small">Time <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                <input type="time" name="reservation_time" class="form-control" value="{{ old('reservation_time','19:00') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium small">Guests <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <input type="number" name="guest_count" class="form-control" value="{{ old('guest_count',2) }}" min="1" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium small">Table Assignment</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 ps-0 pe-2"><i class="bi bi-grid-3x3-gap text-muted"></i></span>
                                <div class="flex-grow-1" style="min-width:0;">
                                    <select name="table_ids[]" class="form-select select2-multiple" multiple>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->table_number }} - {{ $t->name }} ({{ $t->capacity }}p)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium small">Advance Deposit</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="deposit_amount" class="form-control border-end-0" placeholder="0.00" value="{{ old('deposit_amount', 0) }}" min="0" step="0.01" style="max-width: 100px;">
                                <select name="deposit_payment_method" class="form-select border-start-0 ps-0 bg-transparent" style="color: #475569;">
                                    <option value="">Method...</option>
                                    <option value="cash" {{ old('deposit_payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('deposit_payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="mobile_banking" {{ old('deposit_payment_method') == 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-medium small">Special Notes or Requests</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any dietary restrictions, special occasions, or preferences..." style="border-radius: 8px;">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                        <a href="{{ route('reservations.index') }}" class="text-muted text-decoration-none fw-medium me-2">Cancel</a>
                        <button type="submit" class="btn btn-reserve text-white">Confirm Booking <i class="bi bi-check2-circle ms-1"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            placeholder: "Select tables"
        });
    });
</script>
@endpush
