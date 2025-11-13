@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Here's what's happening in your alumni network</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Batchmates</h2>
                @if($batchmates->count() > 0)
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $batchmates->count() }}</span>
                @endif
            </div>
            @if($batchmates->count() > 0)
                <div class="space-y-4">
                    @foreach($batchmates as $batchmate)
                        <a href="{{ route('alumni.show', $batchmate->id) }}" class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                            <img src="{{ $batchmate->profile_image ? asset('storage/' . $batchmate->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($batchmate->name) }}" alt="{{ $batchmate->name }}" class="w-12 h-12 rounded-full ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-indigo-500 transition-all">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $batchmate->name }}</p>
                                @if($batchmate->current_position)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $batchmate->current_position }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('alumni.index', ['graduation_year' => auth()->user()->graduation_year]) }}" class="mt-6 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                    View all batchmates
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No batchmates found.</p>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Upcoming Events</h2>
                @if($upcomingEvents->count() > 0)
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $upcomingEvents->count() }}</span>
                @endif
            </div>
            @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                        <a href="{{ route('events.show', $event->id) }}" class="block p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                            <p class="font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $event->title }}</p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ ($event->event_start_date ?? $event->event_date ?? null)?->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('events.index') }}" class="mt-6 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                    View all events
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400 mb-2">No upcoming events.</p>
                    <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">View all events →</a>
                    @if(auth()->user()->hasRole('admin'))
                        <span class="text-gray-400 dark:text-gray-500 mx-2">|</span>
                        <a href="{{ route('admin.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Manage events →</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border border-indigo-200 dark:border-indigo-800 hover:from-indigo-100 hover:to-indigo-200 dark:hover:from-indigo-800/30 dark:hover:to-indigo-700/30 transition-all group">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="font-medium text-indigo-900 dark:text-indigo-100">Edit Profile</p>
            </a>
            <a href="{{ route('alumni.index') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border border-indigo-200 dark:border-indigo-800 hover:from-indigo-100 hover:to-indigo-200 dark:hover:from-indigo-800/30 dark:hover:to-indigo-700/30 transition-all group">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="font-medium text-indigo-900 dark:text-indigo-100">Alumni Directory</p>
            </a>
            <a href="{{ route('messages.index') }}" class="flex flex-col items-center justify-center p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 border border-indigo-200 dark:border-indigo-800 hover:from-indigo-100 hover:to-indigo-200 dark:hover:from-indigo-800/30 dark:hover:to-indigo-700/30 transition-all group">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="font-medium text-indigo-900 dark:text-indigo-100">Messages</p>
            </a>
        </div>
    </div>
</div>
@endsection




