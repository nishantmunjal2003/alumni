@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Stats -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Batch Alumni</p>
                    <p class="text-3xl font-bold mt-1">{{ $batchAlumni->count() }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Alumni</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalAlumniCount }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Registered Alumni</p>
                    <p class="text-3xl font-bold mt-1">{{ $registeredCount }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Batch Alumni Section -->
    @if($user->graduation_year)
        <div class="mb-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">
                My Batch ({{ $user->graduation_year }})
            </h2>
            
            @if($batchAlumni->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($batchAlumni as $alumnus)
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-3">
                                @if($alumnus->profile_image)
                                    <img src="{{ asset('storage/' . $alumnus->profile_image) }}" alt="{{ $alumnus->name }}" 
                                        class="w-12 h-12 rounded-full object-cover mr-3 border-2 border-purple-300">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-purple-300 flex items-center justify-center mr-3 border-2 border-purple-400">
                                        <span class="text-lg font-bold text-purple-700">{{ substr($alumnus->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $alumnus->name }}</h3>
                                    @if($alumnus->company)
                                        <p class="text-sm text-purple-700 font-medium">{{ $alumnus->company }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('alumni.show', $alumnus) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    View Profile →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-center py-8">No other alumni from your batch yet.</p>
            @endif
        </div>
    @else
        <div class="mb-8 bg-yellow-50 rounded-lg border border-yellow-200 p-6">
            <p class="text-yellow-800">
                <strong>Note:</strong> Your graduation year is not set. Please <a href="{{ route('alumni.edit', auth()->user()) }}" class="text-yellow-700 underline font-semibold">update your profile</a> to see batch alumni.
            </p>
        </div>
    @endif

    <!-- Upcoming Events Section -->
    @if($upcomingEvents->count() > 0)
        <div class="mb-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-5 border border-purple-200 hover:shadow-lg transition-shadow">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" 
                                class="w-full h-32 object-cover rounded-lg mb-3">
                        @else
                            <div class="w-full h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-3 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ substr($event->title, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->event_date->format('M d, Y g:i A') }}
                        </p>
                        
                        @if($event->is_registered)
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    ✓ Registered
                                </span>
                                <a href="{{ route('events.show', $event) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        @else
                            <a href="{{ route('events.register', $event) }}" class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold transition-colors">
                                Register Now
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('events.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold">
                    View All Events →
                </a>
            </div>
        </div>
    @endif

    <!-- All Alumni Search Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900">All Alumni Directory</h2>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, major, company..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 font-semibold">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 font-semibold">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <!-- Alumni Grid -->
        @if($allAlumni->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allAlumni as $alumnus)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            @if($alumnus->profile_image)
                                <img src="{{ asset('storage/' . $alumnus->profile_image) }}" alt="{{ $alumnus->name }}" 
                                    class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-purple-300">
                            @else
                                <div class="w-16 h-16 rounded-full bg-purple-300 flex items-center justify-center mr-4 border-2 border-purple-400">
                                    <span class="text-2xl font-bold text-purple-700">{{ substr($alumnus->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900">{{ $alumnus->name }}</h3>
                                @if($alumnus->company)
                                    <p class="text-sm text-purple-700 font-medium">{{ $alumnus->company }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            @if($alumnus->major)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.747 5.754 18 7.5 18s3.332.747 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.747 18.247 18 16.5 18c-1.746 0-3.332.747-4.5 1.253"></path>
                                    </svg>
                                    {{ $alumnus->major }}
                                </div>
                            @endif
                            @if($alumnus->graduation_year)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Batch {{ $alumnus->graduation_year }}
                                </div>
                            @endif
                            @if($alumnus->current_position)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $alumnus->current_position }}
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('alumni.show', $alumnus) }}" class="inline-block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold transition-colors">
                            View Profile
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $allAlumni->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-600">No alumni found.</p>
            </div>
        @endif
    </div>
</div>
@endsection

