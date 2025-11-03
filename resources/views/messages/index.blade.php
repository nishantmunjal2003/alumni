@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold">
                    Message Alumni
                </a>
            @endif
        </div>

        @if($conversations->count() > 0)
            <div class="space-y-2">
                @foreach($conversations as $conversation)
                    <a href="{{ route('messages.show', $conversation['user']) }}" 
                        class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                @if($conversation['user']->profile_image)
                                    <img src="{{ asset('storage/' . $conversation['user']->profile_image) }}" 
                                        alt="{{ $conversation['user']->name }}" 
                                        class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-purple-300 flex items-center justify-center">
                                        <span class="text-lg font-bold text-purple-700">{{ substr($conversation['user']->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="font-semibold text-gray-900">{{ $conversation['user']->name }}</h3>
                                        @if($conversation['user']->isAdmin())
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Admin</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">{{ \Illuminate\Support\Str::limit($conversation['latest_message']->message, 60) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $conversation['latest_message']->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($conversation['unread_count'] > 0)
                                <span class="px-3 py-1 bg-purple-600 text-white rounded-full text-xs font-bold">
                                    {{ $conversation['unread_count'] }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(auth()->user()->isAdmin())
                        Start a conversation with an alumnus from the user management page.
                    @else
                        Admins can send you messages that will appear here.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    // Update unread count badge when viewing messages index
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("messages.unread.count") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('a[href="{{ route("messages.index") }}"] span');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    badge.style.display = 'flex';
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection

