@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">Events</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2"><a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">{{ $event->title }}</a></h3>
                    <p class="text-gray-600 mb-2">{{ $event->event_date->format('M d, Y h:i A') }}</p>
                    <p class="text-gray-500">{{ Str::limit($event->description, 100) }}</p>
                    <a href="{{ route('events.show', $event->id) }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">View Details →</a>
                </div>
            </div>
        @endforeach
    </div>
    {{ $events->links() }}
</div>
@endsection




