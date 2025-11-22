@extends('layouts.admin')

@section('title', 'Pending Profiles')

@section('content')
<div class="space-y-3 sm:space-y-4 px-4 sm:px-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Pending Profiles for Approval</h1>
            <p class="text-sm text-gray-600 mt-1">
                <span class="font-semibold text-indigo-600">{{ $totalPendingCount ?? $pendingProfiles->total() }}</span> 
                @php
                    $count = $totalPendingCount ?? $pendingProfiles->total();
                @endphp
                {{ $count == 1 ? 'profile' : 'profiles' }} pending approval
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <a href="{{ route('admin.profiles.missing-details.email', ['missing_type' => 'proof_document']) }}" class="text-sm text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-md inline-flex items-center justify-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Email Missing Details
            </a>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.profiles.pending') }}" id="search-form" class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email, enrollment, course, company, or year..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="sm:w-48">
                    <label for="proof_filter" class="block text-sm font-medium text-gray-700 mb-1">Proof Document</label>
                    <select name="proof_filter" id="proof_filter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All</option>
                        <option value="uploaded" {{ request('proof_filter') === 'uploaded' ? 'selected' : '' }}>Uploaded</option>
                        <option value="missing" {{ request('proof_filter') === 'missing' ? 'selected' : '' }}>Missing</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                @if(request('search') || request('proof_filter'))
                    <a href="{{ route('admin.profiles.pending') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Profiles List Container -->
    <div id="profiles-list">
        @include('admin.profiles.partials.pending-list', ['pendingProfiles' => $pendingProfiles])
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
        profilesList.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div><span class="ml-3 text-gray-600">Searching...</span></div>';

        // Create AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

        const url = '{{ route("admin.profiles.pending") }}' + (params.toString() ? '?' + params.toString() : '');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            signal: controller.signal
        })
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            profilesList.innerHTML = html;
            isSearching = false;
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Search error:', error);
            if (error.name === 'AbortError') {
                profilesList.innerHTML = '<div class="bg-white shadow rounded-lg p-6 text-center"><p class="text-red-600">Request timed out. Please try again.</p></div>';
            } else {
                profilesList.innerHTML = '<div class="bg-white shadow rounded-lg p-6 text-center"><p class="text-red-600">An error occurred while searching. Please try again.</p></div>';
            }
            isSearching = false;
        });
    }

    // Real-time search on input change with debouncing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300); // Wait 300ms after user stops typing
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


