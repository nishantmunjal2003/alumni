@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" 
                        class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-purple-300 flex items-center justify-center">
                        <span class="text-lg font-bold text-purple-700">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    @if($user->isAdmin())
                        <span class="text-sm text-yellow-600 font-semibold">Administrator</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('messages.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold">
                ← Back to Messages
            </a>
        </div>

        <!-- Messages -->
        <div class="mb-6 space-y-4 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded-lg">
            @foreach($messages as $message)
                <div class="flex {{ $message->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->from_user_id === auth()->id() ? 'bg-purple-600 text-white' : 'bg-white border border-gray-200 text-gray-900' }}">
                        <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                        <p class="text-xs mt-2 opacity-75">{{ $message->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Send Message Form -->
        <form action="{{ route('messages.store', $user) }}" method="POST" class="border-t border-gray-200 pt-4">
            @csrf
            <div class="flex space-x-2">
                <textarea name="message" rows="3" required placeholder="Type your message..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 font-semibold self-end">
                    Send
                </button>
            </div>
            @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Update unread count badge immediately after page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Small delay to ensure backend has processed the read status
        setTimeout(function() {
            if (typeof updateMessageBadge === 'function') {
                updateMessageBadge();
            } else {
                // Fallback if badge-updater.js hasn't loaded
                fetch('{{ route("messages.unread.count") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateMessageBadgeInHeader(data.count);
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                });
            }
        }, 100);
    });

    function updateMessageBadgeInHeader(count) {
        const link = document.querySelector('a[href*="/messages"]');
        if (!link) return;

        let badge = link.querySelector('span.bg-red-500, span.absolute.bg-red-500');
        
        if (count > 0) {
            if (badge) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else {
                // Create badge if it doesn't exist
                badge = document.createElement('span');
                badge.className = 'absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center';
                badge.textContent = count > 9 ? '9+' : count;
                link.style.position = 'relative';
                link.appendChild(badge);
            }
        } else {
            // Hide badge if no unread messages
            if (badge) {
                badge.style.display = 'none';
            }
        }
    }
</script>
@endsection

