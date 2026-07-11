@extends('admin.layouts.app')
@section('header', 'Edit Plan')

@section('content')
<div class="bg-gray-800 rounded shadow p-6 border border-gray-700 max-w-2xl">
    <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Plan Name</label>
            <input type="text" name="name" value="{{ $plan->name }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Description</label>
            <textarea name="description" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" rows="3">{{ $plan->description }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Price</label>
            <input type="number" step="0.01" name="price" value="{{ $plan->price }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Billing Cycle</label>
            <select name="billing_cycle" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
                <option value="monthly" {{ $plan->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $plan->billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">
                <input type="checkbox" name="is_active" value="1" class="mr-2 rounded bg-gray-700 border-gray-600 text-blue-500" {{ $plan->is_active ? 'checked' : '' }}>
                Active
            </label>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        <a href="{{ route('admin.plans.index') }}" class="ml-2 text-gray-400 hover:text-white">Cancel</a>
    </form>
</div>
@endsection
