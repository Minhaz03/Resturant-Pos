<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand RMS — Restaurant Management & Cloud POS</title>
    <link rel="icon" type="image/png" href="{{ asset('favPos.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            red: '#8B0000',
                            redDark: '#6B0000',
                            navy: '#0A2647',
                            gold: '#D4AF37',
                            bg: '#F8FAFC',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#F8FAFC] text-slate-800 font-sans antialiased overflow-x-hidden">

    <!-- Navigation Header -->
    <header
        class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5 group">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-brand-gold to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/20 group-hover:rotate-6 transition-transform duration-300">
                        <i class="bi bi-cup-hot-fill text-white text-lg"></i>
                    </div>
                    <span class="font-outfit font-bold text-xl text-brand-navy tracking-tight">Grand<span
                            class="text-brand-red">RMS</span></span>
                </a>

                <!-- Nav Menu (Desktop) -->
                {{-- <nav class="hidden md:flex items-center gap-8 font-medium text-slate-600 text-sm">
                    <a href="#features" class="hover:text-brand-red transition-colors">Features</a>
                    <a href="#demo" class="hover:text-brand-red transition-colors">Dashboard Preview</a>
                    <a href="#pricing" class="hover:text-brand-red transition-colors">Pricing</a>
                </nav> --}}

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}"
                        class="text-slate-600 hover:text-brand-red font-medium text-sm transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('tenant.register') }}"
                        class="bg-brand-red hover:bg-brand-redDark text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-red-900/10 hover:shadow-red-900/20 transition-all duration-200 hover:-translate-y-0.5">
                        Register Restaurant
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-10 pb-20 lg:pt-16 lg:pb-32 overflow-hidden">
        <!-- Background decorative vectors -->
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-72 h-72 bg-amber-200/20 blur-3xl rounded-full -z-10">
        </div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-red-100/30 blur-3xl rounded-full -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left: Value Prop -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-brand-red border border-red-100">
                        <i class="bi bi-star-fill text-brand-gold"></i>
                        The Ultimate Restaurant OS
                    </span>
                    <h1
                        class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.1] text-brand-navy">
                        Run Your Restaurant <br>
                        <span class="bg-gradient-to-r from-brand-red to-red-600 bg-clip-text text-transparent">Smarter &
                            Faster</span>
                    </h1>
                    <p class="text-slate-600 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Grand RMS combines high-speedtableside POS billing, live kitchen status tracking, tables &
                        reservations, real-time inventory control, and automated sales reports into a single, cohesive
                        cloud platform.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                        <a href="#register"
                            class="bg-brand-red hover:bg-brand-redDark text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-red-900/10 hover:shadow-red-900/20 transition-all duration-200 hover:-translate-y-0.5 text-center">
                            Get Started Free
                        </a>
                        <a href="#register"
                            class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 text-center">
                            See POS Dashboard
                        </a>
                    </div>

                    <!-- Trust indicators -->
                    <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/80 max-w-md mx-auto lg:mx-0">
                        <div>
                            <div class="font-outfit font-bold text-2xl text-brand-navy">99.9%</div>
                            <div class="text-xs text-slate-500 font-medium">Uptime Guarantee</div>
                        </div>
                        <div>
                            <div class="font-outfit font-bold text-2xl text-brand-navy">2.4x</div>
                            <div class="text-xs text-slate-500 font-medium">Faster Ordering</div>
                        </div>
                        <div>
                            <div class="font-outfit font-bold text-2xl text-brand-navy">15m</div>
                            <div class="text-xs text-slate-500 font-medium">Easy Setup</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Dashboard CSS Mockup -->
                <div class="lg:col-span-6" id="demo">
                    <div
                        class="bg-slate-900 rounded-2xl p-4 shadow-2xl border border-slate-800 shadow-slate-900/20 relative">
                        <!-- Simulated POS Window Topbar -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-4">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            </div>
                            <span
                                class="text-xs text-slate-500 font-mono font-medium">pos.grand-rms.com/dashboard</span>
                            <div class="w-4 h-4 text-slate-500"><i class="bi bi-gear-fill"></i></div>
                        </div>

                        <!-- Simulated Stats Cards -->
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="bg-slate-800/50 border border-slate-700/30 p-3 rounded-xl">
                                <div class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Today's
                                    Sales</div>
                                <div class="text-sm font-bold text-white font-mono mt-1">$1,482.00</div>
                                <span class="text-[9px] text-green-400 font-medium"><i class="bi bi-arrow-up-short"></i>
                                    +12%</span>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700/30 p-3 rounded-xl">
                                <div class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Active
                                    Tables</div>
                                <div class="text-sm font-bold text-white font-mono mt-1">14 / 24</div>
                                <span class="text-[9px] text-amber-400 font-medium">Busy Hour</span>
                            </div>
                            <div class="bg-slate-800/50 border border-slate-700/30 p-3 rounded-xl">
                                <div class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Kitchen
                                    Status</div>
                                <div class="text-sm font-bold text-brand-gold font-mono mt-1">6 Pending</div>
                                <span class="text-[9px] text-slate-400 font-medium">KDS sync'd</span>
                            </div>
                        </div>

                        <!-- Simulated Live Order list -->
                        <div class="bg-slate-800/30 border border-slate-800/80 rounded-xl p-3">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider">Live POS Queue
                                </h4>
                                <span
                                    class="text-[10px] bg-brand-red/20 text-red-300 font-semibold px-2 py-0.5 rounded-full">KDS
                                    Sync</span>
                            </div>
                            <div class="space-y-2">
                                <div
                                    class="flex justify-between items-center bg-slate-800/60 p-2.5 rounded-lg border-l-4 border-emerald-500">
                                    <div>
                                        <div class="text-xs font-bold text-white">Table 5 — Order #2049</div>
                                        <div class="text-[10px] text-slate-400">1x Ribeye Steak, 2x Coca-Cola</div>
                                    </div>
                                    <span
                                        class="text-[9px] bg-emerald-500/10 text-emerald-400 font-semibold px-2 py-0.5 rounded border border-emerald-500/20">Preparing</span>
                                </div>
                                <div
                                    class="flex justify-between items-center bg-slate-800/60 p-2.5 rounded-lg border-l-4 border-amber-500">
                                    <div>
                                        <div class="text-xs font-bold text-white">Table 12 — Order #2050</div>
                                        <div class="text-[10px] text-slate-400">1x Grilled Salmon, 1x Fresh Orange Juice
                                        </div>
                                    </div>
                                    <span
                                        class="text-[9px] bg-amber-500/10 text-amber-400 font-semibold px-2 py-0.5 rounded border border-amber-500/20">Pending</span>
                                </div>
                                <div
                                    class="flex justify-between items-center bg-slate-800/60 p-2.5 rounded-lg border-l-4 border-red-500">
                                    <div>
                                        <div class="text-xs font-bold text-white">Delivery Order #2048</div>
                                        <div class="text-[10px] text-slate-400">2x Beef Burgers, 1x French Fries</div>
                                    </div>
                                    <span
                                        class="text-[9px] bg-red-500/10 text-red-400 font-semibold px-2 py-0.5 rounded border border-red-500/20">Driver
                                        Assigned</span>
                                </div>
                            </div>
                        </div>

                        <!-- Float bubble info -->
                        <div
                            class="absolute -bottom-6 -left-6 bg-white border border-slate-100 p-4 rounded-xl shadow-lg hidden sm:flex items-center gap-3">
                            <div
                                class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <div class="text-[11px] text-slate-500 font-semibold uppercase">Order Latency</div>
                                <div class="text-sm font-bold text-brand-navy">Under 4.2 mins average</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-brand-red font-bold text-sm tracking-wider uppercase">Features Spotlight</span>
                <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-brand-navy">All-In-One Restaurant
                    Operating System</h2>
                <p class="text-slate-500">Everything you need to manage your dine-in, takeaway, and digital delivery
                    workflows seamlessly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: POS -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-red-50 text-brand-red rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-cart3 text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">High-Speed Cloud POS</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Process walk-in, tableside, or phone orders instantly. Add coupons, custom business hours, and
                        handle split bills easily.
                    </p>
                </div>

                <!-- Feature 2: KDS -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 bg-amber-50 text-brand-gold rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-fire text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">Kitchen Display System</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Say goodbye to paper tickets. Stream order information directly to kitchen screens in real-time
                        with status controls.
                    </p>
                </div>

                <!-- Feature 3: Inventory -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-boxes text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">Smart Inventory & Purchases</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Track raw ingredients and stock levels. Set purchase orders, record wastage, and monitor
                        supplier databases.
                    </p>
                </div>

                <!-- Feature 4: Table Reservations -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-grid-3x3-gap text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">Table Reservations</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Manage table layouts and customer seatings. Take advance bookings, map them to tables, and check
                        active counts.
                    </p>
                </div>

                <!-- Feature 5: Delivery -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-bicycle text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">Delivery Management</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Assign drivers to home deliveries, track delivery statuses, record timestamps, and improve
                        customer delivery expectations.
                    </p>
                </div>

                <!-- Feature 6: Reports & Tax -->
                <div
                    class="bg-white p-8 rounded-2xl border border-slate-100 hover:border-brand-red/10 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="bi bi-bar-chart-line text-xl"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-lg text-brand-navy mb-2">Rich Financial Reports</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Export beautiful PDF or CSV sales reports, tax summary invoices, inventory adjustments, and
                        track customer loyalty.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-20 lg:py-32 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white border border-slate-200/60 rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12">

                <!-- Info Column (Left 5 cols) -->
                <div
                    class="md:col-span-5 bg-gradient-to-br from-brand-navy to-slate-900 p-8 sm:p-10 text-white flex flex-col justify-between">
                    <div>
                        <span class="text-brand-gold text-xs font-bold uppercase tracking-widest">Register
                            Tenant</span>
                        <h3 class="font-outfit font-bold text-2xl mt-2 mb-4 leading-snug">Launch Your Cloud Restaurant
                        </h3>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Create your restaurant tenant workspace instantly. Each workspace operates on a private,
                            securely scoped database environment.
                        </p>
                    </div>
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-2.5 text-xs text-slate-300">
                            <i class="bi bi-check-circle-fill text-brand-gold"></i>
                            <span>Instant admin user generation</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-slate-300">
                            <i class="bi bi-check-circle-fill text-brand-gold"></i>
                            <span>Separate private tenant scope</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-slate-300">
                            <i class="bi bi-check-circle-fill text-brand-gold"></i>
                            <span>No credit card required</span>
                        </div>
                    </div>
                </div>

                <!-- Form Column (Right 7 cols) -->
                <div class="md:col-span-7 p-8 sm:p-10">
                    <h3 class="font-outfit font-bold text-xl text-brand-navy mb-1">Create Restaurant Account</h3>
                    <p class="text-slate-500 text-xs mb-6">Create your admin log-in credentials below.</p>

                    @if (session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm mb-4 flex gap-2 items-center"
                            role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl text-xs mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tenant.register') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-slate-700 text-xs font-bold uppercase tracking-wide mb-1.5"
                                for="restaurant_name">Restaurant Name</label>
                            <input
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-sm text-slate-800 focus:outline-none focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition-all"
                                id="restaurant_name" name="restaurant_name" type="text"
                                placeholder="e.g. Bella Italia Bistro" required>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-xs font-bold uppercase tracking-wide mb-1.5"
                                for="email">Admin Email Address</label>
                            <input
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-sm text-slate-800 focus:outline-none focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition-all"
                                id="email" name="email" type="email" placeholder="admin@restaurant.com"
                                required>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-xs font-bold uppercase tracking-wide mb-1.5"
                                for="password">Create Password</label>
                            <input
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-sm text-slate-800 focus:outline-none focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition-all"
                                id="password" name="password" type="password" placeholder="Min. 8 characters"
                                required>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-xs font-bold uppercase tracking-wide mb-1.5"
                                for="password_confirmation">Confirm Password</label>
                            <input
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3.5 text-sm text-slate-800 focus:outline-none focus:bg-white focus:border-brand-red focus:ring-4 focus:ring-brand-red/10 transition-all"
                                id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="Confirm your password" required>
                        </div>

                        <div class="pt-2">
                            <button
                                class="w-full bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-red-900/10 hover:shadow-red-900/20 transition-all duration-200 hover:-translate-y-0.5"
                                type="submit">
                                Register Restaurant Workspace
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-brand-gold rounded-lg flex items-center justify-center text-white">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <span class="font-outfit font-bold text-white">Grand RMS</span>
                </div>
                <div class="flex gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] uppercase tracking-widest text-brand-gold font-bold">Built by</span>
                        <img class="h-6 w-40 object-contain brightness-0 invert opacity-60 hover:opacity-100 transition-opacity duration-300"
                            src="{{ asset('solutionclime.webp') }}" alt="Solution Clime">
                    </div>
                </div>
            </div>
            <div class="text-sm text-center text-slate-500 mt-2 pt-2 border-t border-slate-800/40">
                &copy; 2026 Grand RMS. All rights reserved. Built with passion for dining services.
            </div>
        </div>
    </footer>

</body>

</html>
