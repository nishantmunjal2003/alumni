@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        @if($event->image)
            <div class="w-full overflow-hidden bg-gray-100 dark:bg-gray-900">
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-auto max-h-96 object-cover">
            </div>
        @endif
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">{{ $event->title }}</h1>
            <div class="space-y-2 mb-6">
                <p class="text-gray-900 dark:text-white"><strong class="text-gray-700 dark:text-gray-300">Start Date:</strong> {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('F d, Y h:i A') }}</p>
                @if($event->event_end_date)
                    <p class="text-gray-900 dark:text-white"><strong class="text-gray-700 dark:text-gray-300">End Date:</strong> {{ $event->event_end_date->format('F d, Y h:i A') }}</p>
                @endif
                <p class="text-gray-900 dark:text-white"><strong class="text-gray-700 dark:text-gray-300">Venue:</strong> {{ $event->venue }}</p>
                @if($event->google_maps_link)
                    <p class="text-gray-900 dark:text-white"><strong class="text-gray-700 dark:text-gray-300">Location:</strong> <a href="{{ $event->google_maps_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">View on Google Maps</a></p>
                @elseif(isset($event->location))
                    <p class="text-gray-900 dark:text-white"><strong class="text-gray-700 dark:text-gray-300">Location:</strong> {{ $event->location }}</p>
                @endif
            </div>
            <div class="prose dark:prose-invert mb-6 max-w-none">
                {!! $event->description !!}
            </div>
            @auth
                @if(!$isRegistered)
                    <a href="{{ route('events.register', $event->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors inline-block">Register for Event</a>
                @else
                    <div class="space-y-3">
                        <p class="text-green-600 dark:text-green-400 font-semibold">You are registered for this event!</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('events.registrations.edit', [$event->id, $registration->id]) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Registration
                            </a>
                            <a href="{{ route('events.fellows', $event->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 inline-flex items-center gap-2 px-6 py-2 border border-indigo-600 dark:border-indigo-400 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                View Fellows
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <p class="text-gray-600 dark:text-gray-400">Please <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400">login</a> to register for this event.</p>
            @endauth
        </div>
    </div>
</div>
@endsection




