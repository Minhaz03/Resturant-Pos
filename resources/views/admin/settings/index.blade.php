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

        <div class="mt-8 mb-4 border-b border-gray-700 pb-2">
            <h4 class="text-lg font-bold text-white">SSLCommerz Configuration</h4>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Environment (API Domain)</label>
            <select name="sslcommerz_api_domain" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
                <option value="https://sandbox.sslcommerz.com" {{ (isset($settings['sslcommerz_api_domain']) && $settings['sslcommerz_api_domain'] == 'https://sandbox.sslcommerz.com') ? 'selected' : '' }}>Sandbox</option>
                <option value="https://securepay.sslcommerz.com" {{ (isset($settings['sslcommerz_api_domain']) && $settings['sslcommerz_api_domain'] == 'https://securepay.sslcommerz.com') ? 'selected' : '' }}>Live (Production)</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-300 text-sm font-bold mb-2">Store ID</label>
            <input type="text" name="sslcommerz_store_id" value="{{ $settings['sslcommerz_store_id'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-6">
            <label class="block text-gray-300 text-sm font-bold mb-2">Store Password</label>
            <input type="text" name="sslcommerz_store_password" value="{{ $settings['sslcommerz_store_password'] ?? '' }}" class="w-full bg-gray-700 border border-gray-600 rounded py-2 px-3 text-white focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Settings</button>
    </form>
</div>
@endsection
