<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alumni Portal') }} - Connect, Network, Grow Together</title>
        <link rel="icon" type="image/png" href="https://gkv.ac.in/logo.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-[#161615] border-b border-[#e3e3e0] dark:border-[#3E3E3A] sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">Alumni Portal</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-[#1a1a1a] dark:via-[#161615] dark:to-[#1a1a1a] py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    Welcome to Our
                    <span class="text-indigo-600 dark:text-indigo-400">Alumni Network</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    Connect with fellow alumni, stay updated with events, and contribute to meaningful campaigns. 
                    Together, we grow stronger.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                            Join Our Network
                        </a>
                        <a href="{{ route('login') }}" class="bg-white dark:bg-[#161615] border-2 border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-[#1a1a1a] px-8 py-3 rounded-lg font-medium text-lg transition-colors">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white dark:bg-[#161615] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ number_format($totalAlumni) }}+</div>
                    <div class="text-gray-600 dark:text-gray-400 font-medium">Active Alumni</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ number_format($totalEvents) }}+</div>
                    <div class="text-gray-600 dark:text-gray-400 font-medium">Events Organized</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ $recentCampaigns->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400 font-medium">Active Campaigns</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Why Join Our Alumni Portal?
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Discover the benefits of being part of our vibrant alumni community
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Network & Connect</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Connect with alumni from your batch and across different years. Build meaningful professional relationships.
                    </p>
                </div>
                <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Events & Reunions</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Stay updated with upcoming events, reunions, and gatherings. Register and reconnect with old friends.
                    </p>
                </div>
                <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Support Campaigns</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Contribute to meaningful campaigns and initiatives that make a difference in our community.
                    </p>
                </div>
                <div class="bg-white dark:bg-[#161615] p-6 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Direct Messaging</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Communicate directly with fellow alumni. Share memories, opportunities, and stay in touch.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    @if($upcomingEvents->count() > 0)
    <section class="py-20 bg-white dark:bg-[#161615]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Upcoming Events
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Don't miss out on these exciting gatherings
                    </p>
                </div>
                <a href="{{ route('events.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden hover:shadow-lg transition-shadow">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center">
                            <svg class="w-16 h-16 text-indigo-400 dark:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
            @endif
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 dark:text-indigo-400 font-medium mb-2">
                            {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('F d, Y') }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $event->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-500 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                            {{ $event->venue ?? ($event->google_maps_link ? 'View on Google Maps' : ($event->location ?? '')) }}
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">
                            Learn More →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent Campaigns Section -->
    @if($recentCampaigns->count() > 0)
    <section class="py-20 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Active Campaigns
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Support initiatives that matter
                    </p>
                </div>
                <a href="{{ route('campaigns.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentCampaigns as $campaign)
                <div class="bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden hover:shadow-lg transition-shadow">
                    @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center">
                            <svg class="w-16 h-16 text-purple-400 dark:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="text-sm text-purple-600 dark:text-purple-400 font-medium mb-2">
                            {{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d, Y') }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $campaign->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ Str::limit($campaign->description, 100) }}
                        </p>
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="inline-block text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">
                            Learn More →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                Ready to Join Our Community?
            </h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Connect with thousands of alumni, participate in events, and make a difference through our campaigns.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-block bg-white text-indigo-600 hover:bg-gray-50 px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                    Go to Dashboard
                </a>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 hover:bg-gray-50 px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}" class="inline-block bg-indigo-700 hover:bg-indigo-800 text-white px-8 py-3 rounded-lg font-medium text-lg transition-colors border-2 border-white">
                        Sign In
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-[#161615] border-t border-[#e3e3e0] dark:border-[#3E3E3A] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">Alumni Portal</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Connecting alumni across generations. Building a stronger community together.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('events.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Events</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Campaigns</a></li>
                        @auth
                            <li><a href="{{ route('alumni.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Alumni Directory</a></li>
                            <li><a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Connect</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-600 dark:text-gray-400">Stay updated with our latest events</li>
                        <li class="text-gray-600 dark:text-gray-400">Join meaningful campaigns</li>
                        <li class="text-gray-600 dark:text-gray-400">Network with fellow alumni</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A] text-center text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} Alumni Portal. All rights reserved.</p>
                <p class="mt-2">
                    Designed by <a href="https://www.nishantmunjal.com" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline">Dr. Nishant Kumar</a>
                </p>
            </div>
        </div>
    </footer>
    </body>
</html>
