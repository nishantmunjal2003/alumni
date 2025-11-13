@extends('layouts.app')

@section('title', $alumni->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start space-x-6">
            <img src="{{ $alumni->profile_image ? asset('storage/' . $alumni->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($alumni->name) }}" alt="{{ $alumni->name }}" class="w-32 h-32 rounded-full">
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $alumni->name }}</h1>
                @if($alumni->current_position)
                    <p class="text-xl text-gray-600 mt-2">{{ $alumni->current_position }}</p>
                @endif
                @if($alumni->company)
                    <p class="text-lg text-gray-500">{{ $alumni->company }}</p>
                @endif
                @if($alumni->email)
                    <p class="text-gray-600 mt-2">{{ $alumni->email }}</p>
                @endif
                @if(auth()->id() == $alumni->id)
                    <a href="{{ route('alumni.edit', $alumni->id) }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Edit Profile</a>
                @endif
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4">
            @if($alumni->phone)
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium">{{ $alumni->phone }}</p>
                </div>
            @endif
            @if($alumni->graduation_year)
                <div>
                    <p class="text-sm text-gray-500">Graduation Year</p>
                    <p class="font-medium">{{ $alumni->graduation_year }}</p>
                </div>
            @endif
            @if($alumni->major)
                <div>
                    <p class="text-sm text-gray-500">Major</p>
                    <p class="font-medium">{{ $alumni->major }}</p>
                </div>
            @endif
            @if($alumni->linkedin_url)
                <div>
                    <p class="text-sm text-gray-500">LinkedIn</p>
                    <a href="{{ $alumni->linkedin_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">View Profile</a>
                </div>
            @endif
        </div>

        @if($alumni->bio)
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Bio</h2>
                <p class="text-gray-700">{{ $alumni->bio }}</p>
            </div>
        @endif

        @if(auth()->check() && auth()->id() != $alumni->id)
            <div class="mt-6">
                <a href="{{ route('messages.show', $alumni->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Send Message</a>
            </div>
        @endif
    </div>
</div>
@endsection




