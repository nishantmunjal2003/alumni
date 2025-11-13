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
                    <p class="text-green-600 dark:text-green-400 font-semibold mb-2">You are registered for this event!</p>
                    <a href="{{ route('events.fellows', $event->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">View Fellows →</a>
                @endif
            @else
                <p class="text-gray-600 dark:text-gray-400">Please <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400">login</a> to register for this event.</p>
            @endauth
        </div>
    </div>
</div>
@endsection




