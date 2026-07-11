@extends('admin.layouts.app')
@section('header', 'Global Settings')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h3 class="text-xl font-bold text-white">SaaS Settings</h3>
</div>

@if(session('success'))
    <div class="bg-green-600 text-white p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-gray-800 rounded shadow p-6 border border-gray-700 max-w-2xl">
    <form action="{{ route('admin.settings.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Site Name</label>
            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Support Email</label>
            <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Footer Copyright Text</label>
            <input type="text" name="footer_text" value="{{ $settings['footer_text'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Settings</button>
    </form>
</div>
@endsection
