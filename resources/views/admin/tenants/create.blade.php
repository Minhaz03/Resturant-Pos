@extends('admin.layouts.app')
@section('header', 'Create Tenant')

@section('content')
<div class="bg-gray-800 rounded shadow p-6 border border-gray-700 max-w-2xl">
    <form action="{{ route('admin.tenants.store') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Tenant Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Restaurant Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Restaurant Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Restaurant Address</label>
            <textarea name="address" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" rows="3">{{ old('address') }}</textarea>
        </div>
        
        <hr class="border-gray-600 my-6">
        
        <h4 class="text-white font-bold mb-4">Owner Details</h4>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Owner Name <span class="text-red-500">*</span></label>
            <input type="text" name="owner_name" value="{{ old('owner_name') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Owner Phone <span class="text-red-500">*</span></label>
            <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Owner Email <span class="text-red-500">*</span></label>
            <input type="email" name="owner_email" value="{{ old('owner_email') }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Owner Address <span class="text-red-500">*</span></label>
            <textarea name="owner_address" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" rows="2" required>{{ old('owner_address') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Owner Password <span class="text-red-500">*</span></label>
            <input type="password" name="owner_password" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Confirm Password <span class="text-red-500">*</span></label>
            <input type="password" name="owner_password_confirmation" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">
                <input type="checkbox" name="is_active" value="1" class="mr-2 rounded bg-gray-700 border-gray-600 text-blue-500" checked>
                Active
            </label>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
        <a href="{{ route('admin.tenants.index') }}" class="ml-2 text-gray-400 hover:text-white">Cancel</a>
    </form>
</div>
@endsection
