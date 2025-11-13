@extends('layouts.app')

@section('title', 'Campaigns')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">Campaigns</h1>
    @if($campaigns->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    @if($campaign->image)
                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2"><a href="{{ route('campaigns.show', $campaign->id) }}" class="text-indigo-600 hover:text-indigo-800">{{ $campaign->title }}</a></h3>
                        <p class="text-gray-500 text-sm mb-2">{{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d, Y') }}</p>
                        <p class="text-gray-600">{{ Str::limit($campaign->description, 100) }}</p>
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">View Details →</a>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $campaigns->links() }}
    @else
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <p class="text-gray-500 text-lg">No campaigns yet.</p>
        </div>
    @endif
</div>
@endsection




