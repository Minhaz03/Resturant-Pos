<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Super Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    Dashboard
                </a>
                <a href="{{ route('admin.tenants.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.tenants.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    Tenants
                </a>
                <a href="{{ route('admin.plans.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.plans.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    Plans
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    Subscriptions
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} mt-2">
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-gray-800 shadow p-4 flex justify-between items-center border-b border-gray-700">
                <h2 class="text-lg font-semibold">@yield('header', 'Dashboard')</h2>
                <div>
                    <span class="mr-4 text-sm text-gray-300">Welcome, {{ auth('admin')->user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded">Logout</button>
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
