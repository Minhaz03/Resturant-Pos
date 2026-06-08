@extends('layouts.app')
@section('title','Edit Table')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tables.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit Table: {{ $table->table_number }}</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-5"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('tables.update',$table) }}">@csrf @method('PUT')
        <div class="mb-3"><label class="form-label">Table Number</label>
            <input type="text" name="table_number" class="form-control" value="{{ old('table_number',$table->table_number) }}" required></div>
        <div class="mb-3"><label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$table->name) }}"></div>
        <div class="mb-3"><label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity',$table->capacity) }}" min="1"></div>
        <div class="mb-3"><label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location',$table->location) }}"></div>
        <div class="mb-3"><label class="form-label">Status</label>
            <select name="status" class="form-select">
                @foreach(['available','occupied','reserved','inactive'] as $s)
                <option value="{{ $s }}" {{ old('status',$table->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
