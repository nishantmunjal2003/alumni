@extends('layouts.app')

@section('title', $alumni->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start space-x-6">
            @if($alumni->profile_image)
                <img src="{{ $alumni->profile_image_url }}" alt="{{ $alumni->name }}" class="w-32 h-32 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center hidden">
                    <span class="text-indigo-600 font-semibold text-2xl">{{ getUserInitials($alumni->name) }}</span>
                </div>
            @else
                <div class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-2xl">{{ getUserInitials($alumni->name) }}</span>
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $alumni->name }}</h1>
                @if($alumni->passing_year || $alumni->course)
                    <p class="text-lg text-gray-600 mt-2">
                        @if($alumni->passing_year && $alumni->course)
                            Batch {{ $alumni->passing_year }} • {{ $alumni->course }}
                        @elseif($alumni->passing_year)
                            Batch {{ $alumni->passing_year }}
                        @elseif($alumni->course)
                            {{ $alumni->course }}
                        @endif
                    </p>
                @endif
                @if($alumni->current_position)
                    <p class="text-xl text-gray-600 mt-2">{{ $alumni->current_position }}</p>
                @endif
                @if($alumni->company)
                    <p class="text-lg text-gray-500">{{ $alumni->company }}</p>
                @endif
                @if(auth()->id() == $alumni->id)
                    <a href="{{ route('alumni.edit', $alumni->id) }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Edit Profile</a>
                @endif
            </div>
        </div>

        <!-- Action Buttons: Message and Email -->
        @if(auth()->check() && auth()->id() != $alumni->id)
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('messages.show', $alumni->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Send Message
                </a>
                @if($alumni->email)
                    <a href="mailto:{{ $alumni->email }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $alumni->email }}
                    </a>
                @endif
            </div>
        @elseif(!auth()->check())
            <div class="mt-6">
                <p class="text-gray-600 mb-2">Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">login</a> to contact this alumni.</p>
                @if($alumni->email)
                    <a href="mailto:{{ $alumni->email }}" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $alumni->email }}
                    </a>
                @endif
            </div>
        @endif

        <div class="mt-6 space-y-6">
            <!-- Employment Details Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Employment Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($alumni->company)
                        <div>
                            <p class="text-sm text-gray-500">Current Employer</p>
                            <p class="font-medium">{{ $alumni->company }}</p>
                        </div>
                    @endif
                    @if($alumni->designation)
                        <div>
                            <p class="text-sm text-gray-500">Designation</p>
                            <p class="font-medium">{{ $alumni->designation }}</p>
                        </div>
                    @endif
                    @if($alumni->current_position)
                        <div>
                            <p class="text-sm text-gray-500">Current Position</p>
                            <p class="font-medium">{{ $alumni->current_position }}</p>
                        </div>
                    @endif
                    @if($alumni->employment_type)
                        <div>
                            <p class="text-sm text-gray-500">Employment Type</p>
                            <p class="font-medium">{{ $alumni->employment_type }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Current Address Section -->
            @if($alumni->employment_address || $alumni->employment_city || $alumni->employment_state || $alumni->employment_pincode)
                <div>
                    <h2 class="text-xl font-semibold mb-4">Current Address</h2>
                    <div class="text-gray-700">
                        @if($alumni->employment_address)
                            <p>{{ $alumni->employment_address }}</p>
                        @endif
                        @if($alumni->employment_city || $alumni->employment_state || $alumni->employment_pincode)
                            <p>
                                @if($alumni->employment_city){{ $alumni->employment_city }}@endif
                                @if($alumni->employment_city && $alumni->employment_state), @endif
                                @if($alumni->employment_state){{ $alumni->employment_state }}@endif
                                @if($alumni->employment_pincode) - {{ $alumni->employment_pincode }}@endif
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Contact & Other Details -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Contact & Other Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($alumni->email)
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">
                                <a href="mailto:{{ $alumni->email }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">{{ $alumni->email }}</a>
                            </p>
                        </div>
                    @endif
                    @if($alumni->phone && ($alumni->is_phone_public || auth()->id() == $alumni->id || auth()->user()->hasAnyRole(['admin', 'manager', 'DataEntry'])))
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">
                                <a href="tel:{{ $alumni->phone }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">{{ $alumni->phone }}</a>
                                @if(!$alumni->is_phone_public)
                                    <span class="text-xs text-gray-500 italic ml-2">(Private)</span>
                                @endif
                            </p>
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
                            <a href="{{ $alumni->linkedin_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline">View Profile</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($alumni->bio)
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Bio</h2>
                <p class="text-gray-700">{{ $alumni->bio }}</p>
            </div>
        @endif
    </div>
</div>
@endsection




