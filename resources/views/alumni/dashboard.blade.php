@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Batchmates</h2>
            @if($batchmates->count() > 0)
                <div class="space-y-3">
                    @foreach($batchmates as $batchmate)
                        <div class="flex items-center space-x-3">
                            <img src="{{ $batchmate->profile_image ? asset('storage/' . $batchmate->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($batchmate->name) }}" alt="{{ $batchmate->name }}" class="w-10 h-10 rounded-full">
                            <div>
                                <a href="{{ route('alumni.show', $batchmate->id) }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ $batchmate->name }}</a>
                                @if($batchmate->current_position)
                                    <p class="text-sm text-gray-500">{{ $batchmate->current_position }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('alumni.index', ['graduation_year' => auth()->user()->graduation_year]) }}" class="mt-4 text-indigo-600 hover:text-indigo-800 text-sm">View all batchmates →</a>
            @else
                <p class="text-gray-500">No batchmates found.</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Upcoming Events</h2>
            @if($upcomingEvents->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingEvents as $event)
                        <div>
                            <a href="{{ route('events.show', $event->id) }}" class="font-medium text-indigo-600 hover:text-indigo-800">{{ $event->title }}</a>
                            <p class="text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('events.index') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 text-sm">View all events →</a>
            @else
                <p class="text-gray-500">No upcoming events.</p>
            @endif
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('alumni.edit', auth()->id()) }}" class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center">
                <p class="font-medium text-indigo-900">Edit Profile</p>
            </a>
            <a href="{{ route('alumni.index') }}" class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center">
                <p class="font-medium text-indigo-900">Browse Alumni</p>
            </a>
            <a href="{{ route('messages.index') }}" class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center">
                <p class="font-medium text-indigo-900">Messages</p>
            </a>
        </div>
    </div>
</div>
@endsection




