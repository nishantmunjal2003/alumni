<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Alumni Portal</title>
    <link rel="icon" type="image/png" href="https://gkv.ac.in/logo.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Admin Header with Purple/Violet Theme -->
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-700 shadow-lg border-b border-purple-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold text-white">Admin Panel</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-purple-200 transition-colors {{ request()->routeIs('admin.dashboard') ? 'font-semibold' : '' }}">Dashboard</a>

                        <!-- User Management Dropdown -->
                        <div class="relative group h-full flex items-center">
                            <button class="text-white hover:text-purple-200 transition-colors flex items-center gap-1 h-full {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.profiles.*') ? 'font-semibold' : '' }}">
                                Users & Profiles
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <!-- Invisible bridge to prevent closing -->
                            <div class="absolute left-0 top-full w-full h-2"></div>
                            <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-50 font-medium' : '' }}">All Users</a>
                                <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-50 font-medium' : '' }}">Roles & Permissions</a>
                                <a href="{{ route('admin.profiles.pending') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.profiles.*') ? 'bg-gray-50 font-medium' : '' }}">Pending Profiles</a>
                            </div>
                        </div>

                        <!-- Events & Campaigns Dropdown -->
                        <div class="relative group h-full flex items-center">
                            <button class="text-white hover:text-purple-200 transition-colors flex items-center gap-1 h-full {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.campaigns.*') ? 'font-semibold' : '' }}">
                                Engagement
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                             <!-- Invisible bridge -->
                            <div class="absolute left-0 top-full w-full h-2"></div>
                            <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                                <a href="{{ route('admin.events.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.events.*') ? 'bg-gray-50 font-medium' : '' }}">Events</a>
                                <a href="{{ route('admin.campaigns.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.campaigns.*') ? 'bg-gray-50 font-medium' : '' }}">Campaigns</a>
                            </div>
                        </div>

                        <!-- Alumni Network Dropdown -->
                        <div class="relative group h-full flex items-center">
                            <button class="text-white hover:text-purple-200 transition-colors flex items-center gap-1 h-full {{ request()->routeIs('admin.alumni.*') || request()->routeIs('messages.*') ? 'font-semibold' : '' }}">
                                Alumni Network
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                             <!-- Invisible bridge -->
                            <div class="absolute left-0 top-full w-full h-2"></div>
                            <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                                <a href="{{ route('admin.alumni.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.alumni.*') && !request()->routeIs('admin.alumni.map*') ? 'bg-gray-50 font-medium' : '' }}">Alumni Directory</a>
                                <a href="{{ route('admin.alumni.map') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.alumni.map*') ? 'bg-gray-50 font-medium' : '' }}">Alumni Map</a>
                                <a href="{{ route('messages.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center justify-between {{ request()->routeIs('messages.*') ? 'bg-gray-50 font-medium' : '' }}">
                                    Messages
                                    <span id="admin-dropdown-unread-badge" class="hidden bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center"></span>
                                </a>
                            </div>
                        </div>

                        <!-- System Dropdown -->
                        <div class="relative group h-full flex items-center">
                            <button class="text-white hover:text-purple-200 transition-colors flex items-center gap-1 h-full {{ request()->routeIs('admin.analytics.*') || request()->routeIs('admin.settings.*') ? 'font-semibold' : '' }}">
                                System
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                             <!-- Invisible bridge -->
                            <div class="absolute left-0 top-full w-full h-2"></div>
                            <div class="absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                                <a href="{{ route('admin.analytics.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.analytics.*') ? 'bg-gray-50 font-medium' : '' }}">Analytics</a>
                                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-50 font-medium' : '' }}">Settings</a>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="flex items-center gap-3 pl-4 border-l border-purple-500">
                            <span class="text-white text-sm hidden lg:inline">{{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-purple-200 transition-colors" title="Go to User Dashboard">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-purple-200 transition-colors text-sm">Logout</button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-white hover:text-purple-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-purple-500">
                <div class="flex flex-col space-y-1 mt-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 {{ request()->routeIs('admin.dashboard') ? 'font-semibold bg-purple-800 rounded' : '' }}">Dashboard</a>

                        <div class="px-2 pt-3 pb-1 text-xs font-semibold text-purple-200 uppercase tracking-wider">User Management</div>
                        <a href="{{ route('admin.users.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.users.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Users</a>
                        <a href="{{ route('admin.roles.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.roles.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Roles</a>
                        <a href="{{ route('admin.profiles.pending') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.profiles.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Pending Profiles</a>

                        <div class="px-2 pt-3 pb-1 text-xs font-semibold text-purple-200 uppercase tracking-wider">Engagement</div>
                        <a href="{{ route('admin.events.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.events.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Events</a>
                        <a href="{{ route('admin.campaigns.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.campaigns.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Campaigns</a>

                        <div class="px-2 pt-3 pb-1 text-xs font-semibold text-purple-200 uppercase tracking-wider">Alumni Network</div>
                        <a href="{{ route('admin.alumni.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.alumni.*') && !request()->routeIs('admin.alumni.map*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Alumni Directory</a>
                        <a href="{{ route('admin.alumni.map') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.alumni.map*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Alumni Map</a>
                        <a href="{{ route('messages.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('messages.*') ? 'font-semibold bg-purple-800 rounded' : '' }} relative">
                            Messages
                            <span id="admin-mobile-unread-badge" class="hidden absolute right-2 top-1/2 -translate-y-1/2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                        </a>

                        <div class="px-2 pt-3 pb-1 text-xs font-semibold text-purple-200 uppercase tracking-wider">System</div>
                        <a href="{{ route('admin.analytics.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.analytics.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Analytics</a>
                        <a href="{{ route('admin.settings.index') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 pl-4 {{ request()->routeIs('admin.settings.*') ? 'font-semibold bg-purple-800 rounded' : '' }}">Settings</a>

                        <div class="border-t border-purple-500 pt-2 mt-4">
                            <div class="text-white text-sm px-2 py-1">{{ auth()->user()->name }}</div>
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-purple-200 transition-colors px-2 py-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                User Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-purple-200 transition-colors px-2 py-1 w-full text-left">Logout</button>
                            </form>
                        </div>
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
        // Update unread message count for admin
        function updateUnreadCount() {
            fetch('{{ route("messages.unread.count") }}')
                .then(response => response.json())
                .then(data => {
                    // Desktop badge
                    const badge = document.getElementById('admin-unread-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else if (badge) {
                        badge.classList.add('hidden');
                    }
                    
                    // Mobile badge
                    const mobileBadge = document.getElementById('admin-mobile-unread-badge');
                    if (mobileBadge && data.count > 0) {
                        mobileBadge.textContent = data.count;
                        mobileBadge.classList.remove('hidden');
                    } else if (mobileBadge) {
                        mobileBadge.classList.add('hidden');
                    }

                    // Dropdown badge
                    const dropdownBadge = document.getElementById('admin-dropdown-unread-badge');
                    if (dropdownBadge && data.count > 0) {
                        dropdownBadge.textContent = data.count;
                        dropdownBadge.classList.remove('hidden');
                    } else if (dropdownBadge) {
                        dropdownBadge.classList.add('hidden');
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

