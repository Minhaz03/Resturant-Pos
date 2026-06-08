@extends('layouts.app')
@section('title','New Reservation')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">New Reservation</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('reservations.store') }}">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Guest Name <span class="text-danger">*</span></label><input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone <span class="text-danger">*</span></label><input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}"></div>
            <div class="col-md-6"><label class="form-label">Table</label>
                <select name="table_id" class="form-select"><option value="">Auto Assign</option>
                @foreach($tables as $t)<option value="{{ $t->id }}">{{ $t->table_number }} - {{ $t->name }} ({{ $t->capacity }}p)</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" name="reservation_date" class="form-control" value="{{ old('reservation_date',today()->toDateString()) }}" required min="{{ today()->toDateString() }}"></div>
            <div class="col-md-4"><label class="form-label">Time <span class="text-danger">*</span></label><input type="time" name="reservation_time" class="form-control" value="{{ old('reservation_time','19:00') }}" required></div>
            <div class="col-md-4"><label class="form-label">Guests <span class="text-danger">*</span></label><input type="number" name="guest_count" class="form-control" value="{{ old('guest_count',2) }}" min="1" required></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Create Reservation</button>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
