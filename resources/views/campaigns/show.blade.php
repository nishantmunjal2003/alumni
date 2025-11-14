@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Campaign Header with Image -->
    @if($campaign->image)
        <div class="relative rounded-lg overflow-hidden shadow-lg">
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" 
                class="w-full h-64 sm:h-96 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ $campaign->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-white">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm sm:text-base">{{ $campaign->start_date->format('F d, Y') }} - {{ $campaign->end_date->format('F d, Y') }}</span>
                    </div>
                    @if($campaign->end_date >= now())
                        <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Active Campaign</span>
                    @else
                        <span class="bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Campaign Ended</span>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-8 sm:p-12 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">{{ $campaign->title }}</h1>
            <div class="flex flex-wrap items-center justify-center gap-4 text-white">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $campaign->start_date->format('F d, Y') }} - {{ $campaign->end_date->format('F d, Y') }}</span>
                </div>
                @if($campaign->end_date >= now())
                    <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Active Campaign</span>
                @else
                    <span class="bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Campaign Ended</span>
                @endif
            </div>
        </div>
    @endif

    <!-- Campaign Content -->
    <div class="bg-white shadow rounded-lg p-6 sm:p-8">
        <div class="prose max-w-none">
            <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $campaign->description }}</div>
        </div>
    </div>

    <!-- Donate Section -->
    @if($campaign->donation_link && $campaign->end_date >= now())
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 sm:p-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Support This Cause</h2>
            <p class="text-indigo-100 mb-6 max-w-2xl mx-auto">
                Your contribution makes a real difference. Every donation helps us move closer to our goal.
            </p>
            <a href="{{ $campaign->donation_link }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-bold text-lg rounded-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Donate Now
            </a>
            <p class="mt-4 text-xs text-indigo-200">You will be redirected to our secure payment gateway</p>
        </div>
    @elseif($campaign->end_date < now())
        <div class="bg-gray-100 rounded-lg p-6 sm:p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Campaign Has Ended</h2>
            <p class="text-gray-600">Thank you for your interest. This campaign has concluded.</p>
        </div>
    @elseif(!$campaign->donation_link)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 sm:p-8 text-center">
            <h2 class="text-xl font-bold text-yellow-800 mb-2">Donation Link Coming Soon</h2>
            <p class="text-yellow-700">The donation link for this campaign will be available shortly. Please check back soon!</p>
        </div>
    @endif

    <!-- Back to Campaigns -->
    <div class="text-center">
        <a href="{{ route('campaigns.index') }}" 
            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to All Campaigns
        </a>
    </div>
</div>
@endsection
