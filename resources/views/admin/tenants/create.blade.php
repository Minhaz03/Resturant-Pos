@extends('admin.layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
    <div>
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="bi bi-plus-circle-fill text-blue-500"></i> Provision New Restaurant Tenant
        </h2>
        <p class="text-xs text-gray-400 mt-0.5">Setup store profile and generate primary owner administration credentials.</p>
    </div>
    <a href="{{ route('admin.tenants.index') }}" class="text-sm bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white px-4 py-2 rounded-xl transition-all border border-gray-700 flex items-center gap-2 shadow-sm shrink-0">
        <i class="bi bi-arrow-left"></i> Back to Tenants
    </a>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Global Error Display -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-900/40 border border-red-500/40 text-red-200 text-sm flex items-start gap-3 shadow-lg">
            <i class="bi bi-exclamation-triangle-fill text-red-400 text-xl shrink-0 mt-0.5"></i>
            <div class="flex-1">
                <h5 class="font-bold text-red-100 mb-1">Please correct the following errors:</h5>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.tenants.store') }}" method="POST" id="tenantCreateForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Side: Main Form Fields (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section 1: Restaurant Store Details -->
                <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden">
                    <div class="p-5 border-b border-gray-700 bg-gray-800/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                                <i class="bi bi-shop text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">1. Restaurant Store Profile</h3>
                                <p class="text-xs text-gray-400">Basic metadata and contact info for the restaurant location.</p>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <label class="inline-flex items-center cursor-pointer bg-gray-900/60 px-3 py-1.5 rounded-xl border border-gray-700 hover:border-gray-600 transition-colors">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500 relative"></div>
                            <span class="ml-2.5 text-xs font-semibold text-gray-300 peer-checked:text-green-400">Active Tenant</span>
                        </label>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Tenant Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Tenant / Restaurant Name <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-building"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    placeholder="e.g. Sultan's Dine - Dhanmondi Branch" 
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                                >
                            </div>
                            @error('name')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email & Phone Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Restaurant Email <span class="text-gray-500 font-normal">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        placeholder="contact@restaurant.com" 
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                                    >
                                </div>
                                @error('email')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Restaurant Phone <span class="text-gray-500 font-normal">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}" 
                                        placeholder="+880 1700-000000" 
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                                    >
                                </div>
                                @error('phone')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Restaurant Address -->
                        <div>
                            <label for="address" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Restaurant Address <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3.5 pointer-events-none text-gray-400">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <textarea 
                                    id="address" 
                                    name="address" 
                                    rows="2" 
                                    placeholder="Full street address, city, and zip code..." 
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                                >{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Helper Tip Banner -->
                        <div class="p-3.5 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center gap-3 text-xs text-blue-300">
                            <i class="bi bi-info-circle-fill text-blue-400 text-base shrink-0"></i>
                            <span>If store email, phone, or address are omitted, owner details will be automatically inherited.</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Owner Credentials & Profile -->
                <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden">
                    <div class="p-5 border-b border-gray-700 bg-gray-800/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                                <i class="bi bi-person-badge text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">2. Primary Owner Credentials</h3>
                                <p class="text-xs text-gray-400">Super administrator account created specifically for this store.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-purple-500/10 text-purple-300 text-xs font-semibold rounded-lg border border-purple-500/20">
                            Super Owner Account
                        </span>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Owner Name & Phone Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="owner_name" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Owner Full Name <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="owner_name" 
                                        name="owner_name" 
                                        value="{{ old('owner_name') }}" 
                                        required 
                                        placeholder="e.g. Tanvir Ahmed" 
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                    >
                                </div>
                                @error('owner_name')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="owner_phone" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Owner Phone Number <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="owner_phone" 
                                        name="owner_phone" 
                                        value="{{ old('owner_phone') }}" 
                                        required 
                                        placeholder="+880 1800-000000" 
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                    >
                                </div>
                                @error('owner_phone')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Owner Email -->
                        <div>
                            <label for="owner_email" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Owner Email Address <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-envelope-at"></i>
                                </div>
                                <input 
                                    type="email" 
                                    id="owner_email" 
                                    name="owner_email" 
                                    value="{{ old('owner_email') }}" 
                                    required 
                                    placeholder="owner@restaurant.com" 
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                >
                            </div>
                            <span class="text-[11px] text-gray-400 mt-1 block">Used for primary store owner sign-in and system alerts.</span>
                            @error('owner_email')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Owner Address -->
                        <div>
                            <label for="owner_address" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Owner Residential / Contact Address <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3.5 pointer-events-none text-gray-400">
                                    <i class="bi bi-pin-map"></i>
                                </div>
                                <textarea 
                                    id="owner_address" 
                                    name="owner_address" 
                                    rows="2" 
                                    required 
                                    placeholder="Owner's residential or official address..." 
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                >{{ old('owner_address') }}</textarea>
                            </div>
                            @error('owner_address')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password & Password Confirm Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-gray-700/60">
                            <div>
                                <label for="owner_password" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Account Password <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-key"></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="owner_password" 
                                        name="owner_password" 
                                        required 
                                        placeholder="Min. 8 characters" 
                                        class="w-full pl-10 pr-10 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                    >
                                    <button type="button" class="toggle-password-btn absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-200" data-target="owner_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('owner_password')
                                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="owner_password_confirmation" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">
                                    Confirm Password <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-check2-circle"></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="owner_password_confirmation" 
                                        name="owner_password_confirmation" 
                                        required 
                                        placeholder="Repeat password" 
                                        class="w-full pl-10 pr-10 py-2.5 bg-gray-900/70 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors"
                                    >
                                    <button type="button" class="toggle-password-btn absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-200" data-target="owner_password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Right Side: Action & Overview Sidebar (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Action Card -->
                <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6 sticky top-6">
                    <h4 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-rocket-takeoff-fill text-blue-400"></i> Provisioning Summary
                    </h4>

                    <div class="space-y-4 mb-6 text-xs text-gray-300 border-b border-gray-700 pb-6">
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-900/50 border border-gray-700/60">
                            <i class="bi bi-check-circle-fill text-green-400 text-base shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-white block">Tenant Store Record</span>
                                <span class="text-gray-400">Database entry initialized with isolated settings.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-900/50 border border-gray-700/60">
                            <i class="bi bi-person-check-fill text-purple-400 text-base shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-white block">Owner Administrator</span>
                                <span class="text-gray-400">User created with owner role and tenant reference.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-900/50 border border-gray-700/60">
                            <i class="bi bi-shield-lock-fill text-blue-400 text-base shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-white block">Credentials Hashed</span>
                                <span class="text-gray-400">Password safely encrypted via bcrypt hashing algorithm.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="space-y-3">
                        <button 
                            type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition-all flex items-center justify-center gap-2 group"
                        >
                            <i class="bi bi-plus-lg group-hover:scale-110 transition-transform"></i>
                            <span>Create Tenant & Owner</span>
                        </button>

                        <a 
                            href="{{ route('admin.tenants.index') }}" 
                            class="w-full py-2.5 px-4 bg-gray-900/80 hover:bg-gray-700 text-gray-300 hover:text-white font-semibold text-sm rounded-xl border border-gray-700 transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>

                <!-- Guidance Box -->
                <div class="p-5 rounded-2xl bg-gray-800/60 border border-gray-700/60 text-xs text-gray-400 space-y-2">
                    <h5 class="font-bold text-gray-200 flex items-center gap-1.5">
                        <i class="bi bi-lightbulb text-amber-400"></i> Pro Tip
                    </h5>
                    <p class="leading-relaxed">
                        After tenant creation, assign a subscription plan from the <strong class="text-gray-300">Subscriptions</strong> menu to activate full POS billing capabilities for the owner.
                    </p>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- Password Visibility Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password-btn').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input && icon) {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    icon.classList.toggle('bi-eye', !isPassword);
                    icon.classList.toggle('bi-eye-slash', isPassword);
                }
            });
        });
    });
</script>
@endsection

