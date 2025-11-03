@extends('layouts.app')

@section('title', $alumni->name)

@section('content')
<div class="bg-white rounded-lg shadow-md p-8">
    <div class="flex flex-col md:flex-row items-start md:items-center mb-6">
        @if($alumni->profile_image)
            <img src="{{ asset('storage/' . $alumni->profile_image) }}" alt="{{ $alumni->name }}" class="w-32 h-32 rounded-full object-cover mb-4 md:mb-0 md:mr-6">
        @else
            <div class="w-32 h-32 rounded-full bg-purple-500 flex items-center justify-center text-white text-4xl font-bold mb-4 md:mb-0 md:mr-6">
                {{ substr($alumni->name, 0, 1) }}
            </div>
        @endif
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $alumni->name }}</h1>
            @if($alumni->graduation_year)
                <p class="text-gray-600 text-lg mb-2">Class of {{ $alumni->graduation_year }}</p>
            @endif
            @if($alumni->major)
                <p class="text-gray-600 mb-2"><strong>Major:</strong> {{ $alumni->major }}</p>
            @endif
        </div>
    </div>

    <div class="border-t pt-6 mt-6">
        @if($alumni->current_position || $alumni->company)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-4">Professional Information</h2>
                @if($alumni->current_position)
                    <p class="text-gray-700 mb-2"><strong>Position:</strong> {{ $alumni->current_position }}</p>
                @endif
                @if($alumni->company)
                    <p class="text-gray-700 mb-2"><strong>Company:</strong> {{ $alumni->company }}</p>
                @endif
            </div>
        @endif

        @if($alumni->bio)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-4">About</h2>
                <p class="text-gray-700">{{ $alumni->bio }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            @if($alumni->email)
                <div>
                    <strong>Email:</strong> <a href="mailto:{{ $alumni->email }}" class="text-purple-600 hover:underline">{{ $alumni->email }}</a>
                </div>
            @endif
            
            @if($alumni->phone)
                <div>
                    <strong>Phone:</strong> {{ $alumni->phone }}
                </div>
            @endif
            
            @if($alumni->linkedin_url)
                <div>
                    <strong>LinkedIn:</strong> <a href="{{ $alumni->linkedin_url }}" target="_blank" class="text-purple-600 hover:underline">View Profile</a>
                </div>
            @endif
        </div>

        @auth
            @if(auth()->id() === $alumni->id)
                <div class="mt-6">
                    <a href="{{ route('alumni.edit', $alumni) }}" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 inline-block">
                        Edit Profile
                    </a>
                </div>
            @endif
        @endauth
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('alumni.index') }}" class="text-purple-600 hover:underline">← Back to Directory</a>
</div>
@endsection

