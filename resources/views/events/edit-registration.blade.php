@extends('layouts.app')

@section('title', 'Edit Registration for ' . $event->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Event Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Edit Registration</h1>
                <h2 class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $event->title }}</h2>
                <div class="mt-4 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <p><strong class="text-gray-700 dark:text-gray-300">Date:</strong> {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('F d, Y h:i A') }}</p>
                    <p><strong class="text-gray-700 dark:text-gray-300">Venue:</strong> {{ $event->venue }}</p>
                </div>
            </div>
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-24 h-24 rounded-lg object-cover hidden md:block">
            @endif
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('events.registrations.update', [$event->id, $registration->id]) }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 m-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Please correct the following errors:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6 space-y-8">
            <!-- Travel Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <svg class="inline-block w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Travel Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="coming_from_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Coming From City</label>
                        <input type="text" name="coming_from_city" id="coming_from_city" value="{{ old('coming_from_city', $registration->coming_from_city) }}" placeholder="Enter your city" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="travel_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Travel Mode</label>
                        <select name="travel_mode" id="travel_mode" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select travel mode</option>
                            <option value="car" {{ old('travel_mode', $registration->travel_mode) == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="train" {{ old('travel_mode', $registration->travel_mode) == 'train' ? 'selected' : '' }}>Train</option>
                            <option value="flight" {{ old('travel_mode', $registration->travel_mode) == 'flight' ? 'selected' : '' }}>Flight</option>
                            <option value="bus" {{ old('travel_mode', $registration->travel_mode) == 'bus' ? 'selected' : '' }}>Bus</option>
                            <option value="other" {{ old('travel_mode', $registration->travel_mode) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arrival Date</label>
                        <input type="date" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $registration->arrival_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="arrival_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arrival Time</label>
                        <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time', $registration->arrival_time?->format('H:i') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Accommodation & Preferences Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <svg class="inline-block w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Accommodation & Preferences
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                        <input type="checkbox" name="needs_stay" id="needs_stay" value="1" {{ old('needs_stay', $registration->needs_stay) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="needs_stay" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            I need accommodation/stay arrangements
                        </label>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                        <input type="checkbox" name="coming_with_family" id="coming_with_family" value="1" {{ old('coming_with_family', $registration->coming_with_family) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="coming_with_family" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Coming with family
                        </label>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <svg class="inline-block w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Additional Information
                </h3>
                <div class="space-y-6">
                    <div>
                        <label for="return_journey_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Return Journey Details</label>
                        <textarea name="return_journey_details" id="return_journey_details" rows="3" placeholder="Provide details about your return journey (optional)" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">{{ old('return_journey_details', $registration->return_journey_details) }}</textarea>
                    </div>
                    <div>
                        <label for="memories_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Memories / Description</label>
                        <textarea name="memories_description" id="memories_description" rows="4" placeholder="Share your memories or any additional information (optional)" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">{{ old('memories_description', $registration->memories_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Existing Photos Section -->
            @if($registration->photos->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <svg class="inline-block w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Existing Photos
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($registration->photos as $photo)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Photo {{ $loop->iteration }}" class="w-full h-24 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">You can add more photos below. Existing photos will be kept.</p>
            </div>
            @endif

            <!-- Photos Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    <svg class="inline-block w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Add More Photos (Optional)
                </h3>
                <div class="mt-4">
                    <div class="flex items-center justify-center w-full">
                        <label for="photos" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB each (Max 10 photos)</p>
                            </div>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div id="photo-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('events.show', $event->id) }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    ← Back to Event
                </a>
                <form method="POST" action="{{ route('events.registrations.destroy', [$event->id, $registration->id]) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel your registration? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                        Cancel Registration
                    </button>
                </form>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 font-medium transition-colors shadow-sm hover:shadow w-full sm:w-auto">
                Update Registration
            </button>
        </div>
    </form>
</div>

<script>
    // Photo preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photos');
        const photoPreview = document.getElementById('photo-preview');
        
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                photoPreview.innerHTML = '';
                
                if (files.length > 0) {
                    photoPreview.classList.remove('hidden');
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'relative';
                                div.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                    <span class="absolute top-1 right-1 bg-gray-800 text-white text-xs px-2 py-1 rounded">${index + 1}</span>
                                `;
                                photoPreview.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    photoPreview.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection

