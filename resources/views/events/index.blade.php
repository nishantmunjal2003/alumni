@extends('layouts.app')

@section('title', 'Events')

@section('content')
<style>
    /* Force light mode - override any dark mode styles */
    html, body {
        color-scheme: light !important;
    }
    html.dark, body.dark {
        background-color: #f9fafb !important;
    }
    html.dark *, body.dark * {
        --tw-bg-opacity: 1;
    }
</style>
<script>
    // Force light mode on events page
    (function() {
        let isProcessing = false;
        
        // Remove dark class immediately
        const removeDarkMode = function() {
            if (isProcessing) {
                return;
            }
            isProcessing = true;
            
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
            if (document.body.classList.contains('dark')) {
                document.body.classList.remove('dark');
            }
            
            // Use requestAnimationFrame to prevent blocking
            requestAnimationFrame(() => {
                isProcessing = false;
            });
        };
        
        // Remove on DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeDarkMode);
        } else {
            removeDarkMode();
        }
        
        // Watch for any attempts to add dark class with debouncing
        let timeoutId = null;
        const observer = new MutationObserver(function(mutations) {
            // Debounce the removal to prevent excessive calls
            if (timeoutId) {
                clearTimeout(timeoutId);
            }
            timeoutId = setTimeout(removeDarkMode, 50);
        });
        
        observer.observe(document.documentElement, { 
            attributes: true, 
            attributeFilter: ['class'],
            attributeOldValue: false
        });
        observer.observe(document.body, { 
            attributes: true, 
            attributeFilter: ['class'],
            attributeOldValue: false
        });
    })();
</script>
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Events</h1>
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $eventDate = $event->event_start_date ?? $event->event_date ?? null;
                    $isUpcoming = $eventDate && $eventDate->isFuture();
                    $isPast = $eventDate && $eventDate->isPast();
                @endphp
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-semibold flex-1"><a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">{{ $event->title }}</a></h3>
                            @if($isUpcoming)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Upcoming</span>
                            @elseif($isPast)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Past</span>
                            @endif
                        </div>
                        <p class="text-gray-600 mb-2">
                            <strong>Date:</strong> {{ $eventDate ? $eventDate->format('M d, Y h:i A') : 'TBA' }}
                            @if($event->event_end_date)
                                <br><strong>End:</strong> {{ $event->event_end_date->format('M d, Y h:i A') }}
                            @endif
                        </p>
                        <p class="text-gray-500 mb-2"><strong>Venue:</strong> {{ $event->venue }}</p>
                        <div class="text-gray-500 mb-4 line-clamp-3">
                            {!! Str::limit(strip_tags(preg_replace('/(<style\b[^>]*>.*?<\/style>|<script\b[^>]*>.*?<\/script>)/is', '', $event->description)), 150) !!}
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">View Details →</a>
                            @auth
                                @php
                                    $isRegistered = auth()->check() && $event->registrations->where('user_id', auth()->id())->isNotEmpty();
                                @endphp
                                @if($isRegistered)
                                    <span class="text-sm text-green-600 font-semibold">Registered</span>
                                @elseif($isUpcoming)
                                    <a href="{{ route('events.register', $event->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">Register</a>
                                @endif
                            @elseif($isUpcoming)
                                <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">Login to Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $events->links() }}
    @else
        <div class="bg-white shadow rounded-lg p-12 text-center border border-gray-200">
            <p class="text-gray-500 text-lg">No events yet.</p>
        </div>
    @endif
</div>
@endsection




