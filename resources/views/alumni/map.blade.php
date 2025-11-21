@extends('layouts.app')

@section('title', 'Alumni World Map')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900">Alumni World Map</h1>
        <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
            Discover where our alumni are located around the world. Connect with fellow alumni in your area!
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form id="filter-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="passing_year" class="block text-sm font-medium text-gray-700 mb-2">Passing Year</label>
                    <select name="passing_year" id="passing_year" 
                        class="w-full px-4 py-2 bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Years</option>
                        @foreach($passingYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                        class="w-full px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Map Container -->
    <div class="bg-white shadow rounded-lg overflow-hidden relative">
        <div id="map" style="height: 600px; width: 100%;"></div>
        <div id="map-loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Loading alumni locations...</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600" id="total-locations">0</div>
                <div class="text-sm text-gray-600 mt-1">Locations</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600" id="total-alumni">0</div>
                <div class="text-sm text-gray-600 mt-1">Total Alumni</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600" id="countries-count">0</div>
                <div class="text-sm text-gray-600 mt-1">Countries</div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Leaflet popup styles */
    .leaflet-popup-content-wrapper {
        background-color: #ffffff;
        color: #333333;
    }
    
    .leaflet-popup-tip {
        background-color: #ffffff;
    }
    
    .leaflet-container {
        background-color: #ffffff;
    }
</style>

<script>
(function() {
    let map;
    let markers = [];
    let geocodeCache = {};

    // Initialize map
    function initMap() {
        map = L.map('map').setView([20, 0], 2);
        
        // Use light map tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);
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

        try {
            const response = await fetch('{{ route("alumni.map.locations") }}?' + params.toString());
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
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    };

                    // Create popup content
                    const popupContent = `
                        <div class="p-2 min-w-[200px] max-w-[300px]">
                            <h3 class="font-bold text-lg mb-2 text-gray-900">${escapeHtml(location.city)}, ${escapeHtml(location.state || '')}</h3>
                            <p class="text-sm text-gray-600 mb-2">${escapeHtml(location.country)}</p>
                            <p class="text-sm font-semibold mb-2 text-gray-700">${location.alumni.length} ${location.alumni.length === 1 ? 'Alumnus' : 'Alumni'}</p>
                            <div class="max-h-40 overflow-y-auto space-y-1">
                                ${location.alumni.map(alumnus => `
                                    <div class="text-xs border-b border-gray-200 pb-1 pt-1">
                                        <a href="{{ url('/alumni') }}/${alumnus.id}" target="_blank" class="font-semibold text-indigo-600 hover:text-indigo-800">${escapeHtml(alumnus.name)}</a><br>
                                        ${alumnus.passing_year ? `<span class="text-gray-600">Year: ${escapeHtml(alumnus.passing_year)}</span>` : ''}
                                        ${alumnus.course ? ` | <span class="text-gray-600">${escapeHtml(alumnus.course)}</span>` : ''}
                                        ${alumnus.company ? `<br><span class="text-gray-600">${escapeHtml(alumnus.company)}</span>` : ''}
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
})();
</script>
@endsection

