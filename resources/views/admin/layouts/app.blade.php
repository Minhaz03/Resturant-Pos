<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900 text-white font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 shadow-lg">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-xl font-bold text-blue-400">SaaS Admin</h1>
            </div>
            <nav class="mt-6 px-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.tenants.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.tenants.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    <i class="bi bi-shop me-2"></i> Tenants
                </a>
                <a href="{{ route('admin.plans.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.plans.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    <i class="bi bi-card-checklist me-2"></i> Plans
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    <i class="bi bi-wallet2 me-2"></i> Subscriptions
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-gray-800 shadow p-4 flex justify-between items-center border-b border-gray-700">
                <h2 class="text-lg font-semibold">@yield('header', 'Dashboard')</h2>
                <div class="flex items-center">
                    @php
                        $admin = auth('admin')->user();
                        $unread = $admin->unreadNotifications()->count();
                        $latestNotifs = $admin->notifications()->take(5)->get();
                    @endphp
                    <div class="relative group mr-6 pt-2 pb-2">
                        <a href="{{ route('admin.notifications.index') }}" class="text-gray-300 hover:text-white relative transition-colors block">
                            <i class="bi bi-bell text-xl"></i>
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-gray-800">{{ $unread }}</span>
                            @endif
                        </a>
                        
                        <!-- Dropdown -->
                        <div class="absolute right-0 top-full mt-1 w-80 bg-gray-800 border border-gray-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                            <div class="p-3 border-b border-gray-700 flex justify-between items-center bg-gray-750">
                                <span class="font-semibold text-gray-200">Notifications</span>
                                @if($unread > 0)
                                    <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded">{{ $unread }} New</span>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($latestNotifs as $notification)
                                    <a href="{{ route('admin.notifications.index') }}" class="block p-3 border-b border-gray-750 hover:bg-gray-700 transition-colors {{ is_null($notification->read_at) ? 'bg-gray-750/50' : '' }}">
                                        <div class="text-sm text-gray-300 font-medium">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                        <div class="text-xs text-gray-400 mt-1 truncate">{{ $notification->data['message'] ?? '' }}</div>
                                        <div class="text-[10px] text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-gray-500 text-sm">No notifications yet.</div>
                                @endforelse
                            </div>
                            <div class="p-2 text-center border-t border-gray-700 bg-gray-800">
                                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium">View All Notifications</a>
                            </div>
                        </div>
                    </div>
                    <span class="mr-4 text-sm text-gray-300">Welcome, {{ auth('admin')->user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-md shadow transition-colors font-medium">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6 flex-1 overflow-y-auto bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
