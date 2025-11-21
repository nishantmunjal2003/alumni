@extends('layouts.app')

@section('title', 'Fellows - ' . $event->title)

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Fellows Attending</h1>
            <p class="mt-2 text-xl text-indigo-600 font-medium">{{ $event->title }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ $registrations->count() }} {{ Str::plural('fellow', $registrations->count()) }} registered</p>
        </div>
        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Event
        </a>
    </div>
    
    <!-- Search Form -->
    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
        <form id="search-form" method="GET" action="{{ route('events.fellows', $event->id) }}">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, major, company, city..." class="pl-11 pr-4 py-3 w-full border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all" value="{{ request('search') }}" autocomplete="off">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-all font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('events.fellows', $event->id) }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-all font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Grouped Results -->
    <div class="space-y-10">
        @forelse($sortedGroups as $passingYear => $yearRegistrations)
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <span>Batch {{ $passingYear }}</span>
                            @if($passingYear === $currentUserPassingYear)
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Your Batch
                                </span>
                            @endif
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $yearRegistrations->count() }} {{ Str::plural('fellow', $yearRegistrations->count()) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($yearRegistrations as $registration)
                        <div class="group bg-white shadow-md rounded-xl p-6 border border-gray-200 hover:shadow-xl hover:border-indigo-300 transition-all duration-300">
                            <div class="flex items-start gap-4 mb-4">
                                @if($registration->user->profile_image)
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $registration->user->profile_image_url }}" alt="{{ $registration->user->name }}" class="w-20 h-20 rounded-full object-cover ring-2 ring-gray-200 group-hover:ring-indigo-400 transition-all" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center hidden ring-2 ring-gray-200 group-hover:ring-indigo-400 transition-all">
                                            <span class="text-indigo-600 font-bold text-xl">{{ getUserInitials($registration->user->name) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-400 transition-all flex-shrink-0">
                                        <span class="text-indigo-600 font-bold text-xl">{{ getUserInitials($registration->user->name) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-lg text-gray-900 mb-1 truncate">{{ $registration->user->name }}</h3>
                                    @if($registration->user->course)
                                        <p class="text-sm font-medium text-indigo-600 mb-1">{{ $registration->user->course }}</p>
                                    @endif
                                    @if($registration->user->company)
                                        <p class="text-sm text-gray-600 truncate">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $registration->user->company }}
                                        </p>
                                    @endif
                                    @if($registration->user->current_position)
                                        <p class="text-sm text-gray-500 truncate">{{ $registration->user->current_position }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($registration->coming_from_city)
                                <div class="mb-4 flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Coming from {{ $registration->coming_from_city }}</span>
                                </div>
                            @endif
                            
                            @if($registration->photos->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500">{{ $registration->photos->count() }} {{ Str::plural('photo', $registration->photos->count()) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($registration->photos->take(4) as $photo)
                                            <div class="relative aspect-square overflow-hidden rounded-lg bg-gray-100">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Photo" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('alumni.show', $registration->user->id) }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                    View Profile
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200 shadow-lg">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No fellows found</h3>
                    <p class="text-gray-600">
                        @if(request('search'))
                            No fellows match your search criteria. Try adjusting your search terms.
                        @else
                            No fellows have registered for this event yet.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('events.fellows', $event->id) }}" class="mt-4 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium">
                            Clear search
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
(function() {
    const searchInput = document.getElementById('search');
    let searchTimeout = null;

    // Real-time search on input change with debouncing
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('search-form').submit();
            }, 500); // Wait 500ms after user stops typing
        });
    }
})();
</script>
@endsection




