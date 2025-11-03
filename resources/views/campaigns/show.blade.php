@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
<div class="bg-white rounded-lg shadow-md p-8">
    <div class="mb-6">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-64 object-cover rounded-lg mb-4">
        @endif
        <h1 class="text-3xl font-bold mb-2">{{ $campaign->title }}</h1>
        <div class="flex items-center text-gray-600 mb-4">
            <span class="mr-4">By {{ $campaign->creator->name }}</span>
            <span class="mr-4">•</span>
            <span>{{ $campaign->created_at->format('F d, Y') }}</span>
        </div>
    </div>

    <div class="mb-6">
        <p class="text-gray-700 whitespace-pre-line">{{ $campaign->description }}</p>
    </div>

    @if($campaign->start_date || $campaign->end_date)
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h2 class="text-xl font-semibold mb-3">Campaign Timeline</h2>
            @if($campaign->start_date)
                <p class="text-gray-700 mb-2"><strong>Start Date:</strong> {{ $campaign->start_date->format('F d, Y') }}</p>
            @endif
            @if($campaign->end_date)
                <p class="text-gray-700"><strong>End Date:</strong> {{ $campaign->end_date->format('F d, Y') }}</p>
            @endif
        </div>
    @endif

    @auth
        @if(auth()->user()->isAdmin() || auth()->id() === $campaign->created_by)
            <div class="mt-6 flex gap-4">
                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                    Edit Campaign
                </a>
            </div>
        @endif
    @endauth

    <div class="mt-6">
        <a href="{{ route('campaigns.index') }}" class="text-purple-600 hover:underline">← Back to Campaigns</a>
    </div>
</div>
@endsection


