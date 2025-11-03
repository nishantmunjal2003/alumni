@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header with Image and Title -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6 lg:p-8">
        <!-- Image (left side on desktop) -->
        @if($event->image)
        <div class="lg:col-span-1">
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 lg:h-full object-cover rounded-lg shadow-md">
        </div>
        @endif
        
        <!-- Title and Meta Info (right side) -->
        <div class="{{ $event->image ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <h1 class="text-3xl font-bold mb-3 text-gray-900">{{ $event->title }}</h1>
            <div class="flex items-center text-gray-600 mb-4 flex-wrap gap-2">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    By {{ $event->creator->name }}
                </span>
                <span class="text-gray-400">•</span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $event->created_at->format('F d, Y') }}
                </span>
            </div>
            
            <!-- Compact Event Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-purple-600 font-semibold uppercase">Date & Time</p>
                        <p class="text-sm font-medium text-gray-900">{{ $event->event_date->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                
                @if($event->venue)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-purple-600 font-semibold uppercase">Venue</p>
                        <p class="text-sm font-medium text-gray-900">{{ $event->venue }}</p>
                    </div>
                </div>
                @endif
                
                @if($event->location)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-purple-600 font-semibold uppercase">Location</p>
                        <p class="text-sm font-medium text-gray-900">{{ $event->location }}</p>
                    </div>
                </div>
                @endif
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs text-purple-600 font-semibold uppercase">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($event->status == 'published') bg-green-100 text-green-800 
                            @elseif($event->status == 'cancelled') bg-red-100 text-red-800 
                            @elseif($event->status == 'completed') bg-gray-100 text-gray-800 
                            @else bg-yellow-100 text-yellow-800 
                            @endif">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="px-6 lg:px-8 pb-6 lg:pb-8">
        <div class="border-t border-gray-200 pt-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">About This Event</h2>
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $event->description }}</p>
            </div>
        </div>

        <!-- Registration Status -->
        @auth
            @php
                $userRegistration = \App\Models\EventRegistration::where('event_id', $event->id)
                    ->where('user_id', auth()->id())
                    ->first();
            @endphp
            
            @if($userRegistration)
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-green-800 font-semibold">You are registered for this event!</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('events.fellows', $event) }}" class="text-green-700 hover:text-green-800 font-medium text-sm">
                                View Registered Fellows
                            </a>
                            <a href="{{ route('events.registrations.edit', [$event, $userRegistration]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold">
                                Update Registration
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6">
                    <a href="{{ route('events.register', $event) }}" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 font-semibold">
                        Register for this Event
                    </a>
                </div>
            @endif
        @else
            <div class="mt-6">
                <a href="{{ route('login') }}" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 font-semibold">
                    Login to Register
                </a>
            </div>
        @endauth

        <!-- Admin: View Registrations and Resend Email -->
        @auth
            @if(auth()->user()->isAdmin() || auth()->id() === $event->created_by)
                @php
                    $registrations = \App\Models\EventRegistration::where('event_id', $event->id)
                        ->with(['user', 'friends'])
                        ->get();
                    $registrationCount = $registrations->count();
                @endphp
                
                <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Event Registrations ({{ $registrationCount }})</h3>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.events.resend-invites', $event) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure you want to resend invitations to all invited batches?')" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-md font-semibold text-sm">
                                    Resend Invitations to All
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if($registrationCount > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-purple-600 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Batch</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Company</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">City</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Arrival Date</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Travel Mode</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Needs Stay</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($registrations as $registration)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm">{{ $registration->user->name }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $registration->user->email }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $registration->user->graduation_year ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $registration->user->company ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $registration->coming_from_city ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $registration->arrival_date ? $registration->arrival_date->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ ucfirst($registration->travel_mode ?? 'N/A') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($registration->needs_stay)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Yes</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">No</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('events.registrations.edit', [$event, $registration]) }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-4">No registrations yet.</p>
                    @endif
                </div>
            @endif
        @endauth

        <!-- Actions -->
        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('events.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>

            @auth
                @if(auth()->user()->isAdmin() || auth()->id() === $event->created_by)
                    <a href="{{ route('admin.events.edit', $event) }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                        Edit Event
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

