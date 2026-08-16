<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favPos.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="apple-touch-icon" href="{{ asset('favPos.png') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Flatpickr overrides to match app theme */
        .flatpickr-calendar {
            font-family: 'Inter', sans-serif;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,.15);
            border: 1px solid #e2e8f0;
        }
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: var(--primary);
            border-color: var(--primary);
        }
        .flatpickr-day:hover {
            background: #fef2f2;
            border-color: transparent;
        }
        .flatpickr-months .flatpickr-month {
            background: var(--primary);
            border-radius: 12px 12px 0 0;
            color: #fff;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            color: #fff;
        }
        .flatpickr-weekday {
            color: var(--primary);
            font-weight: 600;
        }
        .flatpickr-day.today {
            border-color: var(--primary);
        }
        .numInputWrapper span.arrowUp:after {
            border-bottom-color: #fff;
        }
        .numInputWrapper span.arrowDown:after {
            border-top-color: #fff;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: var(--accent);
        }
    </style>

    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #6B0000;
            --primary-light: #A50000;
            --secondary: #0A2647;
            --accent: #D4AF37;
            --bg: #F5F7FA;
            --sidebar-width: 260px;
            --topbar-height: 60px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: #2d3748;
            margin: 0;
        }

        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--secondary);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 18px 20px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand .brand-name {
            color: var(--accent);
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-brand .brand-sub {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.72rem;
        }

        .nav-section-title {
            padding: 14px 20px 4px;
            font-size: 0.63rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: rgba(255, 255, 255, 0.72);
            text-decoration: none;
            font-size: 0.845rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-left-color: var(--accent);
        }

        .sidebar-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #topbar {
            height: var(--topbar-height);
            background: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 990;
        }

        .topbar-title {
            flex: 1;
            font-size: 1rem;
            font-weight: 600;
            color: var(--secondary);
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            border: none;
            color: #555;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #e2e8f0;
            color: var(--primary);
        }

        .notif-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 14px;
            height: 14px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            font-size: 0.58rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .page-content {
            padding: 24px;
            flex: 1;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .page-header h4 {
            font-weight: 700;
            color: var(--secondary);
            margin: 0;
        }

        .page-header .text-muted {
            font-size: 0.85rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .stat-card {
            border-radius: 12px;
            padding: 22px;
            color: #fff;
            overflow: hidden;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 700;
            margin: 10px 0 2px;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .bg-grad-primary {
            background: linear-gradient(135deg, #8B0000, #C62828);
        }

        .bg-grad-secondary {
            background: linear-gradient(135deg, #0A2647, #155E9F);
        }

        .bg-grad-success {
            background: linear-gradient(135deg, #1a7f5a, #22a06b);
        }

        .bg-grad-warning {
            background: linear-gradient(135deg, #c0760c, #f39c12);
        }

        .bg-grad-info {
            background: linear-gradient(135deg, #0e7490, #0891b2);
        }

        .bg-grad-purple {
            background: linear-gradient(135deg, #5b21b6, #7c3aed);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .table thead th {
            background: #f8fafc;
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 11px 14px;
        }

        .table tbody td {
            padding: 11px 14px;
            vertical-align: middle;
            border-color: #f1f5f9;
            font-size: 0.87rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 0.875rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.84rem;
        }

        .badge {
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 5px;
        }

        .alert {
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #main {
                margin-left: 0 !important;
            }
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }

        .page-loader {
            position: fixed;
            inset: 0;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            flex-direction: column;
            gap: 16px;
        }

        .loader-ring {
            width: 44px;
            height: 44px;
            border: 3px solid rgba(255, 255, 255, 0.15);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="page-loader" id="pageLoader">
        <div class="loader-ring"></div>
        <span style="color:rgba(255,255,255,0.7);font-size:0.85rem">Loading...</span>
    </div>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-2">
                <div
                    style="width:32px;height:32px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-cup-hot-fill text-white" style="font-size:1rem"></i>
                </div>
                <div>
                    <div class="brand-name">Grand RMS</div>
                    <div class="brand-sub">Restaurant Management</div>
                </div>
            </div>
        </div>

        <div class="flex-grow-1">
            <div class="nav-section-title">Main</div>
            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>
            @can('access pos')
                <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i><span>POS System</span>
                </a>
            @endcan
            @can('view kitchen')
                <a href="{{ route('kitchen.index') }}"
                    class="sidebar-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
                    <i class="bi bi-fire"></i><span>Kitchen Display</span>
                </a>
            @endcan

            <div class="nav-section-title">Orders & Tables</div>
            @can('view orders')
                <a href="{{ route('orders.index') }}"
                    class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i><span>Orders</span>
                </a>
            @endcan
            @can('view tables')
                <a href="{{ route('tables.index') }}"
                    class="sidebar-link {{ request()->routeIs('tables.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i><span>Tables</span>
                </a>
            @endcan
            @can('view reservations')
                <a href="{{ route('reservations.index') }}"
                    class="sidebar-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i><span>Reservations</span>
                </a>
            @endcan
            @can('view delivery')
                <a href="{{ route('delivery.index') }}"
                    class="sidebar-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}">
                    <i class="bi bi-bicycle"></i><span>Delivery</span>
                </a>
            @endcan

            <div class="nav-section-title">Menu</div>
            @can('view categories')
                <a href="{{ route('categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tag"></i><span>Categories</span>
                </a>
            @endcan
            @can('view menu')
                <a href="{{ route('menu.index') }}"
                    class="sidebar-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                    <i class="bi bi-menu-button-wide"></i><span>Menu Items</span>
                </a>
            @endcan

            <div class="nav-section-title">People</div>
            @can('view customers')
                <a href="{{ route('customers.index') }}"
                    class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i><span>Customers</span>
                </a>
            @endcan
            @can('view employees')
                <a href="{{ route('employees.index') }}"
                    class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i><span>Employees</span>
                </a>
            @endcan
            @can('view attendance')
                <a href="{{ route('employees.attendance') }}"
                    class="sidebar-link {{ request()->routeIs('employees.attendance') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i><span>Attendance</span>
                </a>
            @endcan

            <div class="nav-section-title">Inventory</div>
            @can('view inventory')
                <a href="{{ route('inventory.index') }}"
                    class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i><span>Inventory</span>
                </a>
            @endcan
            @can('view suppliers')
                <a href="{{ route('suppliers.index') }}"
                    class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i><span>Suppliers</span>
                </a>
            @endcan
            @can('view purchases')
                <a href="{{ route('purchases.index') }}"
                    class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i><span>Purchases</span>
                </a>
            @endcan

        <div class="nav-section-title">Finance & Reports</div>
        <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i><span>Expenses</span>
        </a>
        <a href="{{ route('expense-categories.index') }}" class="sidebar-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i><span>Expense Categories</span>
        </a>
        @can('view coupons')
        <a href="{{ route('coupons.index') }}" class="sidebar-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
            <i class="bi bi-percent"></i><span>Coupons</span>
        </a>
        @endcan
        @can('view reports')
        <a href="{{ route('reports.sales') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i><span>Reports</span>
        </a>
        @endcan

            <div class="nav-section-title">System</div>
            @can('view users')
                <a href="{{ route('users.index') }}"
                    class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i><span>Users</span>
                </a>
            @endcan
            @can('view roles')
                <a href="{{ route('roles.index') }}"
                    class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i><span>Roles & Permissions</span>
                </a>
            @endcan
            @can('view settings')
                <a href="{{ route('settings.index') }}"
                    class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i><span>Settings</span>
                </a>
                <a href="{{ route('dashboard.billing') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.billing') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i><span>Subscription</span>
                </a>
            @endcan
            <a href="{{ route('tickets.index') }}" class="sidebar-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                <i class="bi bi-headset"></i><span>Support Tickets</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                    style="width:32px;height:32px;background:var(--primary);font-size:0.8rem">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow:hidden">
                    <div class="text-white"
                        style="font-size:0.8rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ auth()->user()->name }}</div>
                    <div style="font-size:0.68rem;color:rgba(255,255,255,0.45)">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'user')) }}</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main">
        <div id="topbar">
            <button class="icon-btn" onclick="toggleSidebar()" style="flex-shrink:0">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="topbar-title d-none d-sm-block">@yield('title', 'Dashboard')</span>
            <div style="margin-left:auto;display:flex;align-items:center;gap:8px">
                <div class="dropdown">
                    <button class="icon-btn" data-bs-toggle="dropdown" id="notifBtn" style="border:none" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="notif-dot d-none" id="notifCount">0</span>
                    </button>
                    @php
                        $latestNotifs = auth()->user()->appNotifications()->latest()->take(5)->get();
                    @endphp
                    <div class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 320px; padding: 0; overflow: hidden; border-radius: 12px; margin-top: 8px;">
                        <div class="bg-light px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem; color: var(--secondary);">Notifications</h6>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($latestNotifs as $notification)
                                <a href="{{ route('notifications.index') }}" class="dropdown-item py-2 px-3 border-bottom text-wrap {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                        @if($notification->icon)
                                            <i class="{{ $notification->icon }} text-{{ $notification->color ?? 'primary' }} me-1"></i>
                                        @endif
                                        {{ $notification->title ?? 'Notification' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($notification->message ?? '', 80) }}</div>
                                    <div class="text-muted mt-1" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted" style="font-size: 0.85rem;">No notifications yet.</div>
                            @endforelse
                        </div>
                        <div class="text-center p-2 bg-light border-top">
                            <a href="{{ route('notifications.index') }}" class="text-decoration-none fw-semibold" style="font-size: 0.8rem; color: var(--primary);">View All Notifications</a>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button style="background:none;border:none;padding:0;cursor:pointer" data-bs-toggle="dropdown">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                            style="width:36px;height:36px;background:var(--primary);font-size:0.85rem">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow"
                        style="border-radius:10px;min-width:160px">
                        <li>
                            <h6 class="dropdown-header small">{{ auth()->user()->name }}</h6>
                        </li>
                        <li><a class="dropdown-item small" href="{{ route('profile.edit') }}"><i
                                    class="bi bi-person me-2"></i>Profile</a></li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item small text-danger"><i
                                        class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @php
            $isTrialing = false;
            $daysLeft = 0;
            if(auth()->check() && auth()->user()->tenant) {
                $currentSub = auth()->user()->tenant->currentSubscription;
                if($currentSub && $currentSub->status === 'trialing') {
                    $isTrialing = true;
                    if ($currentSub->ends_at) {
                        $daysLeft = max(0, (int) ceil(now()->diffInDays(\Carbon\Carbon::parse($currentSub->ends_at), false)));
                    }
                }
            }
        @endphp

        @if(session()->has('impersonated_by_admin'))
        <div class="px-4 py-2.5 text-center text-sm font-semibold d-flex align-items-center justify-content-between shadow-sm position-relative" style="background: linear-gradient(90deg, #d97706, #b45309); color: #ffffff; z-index: 1050;">
            <div class="d-flex align-items-center gap-2 mx-auto flex-wrap justify-content-center">
                <i class="bi bi-person-badge-fill fs-5"></i>
                <span>You are currently impersonating <strong>{{ auth()->user()->name }}</strong> (Tenant: <strong>{{ auth()->user()->tenant->name ?? 'N/A' }}</strong>)</span>
                <form action="{{ route('impersonate.leave') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-dark font-weight-bold shadow-sm" style="border-radius: 6px; font-weight: 700;">
                        <i class="bi bi-arrow-left-circle me-1"></i> Back to Super Admin
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($isTrialing)
        <div class="bg-grad-warning text-white px-4 py-2 text-center text-sm fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
            <i class="bi bi-clock-history fs-6"></i>
            <span>Your workspace is currently on a free trial. You have <strong>{{ $daysLeft }}</strong> days left.</span>
            <a href="{{ route('dashboard.billing') }}" class="text-white text-decoration-underline ms-2">Upgrade Now</a>
        </div>
        @endif

        <div class="page-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
        // SweetAlert2 overrides
        window.alert = function(message) {
            Swal.fire({
                title: 'Notification',
                text: message,
                icon: 'info',
                confirmButtonColor: '#8B0000'
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Intercept confirms via data-confirm attribute
            document.body.addEventListener('submit', function(e) {
                const form = e.target;
                const confirmMsg = form.getAttribute('data-confirm');
                if (confirmMsg) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    const title = form.getAttribute('data-confirm-title') || 'Are you sure?';
                    const buttonText = form.getAttribute('data-confirm-button') || 'Yes, proceed!';
                    const icon = form.getAttribute('data-confirm-icon') || 'warning';

                    Swal.fire({
                        title: title,
                        text: confirmMsg,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#8B0000',
                        cancelButtonColor: '#0A2647',
                        confirmButtonText: buttonText,
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                    return;
                }

                // Intercept native confirms in forms (backward compatibility)
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    const match = onsubmitAttr.match(/confirm\(['"](.*)['"]\)/);
                    const message = match ? match[1] : 'Are you sure?';

                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8B0000',
                        cancelButtonColor: '#0A2647',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Temporarily remove to avoid loop, submit, and restore
                            form.removeAttribute('onsubmit');
                            form.submit();
                            form.setAttribute('onsubmit', onsubmitAttr);
                        }
                    });
                }
            });
        });

        window.addEventListener('load', () => document.getElementById('pageLoader').style.display = 'none');

        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            if (window.innerWidth <= 768) s.classList.toggle('open');
            else s.classList.toggle('collapsed');
        }

        function loadNotif() {
            fetch('{{ route('notifications.unread-count') }}')
                .then(r => r.json()).then(d => {
                    const el = document.getElementById('notifCount');
                    if (d.count > 0) {
                        el.textContent = d.count > 9 ? '9+' : d.count;
                        el.classList.remove('d-none');
                    } else el.classList.add('d-none');
                }).catch(() => {});
        }
        loadNotif();
        setInterval(loadNotif, 60000);
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(a => {
                try {
                    bootstrap.Alert.getOrCreateInstance(a).close();
                } catch (e) {}
            });
        }, 5000);
    </script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="date"]').forEach(function (input) {
                // Preserve existing value
                var currentVal = input.value || '';
                // Read min attribute if set
                var minDate = input.getAttribute('min') || null;
                // Detect if there was an onchange that submits the form
                var onchangeAttr = input.getAttribute('onchange') || '';
                var autoSubmit = onchangeAttr.includes('this.form.submit');

                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                    defaultDate: currentVal || null,
                    minDate: minDate || null,
                    disableMobile: false,
                    onChange: function (selectedDates, dateStr) {
                        if (autoSubmit && input.form) {
                            input.form.submit();
                        }
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
