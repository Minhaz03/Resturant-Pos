<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - RestoPOS</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f5ff',
                            100: '#e0ebff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#0b0f19] text-slate-100 min-h-full flex items-center justify-center p-4 relative overflow-x-hidden font-sans selection:bg-indigo-500 selection:text-white">

    <!-- Ambient Glowing Background Blobs -->
    <div class="fixed top-0 -left-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none animate-pulse-glow"></div>
    <div class="fixed bottom-0 -right-20 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] pointer-events-none animate-pulse-glow" style="animation-delay: 3s;"></div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[150px] pointer-events-none"></div>

    <!-- Main Outer Container -->
    <div class="w-full max-w-5xl bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-slate-800/80 shadow-2xl shadow-black/60 overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 my-8">

        <!-- Left Column: Branding & Feature Showcase (Hidden on small, visible on lg) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-950/80 via-slate-900 to-slate-950 p-8 lg:p-10 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-slate-800/80 relative overflow-hidden">
            <!-- Decorative Subtle Grid Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293715_1px,transparent_1px),linear-gradient(to_bottom,#1f293715_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <!-- Top Brand Badge -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white font-black text-xl">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-lg text-white tracking-tight leading-none">RestoPOS</h2>
                        <span class="text-[11px] font-semibold text-indigo-400 uppercase tracking-widest">Super Admin</span>
                    </div>
                </div>

                <!-- Headline -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-medium mb-4">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                    Central Control Console
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight mb-3 leading-snug">
                    Enterprise SaaS Administration
                </h1>
                <p class="text-slate-400 text-sm leading-relaxed mb-8">
                    Manage multi-tenant restaurant ecosystems, subscriptions, system parameters, and live telemetry from a single secure portal.
                </p>

                <!-- Feature Items -->
                <div class="space-y-4">
                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-800/40 border border-slate-700/40 backdrop-blur-sm">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="bi bi-shop text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-200">Tenant Lifecycle Management</h4>
                            <p class="text-[11px] text-slate-400">Monitor active restaurant stores, domain bindings, and provisioning status.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-800/40 border border-slate-700/40 backdrop-blur-sm">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="bi bi-credit-card text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-200">Subscription & Gateway Control</h4>
                            <p class="text-[11px] text-slate-400">Automated recurring billing, payment gateway logs, and plan management.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-800/40 border border-slate-700/40 backdrop-blur-sm">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="bi bi-shield-check text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-200">Hardened Access Guard</h4>
                            <p class="text-[11px] text-slate-400">Isolated super-admin session guard with real-time audit logging.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="relative z-10 pt-8 mt-8 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-500">
                <span>&copy; {{ date('Y') }} RestoPOS Inc.</span>
                <span class="inline-flex items-center gap-1.5 text-indigo-400 font-medium">
                    <i class="bi bi-patch-check-fill text-indigo-500"></i> v2.4 Security System
                </span>
            </div>
        </div>

        <!-- Right Column: Login Form Container -->
        <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-slate-900/60">
            <div class="max-w-md mx-auto w-full">
                
                <!-- Form Header -->
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-2xl font-bold text-white tracking-tight mb-2">Super Admin Sign In</h2>
                    <p class="text-slate-400 text-sm">Please authenticate with your elevated administrator credentials.</p>
                </div>

                <!-- Error Messages Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-sm flex items-start gap-3 animate-shake">
                        <i class="bi bi-exclamation-triangle-fill text-rose-400 text-lg shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <h5 class="font-semibold text-rose-200 mb-1">Authentication Failed</h5>
                            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300/90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email Address Input -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            <i class="bi bi-envelope-at text-indigo-400 me-1"></i> Email Address
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <i class="bi bi-envelope text-base"></i>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                placeholder="admin@restopos.com"
                                class="w-full pl-10 pr-4 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shadow-inner"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <i class="bi bi-key text-indigo-400 me-1"></i> Password
                            </label>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <i class="bi bi-lock text-base"></i>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                placeholder="••••••••••••"
                                class="w-full pl-10 pr-11 py-3 bg-slate-950/70 border border-slate-800 rounded-xl text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shadow-inner"
                            >
                            <button 
                                type="button" 
                                id="togglePassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 focus:outline-none transition-colors"
                                aria-label="Toggle password visibility"
                            >
                                <i class="bi bi-eye text-lg" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500/20 focus:ring-offset-0 transition duration-150 cursor-pointer"
                            >
                            <span class="text-xs text-slate-400 group-hover:text-slate-300 transition-colors">Remember this device</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="group relative w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-600 bg-[length:200%_auto] hover:bg-right transition-all duration-300 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 active:scale-[0.99] flex items-center justify-center gap-2"
                        >
                            <span>Access Super Admin Panel</span>
                            <i class="bi bi-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <!-- Security Assurance Footer -->
                <div class="mt-8 pt-6 border-t border-slate-800/80 text-center">
                    <div class="inline-flex items-center gap-2 text-xs text-slate-500 bg-slate-950/50 px-3 py-1.5 rounded-full border border-slate-800/60">
                        <i class="bi bi-shield-lock-fill text-indigo-400"></i>
                        <span>256-Bit SSL Encrypted • Restricted Personnel Only</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Password Visibility Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (togglePasswordBtn && passwordInput && toggleIcon) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        toggleIcon.classList.remove('bi-eye');
                        toggleIcon.classList.add('bi-eye-slash');
                    } else {
                        toggleIcon.classList.remove('bi-eye-slash');
                        toggleIcon.classList.add('bi-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>
