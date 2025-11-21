@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Alumni Directory</h1>
            <p class="mt-2 text-gray-600">Search and connect with fellow alumni</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
        <form id="search-form" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, course, company, designation..." class="pl-11 pr-4 py-3 w-full border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-white text-gray-900" value="{{ request('search') }}" autocomplete="off">
                </div>
                <div class="relative">
                    <select name="passing_year" id="passing_year" class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all appearance-none bg-white cursor-pointer text-gray-900">
                        <option value="">All Years</option>
                        @foreach($passingYears as $year)
                            <option value="{{ $year }}" {{ request('passing_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-all font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </span>
                </button>
            </div>
        </form>

        <div id="alumni-list" class="relative">
            @include('alumni.partials.alumni-list', ['alumni' => $alumni])
        </div>
    </div>
</div>

<script>
(function() {
    const searchInput = document.getElementById('search');
    const passingYearSelect = document.getElementById('passing_year');
    const alumniList = document.getElementById('alumni-list');
    let searchTimeout = null;
    let isSearching = false;
    let isInitialLoad = true;

    // Mark initial load as complete after a short delay
    setTimeout(function() {
        isInitialLoad = false;
    }, 1000);

    function performSearch() {
        if (isSearching) {
            return;
        }

        isSearching = true;
        const params = new URLSearchParams();
        
        // Read values directly from input elements to ensure we get the current value
        const searchValue = searchInput.value.trim();
        const passingYearValue = passingYearSelect.value;
        
        if (searchValue) {
            params.append('search', searchValue);
        }
        if (passingYearValue) {
            params.append('passing_year', passingYearValue);
        }
        
        // Show loading state
        const originalContent = alumniList.innerHTML;
        alumniList.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div><span class="ml-3 text-gray-600">Searching...</span></div>';
        
        // Create AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        
        fetch('{{ route("alumni.index") }}?' + params.toString(), {
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
            alumniList.innerHTML = html;
            isSearching = false;
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Search error:', error);
            if (error.name === 'AbortError') {
                alumniList.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Request timed out. Please try again.</p></div>';
            } else {
                alumniList.innerHTML = '<div class="text-center py-8"><p class="text-red-600">An error occurred while searching. Please try again.</p></div>';
            }
            isSearching = false;
        });
    }

    // Real-time search on input change with debouncing (only after initial load)
    searchInput.addEventListener('input', function() {
        if (isInitialLoad) {
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 200); // Wait 200ms after user stops typing
    });

    // Search on passing year change (only after initial load)
    passingYearSelect.addEventListener('change', function() {
        if (isInitialLoad) {
            return;
        }
        performSearch();
    });

    // Form submit handler (always works)
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        isInitialLoad = false; // Allow search on explicit submit
        performSearch();
    });
})();
</script>
@endsection




