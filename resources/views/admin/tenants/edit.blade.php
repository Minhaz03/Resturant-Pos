@extends('admin.layouts.app')
@section('header', 'Edit Tenant')

@section('content')
<div class="bg-gray-800 rounded shadow p-6 border border-gray-700 max-w-2xl">
    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Tenant Name</label>
            <input type="text" name="name" value="{{ $tenant->name }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">
                <input type="checkbox" name="is_active" value="1" class="mr-2 rounded bg-gray-700 border-gray-600 text-blue-500" {{ $tenant->is_active ? 'checked' : '' }}>
                Active
            </label>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        <a href="{{ route('admin.tenants.index') }}" class="ml-2 text-gray-400 hover:text-white">Cancel</a>
    </form>
</div>
@endsection
