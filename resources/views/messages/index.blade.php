@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Messages</h1>
    <div class="bg-white shadow rounded-lg">
        @if($conversations->count() > 0)
            <div class="divide-y">
                @foreach($conversations as $conversation)
                    <a href="{{ route('messages.show', $conversation['user']->id) }}" class="block p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if($conversation['user']->profile_image)
                                    <img src="{{ $conversation['user']->profile_image_url }}" alt="{{ $conversation['user']->name }}" class="w-12 h-12 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center hidden">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-xs">{{ getUserInitials($conversation['user']->name) }}</span>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-xs">{{ getUserInitials($conversation['user']->name) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $conversation['user']->name }}</p>
                                    <p class="text-sm text-gray-600">{{ Str::limit($conversation['last_message']->message, 50) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $conversation['last_message']->created_at->diffForHumans() }}</p>
                                @if($conversation['unread_count'] > 0)
                                    <span class="inline-block mt-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $conversation['unread_count'] }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="p-6 text-gray-500 text-center">No messages yet.</p>
        @endif
    </div>
</div>
@endsection






