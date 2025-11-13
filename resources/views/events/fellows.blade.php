@extends('layouts.app')

@section('title', 'Fellows - ' . $event->title)

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">Fellows Attending {{ $event->title }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($registrations as $registration)
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $registration->user->profile_image ? asset('storage/' . $registration->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($registration->user->name) }}" alt="{{ $registration->user->name }}" class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="font-semibold">{{ $registration->user->name }}</h3>
                        @if($registration->coming_from_city)
                            <p class="text-sm text-gray-600">From {{ $registration->coming_from_city }}</p>
                        @endif
                    </div>
                </div>
                @if($registration->photos->count() > 0)
                    <div class="grid grid-cols-2 gap-2 mt-4">
                        @foreach($registration->photos->take(4) as $photo)
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Photo" class="w-full h-24 object-cover rounded">
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection




