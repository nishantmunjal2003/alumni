@extends('layouts.app')

@section('title', 'Campaigns')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Campaigns</h1>
    <p class="text-gray-600">Stay updated with our latest campaigns</p>
</div>

@auth
    @if(auth()->user()->isAdmin())
        <div class="mb-6">
            <a href="{{ route('admin.campaigns.create') }}" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 inline-block">
                Create Campaign
            </a>
        </div>
    @endif
@endauth

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($campaigns as $campaign)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            @if($campaign->image)
                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center">
                    <span class="text-white text-4xl font-bold">{{ substr($campaign->title, 0, 1) }}</span>
                </div>
            @endif
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $campaign->title }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($campaign->description, 150) }}</p>
                
                @if($campaign->start_date || $campaign->end_date)
                    <div class="mb-4">
                        @if($campaign->start_date)
                            <p class="text-sm text-gray-500"><strong>Start:</strong> {{ $campaign->start_date->format('M d, Y') }}</p>
                        @endif
                        @if($campaign->end_date)
                            <p class="text-sm text-gray-500"><strong>End:</strong> {{ $campaign->end_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                @endif
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">By {{ $campaign->creator->name }}</span>
                    <a href="{{ route('campaigns.show', $campaign) }}" class="text-purple-600 hover:underline">
                        Read More →
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 text-lg">No campaigns found.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $campaigns->links() }}
</div>
@endsection


