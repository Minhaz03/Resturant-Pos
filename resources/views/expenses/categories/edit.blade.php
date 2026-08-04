@extends('layouts.app')

@section('title', 'Edit Expense Category')

@section('content')
<div class="mb-4">
    <a href="{{ route('expense-categories.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Back to Categories</a>
</div>

<div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Expense Category</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('expense-categories.update', $expenseCategory) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $expenseCategory->name) }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $expenseCategory->description) }}</textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
