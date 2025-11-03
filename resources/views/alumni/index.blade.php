@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Alumni Directory</h1>
    <p class="text-gray-600">Connect with fellow alumni</p>
</div>

<!-- Search Form -->
<div class="mb-6">
    <div class="flex gap-2">
        <input 
            type="text" 
            id="search-input"
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search by name, email, major, company, position, or graduation year..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
            autocomplete="off"
        >
        <button type="button" id="search-btn" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            Search
        </button>
        <button type="button" id="clear-btn" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300" style="display: none;">
            Clear
        </button>
    </div>
    <div id="search-loading" class="mt-2 text-gray-500" style="display: none;">
        Searching...
    </div>
    <div id="search-results-count" class="mt-2 text-gray-600" style="display: none;">
        <span id="results-count"></span> results found
    </div>
</div>

@auth
    <div class="mb-6">
        <a href="{{ route('alumni.edit', auth()->user()) }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 inline-block">
            Edit My Profile
        </a>
    </div>
@endauth

@include('alumni.partials.alumni-grid', ['alumni' => $alumni])

@include('alumni.partials.pagination', ['alumni' => $alumni])

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const clearBtn = document.getElementById('clear-btn');
    const loadingDiv = document.getElementById('search-loading');
    const resultsCountDiv = document.getElementById('search-results-count');
    const resultsCount = document.getElementById('results-count');
    
    let searchTimeout;
    
    // Show/hide clear button based on input
    function toggleClearButton() {
        if (searchInput.value.trim() !== '') {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }
    }
    
    // Perform AJAX search
    function performSearch(searchTerm) {
        loadingDiv.style.display = 'block';
        resultsCountDiv.style.display = 'none';
        
        fetch('{{ route("alumni.index") }}?search=' + encodeURIComponent(searchTerm), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('alumni-grid').outerHTML = data.html;
            document.getElementById('pagination-container').outerHTML = data.pagination;
            
            if (data.count > 0) {
                resultsCount.textContent = data.count;
                resultsCountDiv.style.display = 'block';
            } else {
                resultsCountDiv.style.display = 'none';
            }
            
            loadingDiv.style.display = 'none';
            
            // Update URL without reload
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);
            loadingDiv.style.display = 'none';
        });
    }
    
    // Search on button click
    searchBtn.addEventListener('click', function() {
        performSearch(searchInput.value);
    });
    
    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch(searchInput.value);
        }
    });
    
    // Debounced search as user types (optional - uncomment if you want live search)
    // searchInput.addEventListener('input', function() {
    //     clearTimeout(searchTimeout);
    //     toggleClearButton();
    //     
    //     searchTimeout = setTimeout(function() {
    //         if (searchInput.value.trim() !== '') {
    //             performSearch(searchInput.value);
    //         } else {
    //             performSearch('');
    //         }
    //     }, 500); // Wait 500ms after user stops typing
    // });
    
    // Clear search
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        toggleClearButton();
        performSearch('');
    });
    
    // Handle pagination links (also use AJAX)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('.pagination a').href;
            const searchParams = new URL(url).searchParams;
            const search = searchParams.get('search') || '';
            
            loadingDiv.style.display = 'block';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('alumni-grid').outerHTML = data.html;
                document.getElementById('pagination-container').outerHTML = data.pagination;
                
                if (data.count > 0) {
                    resultsCount.textContent = data.count;
                    resultsCountDiv.style.display = 'block';
                }
                
                loadingDiv.style.display = 'none';
                
                // Scroll to top of results
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Error:', error);
                loadingDiv.style.display = 'none';
            });
        }
    });
    
    // Initialize clear button state
    toggleClearButton();
});
</script>
@endsection

