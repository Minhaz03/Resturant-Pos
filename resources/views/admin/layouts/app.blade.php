<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0b0f19]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestoPOS Super Admin Panel</title>
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
                    }
                }
            }
        }
    </script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Dark-theme Flatpickr overrides for admin panel */
        .flatpickr-calendar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,.5);
            color: #cbd5e1;
        }
        .flatpickr-day {
            color: #cbd5e1;
        }
        .flatpickr-day:hover {
            background: #334155;
            border-color: transparent;
        }
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }
        .flatpickr-day.today {
            border-color: #3b82f6;
        }
        .flatpickr-months .flatpickr-month {
            background: #0f172a;
            border-radius: 12px 12px 0 0;
            color: #fff;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            color: #fff;
        }
        .flatpickr-weekday {
            background: #0f172a;
            color: #60a5fa;
            font-weight: 600;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            fill: #94a3b8;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #60a5fa;
        }
        .numInputWrapper span.arrowUp:after {
            border-bottom-color: #94a3b8;
        }
        .numInputWrapper span.arrowDown:after {
            border-top-color: #94a3b8;
        }
        .flatpickr-innerContainer,
        .flatpickr-rContainer {
            background: #1e293b;
        }
    </style>
</head>
<body class="bg-[#0b0f19] text-slate-100 font-sans h-full selection:bg-blue-600 selection:text-white overflow-hidden">
    
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside id="sidebar-menu" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between shrink-0 shadow-2xl transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0 lg:static lg:z-20">
            <div>
                <!-- Brand Header -->
                <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h1 class="font-extrabold text-base text-white tracking-tight leading-none group-hover:text-blue-400 transition-colors">RestoPOS</h1>
                            <span class="text-[10px] font-bold text-blue-400 tracking-widest uppercase">Super Admin</span>
                        </div>
                    </a>

                    <!-- Mobile Close Button -->
                    <button id="mobile-sidebar-close" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition-colors" aria-label="Close sidebar">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 px-3 space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-speedometer2 text-base {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.tenants.*') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-shop text-base {{ request()->routeIs('admin.tenants.*') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>Tenants</span>
                    </a>

                    <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.plans.*') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-card-checklist text-base {{ request()->routeIs('admin.plans.*') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>Subscription Plans</span>
                    </a>

                    <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-wallet2 text-base {{ request()->routeIs('admin.subscriptions.*') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>Subscriptions Log</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-gear text-base {{ request()->routeIs('admin.settings.*') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>System Settings</span>
                    </a>

                    <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.tickets.*') ? 'bg-gradient-to-r from-blue-600/20 to-indigo-600/10 text-blue-400 border-l-4 border-blue-500 shadow-inner' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i class="bi bi-headset text-base {{ request()->routeIs('admin.tickets.*') ? 'text-blue-400' : 'text-slate-400' }}"></i>
                        <span>Support Tickets</span>
                    </a>
                </nav>
            </div>

            <!-- Footer Badge -->
            <div class="p-4 border-t border-slate-800 m-3 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-semibold text-slate-300">Operational</span>
                </div>
                <p class="text-[11px] text-slate-500">RestoPOS Multi-Tenant v2.4</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#0b0f19] overflow-hidden">
            
            <!-- Topbar Header -->
            <header class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800/80 px-4 sm:px-6 py-3.5 flex justify-between items-center z-10 shrink-0 gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <!-- Mobile Sidebar Toggle Button -->
                    <button id="mobile-sidebar-toggle" class="lg:hidden p-2 rounded-xl bg-slate-800/80 border border-slate-700/60 text-slate-300 hover:text-white hover:bg-slate-700/60 focus:outline-none transition-colors shrink-0" aria-label="Open sidebar">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    
                    <div class="min-w-0 flex-1">
                        @yield('header')
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-5 shrink-0">
                    @php
                        $admin = auth('admin')->user();
                        $unread = $admin ? $admin->unreadNotifications()->count() : 0;
                        $latestNotifs = $admin ? $admin->notifications()->take(5)->get() : collect();
                    @endphp

                    <!-- Notifications Dropdown -->
                    <div class="relative group py-1">
                        <a href="{{ route('admin.notifications.index') }}" class="w-9 h-9 rounded-xl bg-slate-800/80 border border-slate-700/60 hover:bg-slate-700/60 flex items-center justify-center text-slate-300 hover:text-white relative transition-colors">
                            <i class="bi bi-bell text-lg"></i>
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-extrabold w-4 h-4 rounded-full flex items-center justify-center border-2 border-slate-900 animate-pulse">
                                    {{ $unread }}
                                </span>
                            @endif
                        </a>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-72 sm:w-80 bg-slate-900/95 backdrop-blur-2xl border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                            <div class="p-3.5 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
                                <span class="font-bold text-xs text-white uppercase tracking-wider">System Notifications</span>
                                @if($unread > 0)
                                    <span class="text-[10px] bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2 py-0.5 rounded-full font-bold">{{ $unread }} New</span>
                                @endif
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-800/50">
                                @forelse($latestNotifs as $notification)
                                    <a href="{{ route('admin.notifications.index') }}" class="block p-3.5 hover:bg-slate-800/50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-500/5' : '' }}">
                                        <div class="text-xs text-slate-200 font-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                        <div class="text-[11px] text-slate-400 mt-1 truncate">{{ $notification->data['message'] ?? '' }}</div>
                                        <div class="text-[10px] text-slate-500 mt-1.5 flex items-center gap-1">
                                            <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-slate-500 text-xs">
                                        <i class="bi bi-bell-slash text-2xl block mb-2 opacity-40"></i>
                                        No recent notifications.
                                    </div>
                                @endforelse
                            </div>
                            <div class="p-2 text-center border-t border-slate-800 bg-slate-950/40">
                                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold">View All Notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile & Logout -->
                    <div class="flex items-center gap-3 pl-3 border-l border-slate-800">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-md">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden md:block">
                            <span class="text-xs font-bold text-white block leading-none">{{ auth('admin')->user()->name ?? 'Super Admin' }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">Administrator</span>
                        </div>
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline ml-1">
                            @csrf
                            <button type="submit" title="Sign Out" class="w-8 h-8 rounded-xl bg-slate-800/80 border border-slate-700/60 hover:bg-rose-500/20 hover:border-rose-500/30 text-slate-400 hover:text-rose-400 flex items-center justify-center transition-colors">
                                <i class="bi bi-box-arrow-right text-sm"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </header>

            <!-- Page Content Scroll Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 bg-[#0b0f19]">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Responsive Sidebar Toggle JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggleBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('mobile-sidebar-close');

            function openSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            }

            function closeSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Auto close mobile drawer on route change / click
            document.querySelectorAll('#sidebar-menu nav a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="date"]').forEach(function (input) {
                var currentVal = input.value || '';
                var minDate = input.getAttribute('min') || null;
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                    defaultDate: currentVal || null,
                    minDate: minDate || null,
                    disableMobile: false,
                });
            });
        });
    </script>
</body>
</html>

