@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Events</h1>
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $eventDate = $event->event_start_date ?? $event->event_date ?? null;
                    $isUpcoming = $eventDate && $eventDate->isFuture();
                    $isPast = $eventDate && $eventDate->isPast();
                @endphp
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-semibold flex-1"><a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $event->title }}</a></h3>
                            @if($isUpcoming)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Upcoming</span>
                            @elseif($isPast)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">Past</span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            <strong>Date:</strong> {{ $eventDate ? $eventDate->format('M d, Y h:i A') : 'TBA' }}
                            @if($event->event_end_date)
                                <br><strong>End:</strong> {{ $event->event_end_date->format('M d, Y h:i A') }}
                            @endif
                        </p>
                        <p class="text-gray-500 dark:text-gray-400 mb-2"><strong>Venue:</strong> {{ $event->venue }}</p>
                        <div class="text-gray-500 dark:text-gray-400 mb-4 line-clamp-3">
                            {!! Str::limit(strip_tags($event->description), 150) !!}
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">View Details →</a>
                            @auth
                                @php
                                    $isRegistered = $event->registrations->isNotEmpty();
                                @endphp
                                @if($isRegistered)
                                    <span class="text-sm text-green-600 dark:text-green-400 font-semibold">Registered</span>
                                @elseif($isUpcoming)
                                    <a href="{{ route('events.register', $event->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-sm font-medium">Register</a>
                                @endif
                            @elseif($isUpcoming)
                                <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-sm font-medium">Login to Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $events->links() }}
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center border border-gray-200 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-lg">No events yet.</p>
        </div>
    @endif
</div>
@endsection




