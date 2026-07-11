@extends('admin.layouts.app')
@section('header', 'Manage Plans')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h3 class="text-xl font-bold text-white">Plans</h3>
    <a href="{{ route('admin.plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Plan</a>
</div>

@if(session('success'))
    <div class="bg-green-600 text-white p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-gray-800 rounded shadow overflow-hidden border border-gray-700">
    <table class="min-w-full divide-y divide-gray-700 text-sm">
        <thead class="bg-gray-700 text-gray-300 uppercase">
            <tr>
                <th class="px-6 py-3 text-left tracking-wider">ID</th>
                <th class="px-6 py-3 text-left tracking-wider">Name</th>
                <th class="px-6 py-3 text-left tracking-wider">Price</th>
                <th class="px-6 py-3 text-left tracking-wider">Cycle</th>
                <th class="px-6 py-3 text-left tracking-wider">Status</th>
                <th class="px-6 py-3 text-right tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700 text-gray-300">
            @foreach($plans as $plan)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $plan->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $plan->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($plan->price, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $plan->billing_cycle }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($plan->is_active)
                        <span class="text-green-400 font-semibold">Active</span>
                    @else
                        <span class="text-red-400 font-semibold">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-blue-400 hover:text-blue-300 mr-3">Edit</a>
                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $plans->links() }}
    </div>
</div>
@endsection
