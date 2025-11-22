@extends('layouts.manager')

@section('title', 'Alumni Directory')

@section('content')
<div class="space-y-4 sm:space-y-6 px-4 sm:px-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Alumni Directory</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                <span class="font-semibold text-indigo-600">{{ $totalAlumniCount ?? $alumni->total() }}</span> 
                @php
                    $count = $totalAlumniCount ?? $alumni->total();
                @endphp
                {{ $count == 1 ? 'alumnus' : 'alumni' }} registered
            </p>
        </div>
        <a href="{{ route('manager.dashboard') }}" class="text-sm sm:text-base text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form id="search-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Search by name, email, course, company..." class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('search') }}" autocomplete="off">
                </div>
                <div>
                    <select name="passing_year" id="passing_year" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Years</option>
                        @foreach($passingYears as $year)
                            <option value="{{ $year }}" {{ request('passing_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <select name="profile_status" id="profile_status" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Profile Status</option>
                        <option value="pending" {{ request('profile_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('profile_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="blocked" {{ request('profile_status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors inline-flex items-center justify-center gap-2 touch-manipulation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                <a href="{{ route('manager.alumni.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors text-center touch-manipulation">
                    Clear Filters
                </a>
                <button type="button" id="export-excel-btn" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors inline-flex items-center justify-center gap-2 touch-manipulation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export to CSV
                </button>
                <button type="button" id="send-email-all-btn" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors inline-flex items-center justify-center gap-2 touch-manipulation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Email to All
                </button>
            </div>
        </form>

        <div id="alumni-list" class="mt-6">
            @include('manager.alumni.partials.alumni-list', ['alumni' => $alumni])
        </div>
    </div>
</div>

<script>
(function() {
    const searchInput = document.getElementById('search');
    const passingYearSelect = document.getElementById('passing_year');
    const statusSelect = document.getElementById('status');
    const profileStatusSelect = document.getElementById('profile_status');
    const alumniList = document.getElementById('alumni-list');
    let searchTimeout = null;
    let isSearching = false;

    function performSearch() {
        if (isSearching) {
            return;
        }

        isSearching = true;
        const formData = new FormData(document.getElementById('search-form'));
        const params = new URLSearchParams();
        
        const searchValue = formData.get('search')?.trim() || '';
        const passingYearValue = formData.get('passing_year') || '';
        const statusValue = formData.get('status') || '';
        const profileStatusValue = formData.get('profile_status') || '';
        const sortByValue = new URLSearchParams(window.location.search).get('sort_by') || '';
        const sortOrderValue = new URLSearchParams(window.location.search).get('sort_order') || '';
        
        if (searchValue) {
            params.append('search', searchValue);
        }
        if (passingYearValue) {
            params.append('passing_year', passingYearValue);
        }
        if (statusValue) {
            params.append('status', statusValue);
        }
        if (profileStatusValue) {
            params.append('profile_status', profileStatusValue);
        }
        if (sortByValue) {
            params.append('sort_by', sortByValue);
        }
        if (sortOrderValue) {
            params.append('sort_order', sortOrderValue);
        }

        const url = '{{ route("manager.alumni.index") }}' + (params.toString() ? '?' + params.toString() : '');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.text())
        .then(html => {
            alumniList.innerHTML = html;
            isSearching = false;
        })
        .catch(error => {
            console.error('Error:', error);
            isSearching = false;
        });
    }

    // Search on input with debounce
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Search on select change
    [passingYearSelect, statusSelect, profileStatusSelect].forEach(select => {
        select?.addEventListener('change', performSearch);
    });

    // Search on form submit
    document.getElementById('search-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        performSearch();
    });

    // Export to Excel button - get current filters from form
    document.getElementById('export-excel-btn')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('search-form'));
        const params = new URLSearchParams();
        
        const searchValue = formData.get('search')?.trim() || '';
        const passingYearValue = formData.get('passing_year') || '';
        const statusValue = formData.get('status') || '';
        const profileStatusValue = formData.get('profile_status') || '';
        const sortByValue = new URLSearchParams(window.location.search).get('sort_by') || '';
        const sortOrderValue = new URLSearchParams(window.location.search).get('sort_order') || '';
        
        if (searchValue) {
            params.append('search', searchValue);
        }
        if (passingYearValue) {
            params.append('passing_year', passingYearValue);
        }
        if (statusValue) {
            params.append('status', statusValue);
        }
        if (profileStatusValue) {
            params.append('profile_status', profileStatusValue);
        }
        if (sortByValue) {
            params.append('sort_by', sortByValue);
        }
        if (sortOrderValue) {
            params.append('sort_order', sortOrderValue);
        }

        const url = '{{ route("manager.alumni.export") }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    });

    // Send Email to All button - get current filters from form
    document.getElementById('send-email-all-btn')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('search-form'));
        const params = new URLSearchParams();
        
        const searchValue = formData.get('search')?.trim() || '';
        const passingYearValue = formData.get('passing_year') || '';
        const statusValue = formData.get('status') || '';
        const profileStatusValue = formData.get('profile_status') || '';
        const sortByValue = new URLSearchParams(window.location.search).get('sort_by') || '';
        const sortOrderValue = new URLSearchParams(window.location.search).get('sort_order') || '';
        
        if (searchValue) {
            params.append('search', searchValue);
        }
        if (passingYearValue) {
            params.append('passing_year', passingYearValue);
        }
        if (statusValue) {
            params.append('status', statusValue);
        }
        if (profileStatusValue) {
            params.append('profile_status', profileStatusValue);
        }
        if (sortByValue) {
            params.append('sort_by', sortByValue);
        }
        if (sortOrderValue) {
            params.append('sort_order', sortOrderValue);
        }

        const url = '{{ route("manager.alumni.email.bulk") }}' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    });
})();
</script>
@endsection

