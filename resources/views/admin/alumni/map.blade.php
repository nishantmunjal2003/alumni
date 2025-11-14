@extends('layouts.admin')

@section('title', 'Alumni World Map')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Alumni World Map</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View alumni locations across the globe</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.alumni.index') }}" 
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Directory
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 transition-colors">
        <form id="filter-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="passing_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Passing Year</label>
                    <select name="passing_year" id="passing_year" 
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors">
                        <option value="">All Years</option>
                        @foreach($passingYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="profile_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Status</label>
                    <select name="profile_status" id="profile_status" 
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-colors">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filters
                </button>
                <button type="button" id="clear-filters" 
                    class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Clear Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Map Container -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden relative transition-colors">
        <div id="map" style="height: 600px; width: 100%;"></div>
        <div id="map-loading" class="absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 dark:bg-opacity-75 flex items-center justify-center z-50 transition-colors">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400 mx-auto"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Loading alumni locations...</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="total-locations">0</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Locations</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="total-alumni">0</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Alumni</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" id="countries-count">0</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Countries</div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Dark mode support for Leaflet popups */
    .leaflet-popup-content-wrapper {
        background-color: var(--popup-bg, #ffffff);
        color: var(--popup-text, #333333);
    }
    
    .dark .leaflet-popup-content-wrapper {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
    }
    
    .leaflet-popup-tip {
        background-color: var(--popup-bg, #ffffff);
    }
    
    .dark .leaflet-popup-tip {
        background-color: #1f2937 !important;
    }
    
    .leaflet-container {
        background-color: var(--map-bg, #ffffff);
    }
    
    .dark .leaflet-container {
        background-color: #111827;
    }
</style>

<script>
(function() {
    let map;
    let markers = [];
    let geocodeCache = {};

    // Check if dark mode is enabled
    function isDarkMode() {
        return document.documentElement.classList.contains('dark') || 
               (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    // Initialize map
    function initMap() {
        map = L.map('map').setView([20, 0], 2);
        
        // Use dark map tiles in dark mode
        const isDark = isDarkMode();
        const tileUrl = isDark 
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        
        L.tileLayer(tileUrl, {
            attribution: isDark 
                ? '© OpenStreetMap contributors © CARTO'
                : '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        // Listen for dark mode changes
        let currentDarkMode = isDark;
        const observer = new MutationObserver(() => {
            const newIsDark = isDarkMode();
            if (newIsDark !== currentDarkMode) {
                currentDarkMode = newIsDark;
                map.eachLayer((layer) => {
                    if (layer instanceof L.TileLayer) {
                        map.removeLayer(layer);
                    }
                });
                const newTileUrl = newIsDark 
                    ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                    : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                L.tileLayer(newTileUrl, {
                    attribution: newIsDark 
                        ? '© OpenStreetMap contributors © CARTO'
                        : '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);
            }
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    }

    // Geocode location using Nominatim API
    async function geocodeLocation(locationString) {
        if (geocodeCache[locationString]) {
            return geocodeCache[locationString];
        }

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationString)}&limit=1`, {
                headers: {
                    'User-Agent': 'Alumni Portal'
                }
            });
            
            const data = await response.json();
            
            if (data && data.length > 0) {
                const result = {
                    lat: parseFloat(data[0].lat),
                    lon: parseFloat(data[0].lon),
                };
                geocodeCache[locationString] = result;
                return result;
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
        
        return null;
    }

    // Clear all markers
    function clearMarkers() {
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
    }

    // Load alumni locations
    async function loadAlumniLocations() {
        const loadingDiv = document.getElementById('map-loading');
        if (loadingDiv) loadingDiv.style.display = 'flex';

        const formData = new FormData(document.getElementById('filter-form'));
        const params = new URLSearchParams();
        
        if (formData.get('passing_year')) {
            params.append('passing_year', formData.get('passing_year'));
        }
        if (formData.get('status')) {
            params.append('status', formData.get('status'));
        }
        if (formData.get('profile_status')) {
            params.append('profile_status', formData.get('profile_status'));
        }

        try {
            const response = await fetch('{{ route("admin.alumni.map.locations") }}?' + params.toString());
            const data = await response.json();

            clearMarkers();

            const countries = new Set();
            let totalAlumni = 0;
            let processedCount = 0;

            // Process locations
            for (const location of data.locations) {
                countries.add(location.country);
                totalAlumni += location.alumni.length;

                const coordinates = await geocodeLocation(location.location_string);
                
                if (coordinates) {
                    // Escape HTML to prevent XSS
                    const escapeHtml = (text) => {
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    };

                    // Create popup content with dark mode support
                    const darkMode = isDarkMode();
                    const popupContent = `
                        <div class="p-2 min-w-[200px] max-w-[300px] ${darkMode ? 'dark' : ''}">
                            <h3 class="font-bold text-lg mb-2 ${darkMode ? 'text-white' : 'text-gray-900'}">${escapeHtml(location.city)}, ${escapeHtml(location.state || '')}</h3>
                            <p class="text-sm ${darkMode ? 'text-gray-400' : 'text-gray-600'} mb-2">${escapeHtml(location.country)}</p>
                            <p class="text-sm font-semibold mb-2 ${darkMode ? 'text-gray-300' : 'text-gray-700'}">${location.alumni.length} ${location.alumni.length === 1 ? 'Alumnus' : 'Alumni'}</p>
                            <div class="max-h-40 overflow-y-auto space-y-1">
                                ${location.alumni.map(alumnus => `
                                    <div class="text-xs border-b ${darkMode ? 'border-gray-700' : 'border-gray-200'} pb-1 pt-1">
                                        <a href="{{ url('/admin/alumni') }}/${alumnus.id}" target="_blank" class="font-semibold ${darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-800'}">${escapeHtml(alumnus.name)}</a><br>
                                        ${alumnus.passing_year ? `<span class="${darkMode ? 'text-gray-400' : 'text-gray-600'}">Year: ${escapeHtml(alumnus.passing_year)}</span>` : ''}
                                        ${alumnus.course ? ` | <span class="${darkMode ? 'text-gray-400' : 'text-gray-600'}">${escapeHtml(alumnus.course)}</span>` : ''}
                                        ${alumnus.company ? `<br><span class="${darkMode ? 'text-gray-400' : 'text-gray-600'}">${escapeHtml(alumnus.company)}</span>` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;

                    // Create marker with custom icon color based on count
                    const markerColor = location.alumni.length > 5 ? 'red' : location.alumni.length > 2 ? 'orange' : 'blue';
                    const marker = L.marker([coordinates.lat, coordinates.lon], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        })
                    })
                        .addTo(map)
                        .bindPopup(popupContent);

                    markers.push(marker);
                }

                processedCount++;
                // Update loading message
                if (loadingDiv) {
                    loadingDiv.querySelector('p').textContent = `Processing ${processedCount} of ${data.locations.length} locations...`;
                }

                // Small delay to respect rate limits
                await new Promise(resolve => setTimeout(resolve, 200));
            }

            // Update stats
            document.getElementById('total-locations').textContent = data.locations.length;
            document.getElementById('total-alumni').textContent = totalAlumni;
            document.getElementById('countries-count').textContent = countries.size;

            // Hide loading
            if (loadingDiv) loadingDiv.style.display = 'none';

            // Fit map to show all markers
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                // Show message if no markers
                if (loadingDiv) {
                    loadingDiv.querySelector('p').textContent = 'No locations found with the selected filters.';
                    setTimeout(() => {
                        loadingDiv.style.display = 'none';
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Error loading locations:', error);
            if (loadingDiv) {
                loadingDiv.querySelector('p').textContent = 'Error loading locations. Please try again.';
                setTimeout(() => {
                    loadingDiv.style.display = 'none';
                }, 2000);
            }
        }
    }

    // Initialize
    initMap();
    loadAlumniLocations();

    // Filter form submission
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        loadAlumniLocations();
    });

    // Clear filters
    document.getElementById('clear-filters').addEventListener('click', function() {
        document.getElementById('filter-form').reset();
        loadAlumniLocations();
    });
})();
</script>
@endsection

