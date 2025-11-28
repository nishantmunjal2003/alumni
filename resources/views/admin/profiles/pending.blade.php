@extends('layouts.admin')

@section('title', 'Pending Profiles')

@section('content')
<style>
    /* Force light mode - override any dark mode styles */
    html, body {
        color-scheme: light !important;
    }
    html.dark, body.dark {
        background-color: #f9fafb !important;
    }
    html.dark *, body.dark * {
        --tw-bg-opacity: 1;
    }
</style>
<script>
    // Force light mode on pending profiles page
    (function() {
        let isProcessing = false;
        
        // Remove dark class immediately
        const removeDarkMode = function() {
            if (isProcessing) {
                return;
            }
            isProcessing = true;
            
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
            if (document.body.classList.contains('dark')) {
                document.body.classList.remove('dark');
            }
            
            // Use requestAnimationFrame to prevent blocking
            requestAnimationFrame(() => {
                isProcessing = false;
            });
        };
        
        // Remove on DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeDarkMode);
        } else {
            removeDarkMode();
        }
        
        // Watch for any attempts to add dark class with debouncing
        let timeoutId = null;
        const observer = new MutationObserver(function(mutations) {
            // Debounce the removal to prevent excessive calls
            if (timeoutId) {
                clearTimeout(timeoutId);
            }
            timeoutId = setTimeout(removeDarkMode, 50);
        });
        
        observer.observe(document.documentElement, { 
            attributes: true, 
            attributeFilter: ['class'],
            attributeOldValue: false
        });
        observer.observe(document.body, { 
            attributes: true, 
            attributeFilter: ['class'],
            attributeOldValue: false
        });
        
        // Remove dark mode toggle buttons if they exist
        const removeDarkToggle = function() {
            const darkToggles = document.querySelectorAll('[data-theme-toggle], [id*="dark"], [id*="theme"], button[aria-label*="dark"], button[aria-label*="theme"]');
            darkToggles.forEach(btn => {
                if (btn.textContent.toLowerCase().includes('dark') || btn.textContent.toLowerCase().includes('theme')) {
                    btn.style.display = 'none';
                }
            });
        };
        
        // Remove toggle buttons immediately and on DOMContentLoaded
        removeDarkToggle();
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeDarkToggle);
        }
        
        // Watch for dynamically added toggle buttons
        const toggleObserver = new MutationObserver(removeDarkToggle);
        toggleObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    })();
</script>
<div class="space-y-3 sm:space-y-4 px-4 sm:px-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Pending Profiles for Approval</h1>
            <p class="text-sm text-gray-600 mt-1">
                @php
                    // Show filtered count if filters are applied, otherwise show total
                    $hasFilters = request('search') || request('proof_filter');
                    $displayCount = $hasFilters ? $pendingProfiles->total() : ($totalPendingCount ?? $pendingProfiles->total());
                @endphp
                <span class="font-semibold text-indigo-600" id="pending-count">{{ $displayCount }}</span> 
                {{ $displayCount == 1 ? 'profile' : 'profiles' }} 
                @if($hasFilters)
                    found
                @else
                    pending approval
                @endif
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
                'Accept': 'text/html',
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


