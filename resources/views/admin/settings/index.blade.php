@extends('admin.layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
    <div>
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2 tracking-tight">
            <i class="bi bi-gear text-blue-500"></i> Global System Settings
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">Manage global platform credentials, branding, and SSLCommerz payment gateway parameters.</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-semibold flex items-center gap-3 shadow-lg">
            <i class="bi bi-check-circle-fill text-emerald-400 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Section 1: General Platform Branding -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
            <div class="p-5 border-b border-slate-800 bg-slate-900/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                    <i class="bi bi-globe text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Platform Identity & General Metadata</h3>
                    <p class="text-xs text-slate-400">Configure global website branding and system contact details.</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label for="site_name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        <i class="bi bi-building text-blue-400 me-1"></i> Platform Site Name
                    </label>
                    <input 
                        type="text" 
                        id="site_name" 
                        name="site_name" 
                        value="{{ $settings['site_name'] ?? '' }}" 
                        placeholder="e.g. RestoPOS Enterprise SaaS" 
                        class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="support_email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            <i class="bi bi-envelope-at text-blue-400 me-1"></i> Platform Support Email
                        </label>
                        <input 
                            type="email" 
                            id="support_email" 
                            name="support_email" 
                            value="{{ $settings['support_email'] ?? '' }}" 
                            placeholder="support@restopos.com" 
                            class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all"
                        >
                    </div>

                    <div>
                        <label for="footer_text" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            <i class="bi bi-c-circle text-blue-400 me-1"></i> Footer Copyright Notice
                        </label>
                        <input 
                            type="text" 
                            id="footer_text" 
                            name="footer_text" 
                            value="{{ $settings['footer_text'] ?? '' }}" 
                            placeholder="&copy; 2026 RestoPOS Inc. All rights reserved." 
                            class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: SSLCommerz Gateway Integration -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
            <div class="p-5 border-b border-slate-800 bg-slate-900/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                        <i class="bi bi-credit-card text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">SSLCommerz Payment Gateway Config</h3>
                        <p class="text-xs text-slate-400">Credentials for processing online tenant subscription payments.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-300 text-xs font-semibold rounded-lg border border-emerald-500/20">
                    Payment Gateway
                </span>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label for="sslcommerz_api_domain" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        <i class="bi bi-cloud-check text-emerald-400 me-1"></i> Gateway Environment
                    </label>
                    <select 
                        name="sslcommerz_api_domain" 
                        id="sslcommerz_api_domain" 
                        class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all"
                    >
                        <option value="https://sandbox.sslcommerz.com" {{ (isset($settings['sslcommerz_api_domain']) && $settings['sslcommerz_api_domain'] == 'https://sandbox.sslcommerz.com') ? 'selected' : '' }}>
                            Sandbox Testing Environment (https://sandbox.sslcommerz.com)
                        </option>
                        <option value="https://securepay.sslcommerz.com" {{ (isset($settings['sslcommerz_api_domain']) && $settings['sslcommerz_api_domain'] == 'https://securepay.sslcommerz.com') ? 'selected' : '' }}>
                            Live Production Environment (https://securepay.sslcommerz.com)
                        </option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="sslcommerz_store_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            <i class="bi bi-person-badge text-emerald-400 me-1"></i> Store ID
                        </label>
                        <input 
                            type="text" 
                            id="sslcommerz_store_id" 
                            name="sslcommerz_store_id" 
                            value="{{ $settings['sslcommerz_store_id'] ?? '' }}" 
                            placeholder="e.g. resto65abc123" 
                            class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all"
                        >
                    </div>

                    <div>
                        <label for="sslcommerz_store_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            <i class="bi bi-key text-emerald-400 me-1"></i> Store Password / Secret
                        </label>
                        <input 
                            type="password" 
                            id="sslcommerz_store_password" 
                            name="sslcommerz_store_password" 
                            value="{{ $settings['sslcommerz_store_password'] ?? '' }}" 
                            placeholder="••••••••••••" 
                            class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 transition-all"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submit Bar -->
        <div class="flex justify-end pt-2">
            <button 
                type="submit" 
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2"
            >
                <i class="bi bi-check-lg text-lg"></i>
                <span>Save Platform Settings</span>
            </button>
        </div>

    </form>

</div>
@endsection

