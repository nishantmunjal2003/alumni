<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Alumni Portal')</title>
    <link rel="icon" type="image/png" href="https://gkv.ac.in/logo.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold text-indigo-600">Alumni Portal</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                        <a href="{{ route('alumni.index') }}" class="text-gray-700 hover:text-indigo-600">Alumni</a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-indigo-600">Events</a>
                        <a href="{{ route('campaigns.index') }}" class="text-gray-700 hover:text-indigo-600">Campaigns</a>
                        <a href="{{ route('messages.index') }}" class="text-gray-700 hover:text-indigo-600 relative">
                            Messages
                            <span id="unread-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-indigo-600">Admin</a>
                        @endif
                        @if(auth()->user()->hasRole('manager'))
                            <a href="{{ route('manager.dashboard') }}" class="text-gray-700 hover:text-indigo-600">Manager</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-indigo-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <script>
        // Update unread message count
        function updateUnreadCount() {
            fetch('{{ route("messages.unread.count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('unread-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
        }

        @auth
        // Update every 30 seconds
        setInterval(updateUnreadCount, 30000);
        updateUnreadCount();
        @endauth
    </script>
</body>
</html>

