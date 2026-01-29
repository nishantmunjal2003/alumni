@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Welcome Header & Status -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6 w-full md:w-auto">
            <div class="relative group">
                @if(auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image_url }}" alt="Profile" class="w-20 h-20 rounded-full object-cover border-4 border-indigo-50 shadow-md">
                @else
                    <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-indigo-50 shadow-md text-indigo-600 font-bold text-2xl">
                        {{ getUserInitials(auth()->user()->name) }}
                    </div>
                @endif
                <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="profileImageForm" class="absolute bottom-0 right-0">
                    @csrf
                    <input type="file" name="profile_image" id="profileImageInput" class="hidden" accept="image/*" onchange="document.getElementById('profileImageForm').submit()">
                    <button type="button" onclick="document.getElementById('profileImageInput').click()" class="bg-white p-1.5 rounded-full shadow-md border border-gray-200 text-gray-500 hover:text-indigo-600 transition-colors focus:outline-none" title="Update Photo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </form>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500 text-sm mt-1">
                    @if(auth()->user()->profile_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            Profile Pending Approval
                        </span>
                    @elseif(auth()->user()->profile_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            Verified Alumni
                        </span>
                    @endif
                </p>
            </div>
        </div>
        @if(auth()->user()->profile_status === 'pending')
             <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Under Review</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Your profile is currently under review by the administrator. Some features might be limited until approval.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($missingEmploymentDetails)
        <div class="mb-6 bg-blue-50 border border-blue-200 p-4 rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                     <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-blue-800">Employment Details Missing</h3>
                    <p class="text-xs text-blue-700 mt-1">Add your work info to connect professionally with other alumni.</p>
                </div>
            </div>
            <a href="{{ route('profile.employment') }}" class="whitespace-nowrap text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex items-center">
                Update Now <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    @endif

    @if(($missingEnrollmentNo || $missingProofDocument) && !auth()->user()->isProfileApproved())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @if($missingEnrollmentNo)
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg flex flex-col">
                    <div class="flex items-center mb-3">
                         <div class="flex-shrink-0 bg-yellow-100 rounded-full p-2">
                             <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.747-2.146 2.454a4.532 4.532 0 00-1.658.74c-.496.347-1.196.967-1.196 2.37C9.92 11.5 10.92 12.5 12 12s2.08-1 2.08-1.436c0-1.403-.7-2.023-1.196-2.37a4.532 4.532 0 00-1.658-.74C10.896 6.747 10 5.884 10 5z"></path></svg>
                        </div>
                        <h3 class="ml-3 text-sm font-bold text-yellow-800">Enrollment No.</h3>
                    </div>
                    <p class="text-xs text-yellow-700 flex-grow mb-3">Help us identify you better.</p>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-yellow-700 hover:text-yellow-900 inline-flex items-center">
                        Update Profile <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            @endif

            @if($missingProofDocument)
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex flex-col">
                    <div class="flex items-center mb-3">
                         <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                             <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="ml-3 text-sm font-bold text-red-800">Proof Needed</h3>
                    </div>
                    <p class="text-xs text-red-700 flex-grow mb-3">Upload ID/Marksheet to complete verification.</p>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-red-700 hover:text-red-900 inline-flex items-center">
                        Upload Now <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
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
            <a href="{{ route('profile.employment') }}" class="flex flex-col items-center justify-center p-5 sm:p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 active:from-indigo-200 active:to-indigo-300 transition-all group touch-manipulation">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="font-medium text-sm sm:text-base text-indigo-900 text-center">Update Employment</p>
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