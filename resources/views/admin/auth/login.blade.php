<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 h-screen flex items-center justify-center font-sans">
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-md border border-gray-700">
        <h1 class="text-2xl font-bold mb-6 text-center text-white">Super Admin Login</h1>

        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border border-gray-600 bg-gray-700 rounded w-full py-2 px-3 text-white leading-tight focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border border-gray-600 bg-gray-700 rounded w-full py-2 px-3 text-white leading-tight focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" id="password" name="password" type="password" required>
            </div>
            
            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500">
                <label for="remember" class="text-sm text-gray-400">Remember Me</label>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline transition duration-150" type="submit">
                    Login to Admin Panel
                </button>
            </div>
        </form>
    </div>
</body>
</html>
