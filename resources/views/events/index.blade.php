@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Events</h1>
    <p class="text-gray-600">Upcoming and past alumni events</p>
</div>

@auth
    @if(auth()->user()->isAdmin())
        <div class="mb-6">
            <a href="{{ route('admin.events.create') }}" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 inline-block">
                Create Event
            </a>
        </div>
    @endif
@endauth

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($events as $event)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                    <span class="text-white text-4xl font-bold">{{ substr($event->title, 0, 1) }}</span>
                </div>
            @endif
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($event->description, 150) }}</p>
                
                <div class="mb-4 space-y-2">
                    <p class="text-sm text-gray-700">
                        <strong>Date:</strong> {{ $event->event_date->format('F d, Y \a\t g:i A') }}
                    </p>
                    @if($event->venue)
                        <p class="text-sm text-gray-700"><strong>Venue:</strong> {{ $event->venue }}</p>
                    @endif
                    @if($event->location)
                        <p class="text-sm text-gray-700"><strong>Location:</strong> {{ $event->location }}</p>
                    @endif
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">By {{ $event->creator->name }}</span>
                    <a href="{{ route('events.show', $event) }}" class="text-purple-600 hover:underline">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 text-lg">No events found.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $events->links() }}
</div>
@endsection


