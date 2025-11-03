<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alumni Directory') - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen flex flex-col">
    <nav class="fixed top-0 left-0 right-0 z-50 {{ auth()->check() && auth()->user()->isAdmin() ? 'bg-gradient-to-r from-yellow-600 to-yellow-800' : 'bg-gradient-to-r from-purple-600 to-purple-800' }} text-white shadow-xl backdrop-blur-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="text-2xl font-bold hover:opacity-80 transition-colors duration-300">Alumni Directory</a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Dashboard</a>
                        <a href="{{ route('alumni.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Directory</a>
                        <a href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Campaigns</a>
                        <a href="{{ route('events.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Events</a>
                        
                        <!-- Messages Link with Badge -->
                        @php
                            $unreadCount = \App\Models\Message::where('to_user_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <a href="{{ route('messages.index') }}" class="relative px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="bg-white text-yellow-700 hover:bg-gray-100 px-4 py-2 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">Admin Panel</a>
                        @endif
                        
                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 focus:outline-none">
                                @if(auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" 
                                        class="w-8 h-8 rounded-full object-cover border-2 border-white/30">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/30">
                                        <span class="text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-200">
                                <a href="{{ route('alumni.show', auth()->user()) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Profile
                                    </div>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Campaigns</a>
                        <a href="{{ route('events.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Events</a>
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg hover:bg-white/15 transition-all duration-300 hover:-translate-y-0.5">Login</a>
                        <a href="{{ route('register') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 flex-1 mt-20">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 shadow-md animate-slide-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-md animate-slide-in">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->has('alumni'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-md animate-slide-in">
                Alumni not found. Please check the URL.
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-md animate-slide-in">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white mt-12 py-6 shadow-xl">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Alumni Directory. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>

