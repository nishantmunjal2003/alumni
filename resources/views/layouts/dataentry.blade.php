<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DataEntry Panel') - Alumni Portal</title>
    <link rel="icon" type="image/png" href="https://gkv.ac.in/logo.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- DataEntry Header with Teal/Cyan Theme -->
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-700 shadow-lg border-b border-teal-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dataentry.dashboard') }}" class="flex items-center gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold text-white">DataEntry Panel</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dataentry.dashboard') }}" class="text-white hover:text-teal-200 transition-colors {{ request()->routeIs('dataentry.dashboard') ? 'font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('dataentry.profiles.index') }}" class="text-white hover:text-teal-200 transition-colors {{ request()->routeIs('dataentry.profiles.*') ? 'font-semibold' : '' }}">Pending Profiles</a>
                        
                        <!-- User Menu -->
                        <div class="flex items-center gap-3 pl-4 border-l border-teal-500">
                            <span class="text-white text-sm hidden lg:inline">{{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-teal-200 transition-colors" title="Go to User Dashboard">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-teal-200 transition-colors text-sm">Logout</button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-white hover:text-teal-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-teal-500">
                <div class="flex flex-col space-y-2 mt-4">
                    @auth
                        <a href="{{ route('dataentry.dashboard') }}" class="text-white hover:text-teal-200 transition-colors px-2 py-1 {{ request()->routeIs('dataentry.dashboard') ? 'font-semibold bg-teal-800 rounded' : '' }}">Dashboard</a>
                        <a href="{{ route('dataentry.profiles.index') }}" class="text-white hover:text-teal-200 transition-colors px-2 py-1 {{ request()->routeIs('dataentry.profiles.*') ? 'font-semibold bg-teal-800 rounded' : '' }}">Pending Profiles</a>
                        <div class="border-t border-teal-500 pt-2 mt-2">
                            <div class="text-white text-sm px-2 py-1">{{ auth()->user()->name }}</div>
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-teal-200 transition-colors px-2 py-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                User Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-teal-200 transition-colors px-2 py-1 w-full text-left">Logout</button>
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

    @if(isset($errors) && $errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</body>
</html>

