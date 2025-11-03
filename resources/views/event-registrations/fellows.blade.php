@extends('layouts.app')

@section('title', 'Registered Fellows')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Registered Fellows</h1>
                <p class="text-gray-600 mt-1">{{ $event->title }}</p>
            </div>
            <a href="{{ route('events.show', $event) }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                Back to Event
            </a>
        </div>

        @if(isset($message))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-800">{{ $message }}</p>
            </div>
        @elseif($fellows->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($fellows as $fellow)
                    <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-6 border border-purple-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            @if($fellow->user->profile_image)
                                <img src="{{ asset('storage/' . $fellow->user->profile_image) }}" alt="{{ $fellow->user->name }}" 
                                    class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-purple-300">
                            @else
                                <div class="w-16 h-16 rounded-full bg-purple-300 flex items-center justify-center mr-4 border-2 border-purple-400">
                                    <span class="text-2xl font-bold text-purple-700">{{ substr($fellow->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">{{ $fellow->user->name }}</h3>
                                @if($fellow->user->company)
                                    <p class="text-sm text-gray-600">{{ $fellow->user->company }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            @if($fellow->coming_from_city)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    Coming from: {{ $fellow->coming_from_city }}
                                </div>
                            @endif

                            @if($fellow->arrival_date)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Arrival: {{ $fellow->arrival_date->format('M d, Y') }}
                                </div>
                            @endif

                            @if($fellow->travel_mode)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Travel: {{ ucfirst($fellow->travel_mode) }}
                                </div>
                            @endif

                            @if($fellow->needs_stay)
                                <div class="flex items-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Needs Stay</span>
                                </div>
                            @endif

                            @if($fellow->coming_with_family)
                                <div class="flex items-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Coming with Family</span>
                                </div>
                            @endif
                        </div>

                        @if($fellow->friends->count() > 0)
                            <div class="mt-4 pt-4 border-t border-purple-200">
                                <p class="text-xs font-semibold text-purple-700 mb-2">Invited Friends:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($fellow->friends as $friend)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">{{ $friend->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No fellows registered yet</h3>
                <p class="mt-1 text-sm text-gray-500">No alumni from your batch have registered for this event yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection


