@extends('layouts.app')

@section('title', 'Fundraising Campaigns')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900">Support Our Causes</h1>
        <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
            Join hands with fellow alumni to make a difference. Your support helps build a better future for our institution and students.
        </p>
    </div>

    @if($campaigns->count() > 0)
        <!-- Campaigns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                    <!-- Campaign Image -->
                    @if($campaign->image)
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" 
                                class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4">
                                @if($campaign->end_date >= now())
                                    <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                                @else
                                    <span class="bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Ended</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Campaign Content -->
                    <div class="p-6 flex-grow flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('campaigns.show', $campaign->id) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $campaign->title }}
                            </a>
                        </h3>
                        
                        <p class="text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d, Y') }}
                        </p>

                        <p class="text-gray-600 mb-4 flex-grow line-clamp-3">
                            {{ Str::limit($campaign->description, 150) }}
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('campaigns.show', $campaign->id) }}" 
                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors">
                                Learn More & Support
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Active Campaigns</h3>
            <p class="mt-2 text-sm text-gray-500">There are currently no active fundraising campaigns. Check back soon!</p>
        </div>
    @endif
</div>
@endsection
