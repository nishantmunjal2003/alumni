@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Welcome Header -->
    <div class="pt-2 sm:pt-0">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-sm sm:text-base text-gray-600">Here's what's happening in your alumni network</p>
    </div>

    <!-- Missing Information Alerts -->
    @if($missingEnrollmentNo || $missingProofDocument)
        <div class="space-y-3">
            @if($missingEnrollmentNo)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-yellow-800">
                                Enrollment Number Missing
                            </p>
                            <p class="mt-1 text-sm text-yellow-700">
                                Please complete your enrollment number in your profile to help us better identify you.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline inline-flex items-center gap-1">
                                    Update Profile
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($missingProofDocument)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-yellow-800">
                                Proof Document Missing
                            </p>
                            <p class="mt-1 text-sm text-yellow-700">
                                Please upload your proof document (ID Card/Marksheet) in your profile to complete your registration.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline inline-flex items-center gap-1">
                                    Upload Document
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Batchmates and Events Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Batchmates Card -->
        <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Batchmates</h2>
                @if($batchmates->count() > 0)
                    <span class="text-xs sm:text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $batchmates->count() }}</span>
                @endif
            </div>
            @if($batchmates->count() > 0)
                <div class="space-y-3 sm:space-y-4">
                    @foreach($batchmates as $batchmate)
                        <a href="{{ route('alumni.show', $batchmate->id) }}" class="flex items-center gap-3 sm:gap-4 p-2.5 sm:p-3 rounded-lg hover:bg-gray-50 active:bg-gray-100 transition-colors group touch-manipulation">
                            @if($batchmate->profile_image)
                                <img src="{{ $batchmate->profile_image_url }}" alt="{{ $batchmate->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full ring-2 ring-gray-200 group-hover:ring-indigo-500 transition-all flex-shrink-0 object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-500 transition-all flex-shrink-0 hidden">
                                    <span class="text-indigo-600 font-semibold text-xs sm:text-sm">{{ getUserInitials($batchmate->name) }}</span>
                                </div>
                            @else
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-500 transition-all flex-shrink-0">
                                    <span class="text-indigo-600 font-semibold text-xs sm:text-sm">{{ getUserInitials($batchmate->name) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm sm:text-base text-gray-900 group-hover:text-indigo-600 transition-colors truncate">{{ $batchmate->name }}</p>
                                @if($batchmate->current_position)
                                    <p class="text-xs sm:text-sm text-gray-600 truncate mt-0.5">{{ $batchmate->current_position }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('alumni.index', ['passing_year' => auth()->user()->passing_year]) }}" class="mt-4 sm:mt-6 inline-flex items-center text-xs sm:text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors touch-manipulation">
                    View all batchmates
                    <svg class="ml-1 w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <div class="text-center py-8 sm:py-10">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm sm:text-base text-gray-500">No batchmates found.</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Events Card -->
        <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Upcoming Events</h2>
                @if($upcomingEvents->count() > 0)
                    <span class="text-xs sm:text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $upcomingEvents->count() }}</span>
                @endif
            </div>
            @if($upcomingEvents->count() > 0)
                <div class="space-y-3 sm:space-y-4">
                    @foreach($upcomingEvents as $event)
                        <div class="p-3 sm:p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
                            <a href="{{ route('events.show', $event->id) }}" class="block mb-3 touch-manipulation">
                                <p class="font-medium text-sm sm:text-base text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $event->title }}</p>
                                <div class="mt-1.5 flex items-center gap-3 flex-wrap">
                                    <p class="text-xs sm:text-sm text-gray-600">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('M d, Y') }}
                                    </p>
                                    @if($event->registrations_count > 0)
                                        <p class="text-xs sm:text-sm text-gray-600">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $event->registrations_count }} {{ Str::plural('fellow', $event->registrations_count) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200">
                                <a href="{{ route('events.show', $event->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors touch-manipulation">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                @if($event->registrations_count > 0)
                                    <a href="{{ route('events.fellows', $event->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors touch-manipulation shadow-sm hover:shadow-md">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Registered Fellows
                                    </a>
                                @else
                                    <span class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        No Fellows Yet
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('events.index') }}" class="mt-4 sm:mt-6 inline-flex items-center text-xs sm:text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors touch-manipulation">
                    View all events
                    <svg class="ml-1 w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <div class="text-center py-8 sm:py-10">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm sm:text-base text-gray-500 mb-2">No upcoming events.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-0">
                        <a href="{{ route('events.index') }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-700 transition-colors touch-manipulation">View all events →</a>
                        @if(auth()->user()->hasRole('admin'))
                            <span class="hidden sm:inline text-gray-400 mx-2">|</span>
                            <a href="{{ route('admin.events.index') }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-700 transition-colors touch-manipulation">Manage events →</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 border border-gray-200">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center p-5 sm:p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 active:from-indigo-200 active:to-indigo-300 transition-all group touch-manipulation">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="font-medium text-sm sm:text-base text-indigo-900 text-center">Edit Profile</p>
            </a>
            <a href="{{ route('alumni.index') }}" class="flex flex-col items-center justify-center p-5 sm:p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 active:from-indigo-200 active:to-indigo-300 transition-all group touch-manipulation">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="font-medium text-sm sm:text-base text-indigo-900 text-center">Alumni Directory</p>
            </a>
            <a href="{{ route('messages.index') }}" class="flex flex-col items-center justify-center p-5 sm:p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 active:from-indigo-200 active:to-indigo-300 transition-all group touch-manipulation sm:col-span-2 lg:col-span-1">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="font-medium text-sm sm:text-base text-indigo-900 text-center">Messages</p>
            </a>
        </div>
    </div>
</div>
@endsection