@extends('layouts.app')
@section('title','Edit Reservation')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $reservation->reservation_number }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('reservations.update',$reservation) }}">@csrf @method('PUT')
        <!-- Guest Details -->
        <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Guest Details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><label class="form-label">Guest Name</label><input type="text" name="customer_name" class="form-control" value="{{ old('customer_name',$reservation->customer_name) }}" required></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone',$reservation->customer_phone) }}" required></div>
            <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="customer_email" class="form-control" value="{{ old('customer_email',$reservation->customer_email) }}"></div>
        </div>

        <!-- Reservation Details -->
        <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">Reservation Details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3"><label class="form-label">Date</label><input type="date" name="reservation_date" class="form-control" value="{{ old('reservation_date',$reservation->reservation_date->format('Y-m-d')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Time</label><input type="time" name="reservation_time" class="form-control" value="{{ old('reservation_time',substr($reservation->reservation_time,0,5)) }}" required></div>
            <div class="col-md-2"><label class="form-label">Guests</label><input type="number" name="guest_count" class="form-control" value="{{ old('guest_count',$reservation->guest_count) }}" min="1" required></div>
            <div class="col-md-5">
                <label class="form-label">Deposit (৳)</label>
                <div class="input-group">
                    <input type="number" name="deposit_amount" class="form-control" value="{{ old('deposit_amount',$reservation->deposit_amount ?? 0) }}" min="0" step="0.01">
                    <select name="deposit_payment_method" class="form-select">
                        <option value="">Method...</option>
                        <option value="cash" {{ old('deposit_payment_method', $reservation->deposit_payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('deposit_payment_method', $reservation->deposit_payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="mobile_banking" {{ old('deposit_payment_method', $reservation->deposit_payment_method) == 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                @foreach(['pending','confirmed','seated','completed','cancelled','no_show'] as $s)
                <option value="{{ $s }}" {{ old('status',$reservation->status)==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach</select>
            </div>
            <div class="col-12"><label class="form-label">Table</label>
                <select name="table_ids[]" class="form-select select2-multiple" multiple>
                @foreach($tables as $t)<option value="{{ $t->id }}" {{ in_array($t->id, old('table_ids', $reservation->tables->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $t->table_number }} ({{ $t->capacity }}p)</option>@endforeach</select>
            </div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$reservation->notes) }}</textarea></div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
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
