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
<body class="bg-[#FDFDFC] text-[#1b1b18] antialiased">
    <!-- Navigation -->
    <nav class="bg-white border-b border-[#e3e3e0] sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-semibold text-indigo-600">Alumni Portal</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">Dashboard</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        Welcome to Our
                        <span class="text-indigo-600">Alumni Network</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto lg:mx-0">
                        Connect with fellow alumni, stay updated with events, and contribute to meaningful campaigns. 
                        Together, we grow stronger.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Find your alumni <span class="font-semibold text-indigo-600">around the globe</span>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium text-lg transition-colors shadow-lg">
                                Join Our Network
                            </a>
                            <a href="{{ route('login') }}" class="bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 px-8 py-3 rounded-lg font-medium text-lg transition-colors">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                
                <!-- Rotating Globe -->
                <div class="flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md h-96">
                        <div class="globe-container">
                            <div class="globe">
                                <svg class="globe-svg" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Globe circles (latitudes) -->
                                    <circle cx="200" cy="200" r="180" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    <circle cx="200" cy="200" r="150" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    <circle cx="200" cy="200" r="120" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    
                                    <!-- Continents (simplified) -->
                                    <!-- North America -->
                                    <path d="M 80 80 Q 100 60 120 80 Q 140 100 160 90 Q 180 85 200 100 Q 220 110 240 105 Q 260 100 280 110 Q 300 120 320 100 Q 340 90 360 100" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- South America -->
                                    <path d="M 140 200 Q 150 220 160 240 Q 170 260 180 280 Q 190 300 200 310 Q 210 320 220 330 Q 230 340 240 350" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- Europe -->
                                    <path d="M 180 60 Q 200 70 220 65 Q 240 60 260 70 Q 280 75 300 80" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- Africa -->
                                    <path d="M 200 120 Q 210 140 220 160 Q 230 180 240 200 Q 250 220 260 240 Q 270 260 280 280 Q 290 300 300 320" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- Asia -->
                                    <path d="M 240 60 Q 260 80 280 100 Q 300 120 320 140 Q 340 160 360 180 Q 340 200 320 220 Q 300 240 280 260" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- Australia -->
                                    <path d="M 300 280 Q 310 290 320 300 Q 330 310 340 300 Q 350 290 360 300" 
                                          fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" class="continent"/>
                                    
                                    <!-- Longitude lines -->
                                    <line x1="200" y1="20" x2="200" y2="380" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    <line x1="100" y1="20" x2="100" y2="380" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    <line x1="300" y1="20" x2="300" y2="380" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    
                                    <!-- Latitude lines -->
                                    <ellipse cx="200" cy="200" rx="180" ry="60" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    <ellipse cx="200" cy="200" rx="180" ry="120" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"/>
                                    
                                    <!-- Dots representing alumni locations -->
                                    <circle cx="120" cy="90" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="180" cy="70" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="240" cy="100" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="280" cy="80" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="160" cy="250" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="200" cy="280" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="220" cy="65" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="260" cy="75" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="240" cy="180" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="280" cy="240" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="320" cy="150" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="300" cy="290" r="3" fill="#10b981" class="alumni-dot"/>
                                    <circle cx="340" cy="300" r="3" fill="#10b981" class="alumni-dot"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .globe-container {
                perspective: 1200px;
                perspective-origin: center center;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .globe {
                width: 100%;
                height: 100%;
                position: relative;
                transform-style: preserve-3d;
                animation: rotateGlobe 30s linear infinite;
            }
            
            .globe-svg {
                width: 100%;
                height: 100%;
                color: #4f46e5;
                filter: drop-shadow(0 10px 30px rgba(79, 70, 229, 0.2));
            }
            
            .continent {
                animation: pulseContinent 4s ease-in-out infinite;
            }
            
            .alumni-dot {
                animation: pulseDot 2.5s ease-in-out infinite;
                filter: drop-shadow(0 0 4px rgba(16, 185, 129, 0.6));
            }
            
            .alumni-dot:nth-of-type(1) { animation-delay: 0s; }
            .alumni-dot:nth-of-type(2) { animation-delay: 0.2s; }
            .alumni-dot:nth-of-type(3) { animation-delay: 0.4s; }
            .alumni-dot:nth-of-type(4) { animation-delay: 0.6s; }
            .alumni-dot:nth-of-type(5) { animation-delay: 0.8s; }
            .alumni-dot:nth-of-type(6) { animation-delay: 1s; }
            .alumni-dot:nth-of-type(7) { animation-delay: 1.2s; }
            .alumni-dot:nth-of-type(8) { animation-delay: 1.4s; }
            .alumni-dot:nth-of-type(9) { animation-delay: 1.6s; }
            .alumni-dot:nth-of-type(10) { animation-delay: 1.8s; }
            .alumni-dot:nth-of-type(11) { animation-delay: 2s; }
            .alumni-dot:nth-of-type(12) { animation-delay: 2.2s; }
            .alumni-dot:nth-of-type(13) { animation-delay: 2.4s; }
            
            @keyframes rotateGlobe {
                0% {
                    transform: rotateY(0deg) rotateX(10deg);
                }
                100% {
                    transform: rotateY(360deg) rotateX(10deg);
                }
            }
            
            @keyframes pulseContinent {
                0%, 100% {
                    opacity: 0.5;
                    stroke-width: 2;
                }
                50% {
                    opacity: 0.8;
                    stroke-width: 2.5;
                }
            }
            
            @keyframes pulseDot {
                0%, 100% {
                    opacity: 0.7;
                    transform: scale(1);
                }
                50% {
                    opacity: 1;
                    transform: scale(1.5);
                }
            }
            
            @media (prefers-reduced-motion: reduce) {
                .globe {
                    animation: none;
                }
                .continent,
                .alumni-dot {
                    animation: none;
                }
            }
            
            @media (max-width: 768px) {
                .globe-container {
                    perspective: 800px;
                }
            }
        </style>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white border-y border-[#e3e3e0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($totalAlumni) }}+</div>
                    <div class="text-gray-600 font-medium">Registered Alumni</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($totalEvents) }}+</div>
                    <div class="text-gray-600 font-medium">Events Organized</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $recentCampaigns->count() }}</div>
                    <div class="text-gray-600 font-medium">Active Campaigns</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-[#FDFDFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Why Join Our Alumni Portal?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Discover the benefits of being part of our vibrant alumni community
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-lg border border-[#e3e3e0] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Network & Connect</h3>
                    <p class="text-gray-600">
                        Connect with alumni from your batch and across different years. Build meaningful professional relationships.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-[#e3e3e0] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Events & Reunions</h3>
                    <p class="text-gray-600">
                        Stay updated with upcoming events, reunions, and gatherings. Register and reconnect with old friends.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-[#e3e3e0] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Support Campaigns</h3>
                    <p class="text-gray-600">
                        Contribute to meaningful campaigns and initiatives that make a difference in our community.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg border border-[#e3e3e0] shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Direct Messaging</h3>
                    <p class="text-gray-600">
                        Communicate directly with fellow alumni. Share memories, opportunities, and stay in touch.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    @if($upcomingEvents->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        Upcoming Events
                    </h2>
                    <p class="text-gray-600">
                        Don't miss out on these exciting gatherings
                    </p>
                </div>
                <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                <div class="bg-[#FDFDFC] rounded-lg border border-[#e3e3e0] overflow-hidden hover:shadow-lg transition-shadow">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
            @endif
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">
                            {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('F d, Y') }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $event->title }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                            {{ $event->venue ?? ($event->google_maps_link ? 'View on Google Maps' : ($event->location ?? '')) }}
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="inline-block text-indigo-600 hover:text-indigo-700 font-medium">
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
    <section class="py-20 bg-[#FDFDFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                        Active Campaigns
                    </h2>
                    <p class="text-gray-600">
                        Support initiatives that matter
                    </p>
                </div>
                <a href="{{ route('campaigns.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentCampaigns as $campaign)
                <div class="bg-white rounded-lg border border-[#e3e3e0] overflow-hidden hover:shadow-lg transition-shadow">
                    @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="text-sm text-purple-600 font-medium mb-2">
                            {{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d, Y') }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $campaign->title }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {{ Str::limit($campaign->description, 100) }}
                        </p>
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="inline-block text-purple-600 hover:text-purple-700 font-medium">
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
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
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
    <footer class="bg-white border-t border-[#e3e3e0] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="h-10 w-auto">
                        <span class="text-xl font-semibold text-indigo-600">Alumni Portal</span>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Connecting alumni across generations. Building a stronger community together.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('events.index') }}" class="text-gray-600 hover:text-indigo-600">Events</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="text-gray-600 hover:text-indigo-600">Campaigns</a></li>
                        @auth
                            <li><a href="{{ route('alumni.index') }}" class="text-gray-600 hover:text-indigo-600">Alumni Directory</a></li>
                            <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-indigo-600">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Connect</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-600">Stay updated with our latest events</li>
                        <li class="text-gray-600">Join meaningful campaigns</li>
                        <li class="text-gray-600">Network with fellow alumni</li>
                        <li class="text-gray-600">
                            <span>Support: </span>
                            <a href="mailto:admin@gkv.ac.in" class="text-indigo-600 hover:text-indigo-700 underline">admin@gkv.ac.in</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-[#e3e3e0] text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Alumni Portal. All rights reserved.</p>
                <p class="mt-2">
                    <span>Need help? Contact us at </span>
                    <a href="mailto:admin@gkv.ac.in" class="text-indigo-600 hover:text-indigo-700 underline">admin@gkv.ac.in</a>
                </p>
                <p class="mt-2">
                    Designed by <a href="https://www.nishantmunjal.com" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 underline">Dr. Nishant Kumar</a>
                </p>
            </div>
        </div>
    </footer>
    </body>
</html>
