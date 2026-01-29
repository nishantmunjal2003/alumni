@extends('layouts.app')

@section('title', 'Conversation with ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto h-[85vh] flex flex-col box-border">
    <div class="bg-white shadow-xl rounded-2xl flex-1 flex flex-col overflow-hidden border border-gray-200 box-border w-full">
        <!-- Header -->
        <div class="p-4 border-b border-gray-100 bg-white flex items-center justify-between shrink-0 h-20">
            <div class="flex items-center gap-3">
                <a href="{{ route('alumni.show', $user->id) }}" class="flex items-center gap-3 group">
                    <div class="relative">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 group-hover:border-indigo-500 transition-colors">
                        @else
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200 group-hover:border-indigo-500 transition-colors text-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</h2>
                    </div>
                </a>
            </div>
            <a href="{{ route('messages.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium flex items-center gap-1 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 w-full" id="messages-container">
            @forelse($messages as $index => $message)
                @php
                    $isMine = $message->from_user_id == auth()->id();
                    $previousMessage = $messages[$index - 1] ?? null;
                    $showTime = !$previousMessage || $message->created_at->diffInMinutes($previousMessage->created_at) > 15;
                @endphp

                @if($showTime)
                    <div class="flex justify-center my-4 w-full">
                        <span class="text-xs font-semibold text-gray-400 bg-gray-200/50 px-3 py-1 rounded-full">{{ $message->created_at->format('M d, h:i A') }}</span>
                    </div>
                @endif

                <div class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }} group animate-fade-in-up">
                    <div class="max-w-[80%] md:max-w-[70%] flex gap-3 {{ $isMine ? 'flex-row-reverse' : 'flex-row' }}">
                        
                        <!-- Avatar (Small) -->
                        <div class="shrink-0 self-end mb-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($isMine)
                                @if(auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="w-8 h-8 rounded-full object-cover shadow-sm bg-white">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs text-indigo-600 font-bold border border-indigo-200">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                @endif
                            @else
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-8 h-8 rounded-full object-cover shadow-sm bg-white">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs text-indigo-600 font-bold border border-indigo-200">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                            @endif
                        </div>

                        <!-- Bubble -->
                        <div class="rounded-2xl px-5 py-3 shadow-sm text-[15px] leading-relaxed relative
                            {{ $isMine 
                                ? 'bg-indigo-600 text-white rounded-br-none' 
                                : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none' 
                            }}">
                            <p class="break-words">{!! nl2br(e($message->message)) !!}</p>
                            <div class="text-[10px] mt-1.5 flex justify-end gap-1 items-center {{ $isMine ? 'text-indigo-200' : 'text-gray-400' }}">
                                {{ $message->created_at->format('h:i A') }}
                                @if($isMine)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex h-full w-full flex-col items-center justify-center text-gray-400 text-center p-8">
                    <div class="bg-white p-6 rounded-full mb-4 shadow-sm">
                        <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600">No messages yet</h3>
                    <p class="text-sm text-gray-500 mt-1">Start the conversation by saying hi!</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-100 shrink-0 w-full">
            <form method="POST" action="{{ route('messages.store', $user->id) }}" id="messageForm" class="relative">
                @csrf
                <div class="flex items-end gap-3 bg-white border border-gray-200 rounded-2xl p-2 pl-4 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all shadow-sm">
                    <textarea name="message" id="messageInput" rows="1" class="flex-1 bg-transparent border-none focus:ring-0 resize-none max-h-32 py-3 px-0 text-base text-gray-800 placeholder-gray-400" placeholder="Type your message..." required style="min-height: 48px;"></textarea>
                    <button type="submit" class="bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all shrink-0 mb-0.5 shadow-md group">
                        <svg class="w-5 h-5 transform rotate-90 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-scroll to bottom
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        // Auto-resize textarea
        const textarea = document.getElementById('messageInput');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            // Handle Enter key to submit (Shift+Enter for newline)
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim() !== '') {
                        document.getElementById('messageForm').submit();
                    }
                }
            });
        }
    });
</script>
@endsection






