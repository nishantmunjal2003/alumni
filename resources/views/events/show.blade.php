@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($event->image)
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>
            <div class="space-y-2 mb-6">
                <p><strong>Date:</strong> {{ $event->event_date->format('F d, Y h:i A') }}</p>
                <p><strong>Venue:</strong> {{ $event->venue }}</p>
                <p><strong>Location:</strong> {{ $event->location }}</p>
            </div>
            <div class="prose mb-6">
                {!! nl2br(e($event->description)) !!}
            </div>
            @auth
                @if(!$isRegistered)
                    <a href="{{ route('events.register', $event->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Register for Event</a>
                @else
                    <p class="text-green-600 font-semibold">You are registered for this event!</p>
                    <a href="{{ route('events.fellows', $event->id) }}" class="text-indigo-600 hover:text-indigo-800">View Fellows →</a>
                @endif
            @else
                <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-indigo-600">login</a> to register for this event.</p>
            @endauth
        </div>
    </div>
</div>
@endsection




