@extends('layouts.app')

@section('title', 'Conversation with ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
        </div>
        <div class="p-4 space-y-4" style="max-height: 500px; overflow-y: auto;">
            @foreach($messages as $message)
                <div class="flex {{ $message->from_user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->from_user_id == auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        <p>{{ $message->message }}</p>
                        <p class="text-xs mt-1 opacity-75">{{ $message->created_at->format('M d, h:i A') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="p-4 border-t">
            <form method="POST" action="{{ route('messages.store', $user->id) }}">
                @csrf
                <div class="flex space-x-2">
                    <textarea name="message" rows="2" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Type your message..." required></textarea>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection






