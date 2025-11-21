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
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-8 w-auto sm:h-10">
                        <span class="text-lg sm:text-xl font-bold text-indigo-600">Alumni Portal</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Dashboard</a>
                        <a href="{{ route('alumni.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Alumni</a>
                        <a href="{{ route('alumni.map') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Alumni Map</a>
                        <a href="{{ route('events.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Events</a>
                        <a href="{{ route('campaigns.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Campaigns</a>
                        <a href="{{ route('messages.index') }}" class="text-sm text-gray-700 hover:text-indigo-600 relative transition-colors">
                            Messages
                            <span id="unread-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Admin</a>
                        @endif
                        @if(auth()->user()->hasRole('manager'))
                            <a href="{{ route('manager.dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Manager</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors text-sm">Register</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-2 transition-colors" aria-label="Toggle menu">
                        <svg id="menu-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg id="close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200">
                <div class="flex flex-col space-y-1 mt-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">Dashboard</a>
                        <a href="{{ route('alumni.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('alumni.*') && !request()->routeIs('alumni.map*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Alumni</a>
                        <a href="{{ route('alumni.map') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('alumni.map*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Alumni Map</a>
                        <a href="{{ route('events.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('events.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Events</a>
                        <a href="{{ route('campaigns.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('campaigns.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Campaigns</a>
                        <a href="{{ route('messages.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors relative {{ request()->routeIs('messages.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                            Messages
                            <span id="mobile-unread-badge" class="hidden absolute right-3 top-1/2 -translate-y-1/2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('admin.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Admin</a>
                        @endif
                        @if(auth()->user()->hasRole('manager'))
                            <a href="{{ route('manager.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors {{ request()->routeIs('manager.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Manager</a>
                        @endif
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-red-600 rounded-md transition-colors">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600 rounded-md transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700 rounded-md transition-colors text-center">Register</a>
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

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            menu?.classList.toggle('hidden');
            menuIcon?.classList.toggle('hidden');
            closeIcon?.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            if (menu && button && !menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
                menuIcon?.classList.remove('hidden');
                closeIcon?.classList.add('hidden');
            }
        });

        // Update unread message count
        function updateUnreadCount() {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
            
            fetch('{{ route("messages.unread.count") }}', {
                signal: controller.signal
            })
                .then(response => {
                    clearTimeout(timeoutId);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const badge = document.getElementById('unread-badge');
                    const mobileBadge = document.getElementById('mobile-unread-badge');
                    
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        }
                        if (mobileBadge) {
                            mobileBadge.textContent = data.count;
                            mobileBadge.classList.remove('hidden');
                        }
                    } else {
                        badge?.classList.add('hidden');
                        mobileBadge?.classList.add('hidden');
                    }
                })
                .catch(error => {
                    clearTimeout(timeoutId);
                    // Silently fail - don't show errors for unread count
                    if (error.name !== 'AbortError') {
                        console.error('Failed to update unread count:', error);
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