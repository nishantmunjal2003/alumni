@extends('layouts.dataentry')

@section('title', 'DataEntry Dashboard')

@section('content')
<div class="space-y-3 sm:space-y-4 px-4 sm:px-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Pending Profiles for Review</h1>
            <p class="text-sm text-gray-600 mt-1">
                @php
                    $hasFilters = request('search') || request('proof_filter');
                    if ($hasFilters) {
                        $displayCount = $pendingProfiles->total();
                    } else {
                        $displayCount = $totalPendingCount ?? $pendingProfiles->total();
                    }
                @endphp
                <span class="font-semibold text-teal-600" id="pending-count">{{ $displayCount }}</span> 
                {{ $displayCount == 1 ? 'profile' : 'profiles' }} 
                @if($hasFilters)
                    found
                @else
                    pending review
                @endif
            </p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('dataentry.dashboard') }}" id="search-form" class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email, enrollment, course, company, or year..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                </div>
                <div class="sm:w-48">
                    <label for="proof_filter" class="block text-sm font-medium text-gray-700 mb-1">Proof Document</label>
                    <select name="proof_filter" id="proof_filter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <option value="">All</option>
                        <option value="uploaded" {{ request('proof_filter') === 'uploaded' ? 'selected' : '' }}>Uploaded</option>
                        <option value="missing" {{ request('proof_filter') === 'missing' ? 'selected' : '' }}>Missing</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                @if(request('search') || request('proof_filter'))
                    <a href="{{ route('dataentry.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Profiles List Container -->
    <div id="profiles-list">
        @include('dataentry.partials.pending-list', ['pendingProfiles' => $pendingProfiles])
    </div>
</div>

<script>
(function() {
    const searchInput = document.getElementById('search');
    const proofFilter = document.getElementById('proof_filter');
    const profilesList = document.getElementById('profiles-list');
    const searchForm = document.getElementById('search-form');
    let searchTimeout = null;
    let isSearching = false;

    function performSearch() {
        if (isSearching) {
            return;
        }

        isSearching = true;
        const params = new URLSearchParams();
        
        const searchValue = searchInput.value.trim();
        const proofFilterValue = proofFilter.value;
        
        if (searchValue) {
            params.append('search', searchValue);
        }
        
        if (proofFilterValue) {
            params.append('proof_filter', proofFilterValue);
        }

        // Show loading state
        const originalContent = profilesList.innerHTML;
        profilesList.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600"></div><span class="ml-3 text-gray-600">Searching...</span></div>';

        // Create AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000);

        const url = '{{ route("dataentry.dashboard") }}' + (params.toString() ? '?' + params.toString() : '');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            },
            credentials: 'same-origin',
            signal: controller.signal
        })
        .then(response => {
            clearTimeout(timeoutId);
            console.log('Response status:', response.status, response.statusText);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response body:', text.substring(0, 1000));
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                });
            }
            return response.text();
        })
        .then(html => {
            // The controller should return only the partial view HTML for AJAX requests
            // If we somehow get the full page, extract just the profiles list content
            if (html.includes('<!DOCTYPE html>') || html.includes('<html') || html.includes('extends')) {
                // Full page was returned - extract the partial content
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Try to find the profiles list content
                const content = tempDiv.querySelector('#profiles-list') || 
                               tempDiv.querySelector('[id*="profiles"]') ||
                               tempDiv.querySelector('table') ||
                               tempDiv.querySelector('.bg-white.shadow');
                
                if (content) {
                    profilesList.innerHTML = content.innerHTML;
                } else {
                    // Fallback: try to extract body content
                    const bodyContent = tempDiv.querySelector('body');
                    if (bodyContent) {
                        profilesList.innerHTML = bodyContent.innerHTML;
                    } else {
                        profilesList.innerHTML = '<div class="bg-white shadow rounded-lg p-6 text-center"><p class="text-red-600">Error loading results. Please refresh the page.</p></div>';
                    }
                }
            } else {
                // It's already just the partial content
                profilesList.innerHTML = html;
            }
            
            // Update the count display from pagination info
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const paginationLinks = doc.querySelectorAll('a[href*="page="]');
                // Try to extract total from pagination - Laravel pagination shows "Showing X to Y of Z results"
                const paginationText = doc.body.textContent || '';
                const showingMatch = paginationText.match(/Showing\s+\d+\s+to\s+\d+\s+of\s+(\d+)/i);
                if (showingMatch) {
                    const countElement = document.getElementById('pending-count');
                    if (countElement) {
                        countElement.textContent = showingMatch[1];
                    }
                }
            } catch (e) {
                // Ignore errors in count update
            }
            
            isSearching = false;
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Search error:', error);
            console.error('Error details:', {
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            if (error.name === 'AbortError') {
                profilesList.innerHTML = '<div class="bg-white shadow rounded-lg p-6 text-center"><p class="text-red-600">Request timed out. Please try again.</p></div>';
            } else {
                profilesList.innerHTML = '<div class="bg-white shadow rounded-lg p-6 text-center"><p class="text-red-600">An error occurred while searching. Please try again.</p><p class="text-sm text-gray-500 mt-2">Error: ' + (error.message || 'Unknown error') + '</p></div>';
            }
            isSearching = false;
        });
    }

    // Real-time search on input change with debouncing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    // Filter change handler
    proofFilter.addEventListener('change', function() {
        clearTimeout(searchTimeout);
        performSearch();
    });

    // Form submit handler
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        performSearch();
    });
})();
</script>
@endsection