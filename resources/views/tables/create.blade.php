@extends('layouts.app')
@section('title','Add Table')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tables.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add Table</h4>
</div>
<div class="row justify-content-center"><div class="col-lg-5"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('tables.store') }}">@csrf
        <div class="mb-3"><label class="form-label">Table Number <span class="text-danger">*</span></label>
            <input type="text" name="table_number" class="form-control" value="{{ old('table_number') }}" placeholder="e.g. T01" required></div>
        <div class="mb-3"><label class="form-label">Name / Label</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Window Table 1"></div>
        <div class="mb-3"><label class="form-label">Capacity <span class="text-danger">*</span></label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity',4) }}" min="1" max="50" required></div>
        <div class="mb-3"><label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Main Hall"></div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save</button>
            <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div></div></div></div>
@endsection
