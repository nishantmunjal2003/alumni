@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-4">{{ $campaign->title }}</h1>
            <p class="text-gray-600 mb-6">{{ $campaign->start_date->format('F d, Y') }} - {{ $campaign->end_date->format('F d, Y') }}</p>
            <div class="prose">
                {!! nl2br(e($campaign->description)) !!}
            </div>
        </div>
    </div>
</div>
@endsection




