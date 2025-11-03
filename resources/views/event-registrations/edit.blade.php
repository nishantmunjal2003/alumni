@extends('layouts.app')

@section('title', 'Register for Event')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-900">Update Registration</h1>
        <p class="text-gray-600 mb-6">{{ $event->title }}</p>

        <form action="{{ route('events.registrations.update', [$event, $registration]) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <!-- Arrival Information -->
            <div class="mb-8 p-6 bg-purple-50 rounded-lg border border-purple-200">
                <h2 class="text-xl font-semibold mb-4 text-purple-900">Arrival Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="arrival_date" class="block text-gray-700 font-semibold mb-2">When are you coming for the event date? *</label>
                        <input type="date" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $registration->arrival_date ? $registration->arrival_date->format('Y-m-d') : '') }}" min="{{ $event->event_date->format('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('arrival_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="coming_from_city" class="block text-gray-700 font-semibold mb-2">From which city you are coming? *</label>
                        <input type="text" name="coming_from_city" id="coming_from_city" value="{{ old('coming_from_city', $registration->coming_from_city) }}" placeholder="Enter city name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('coming_from_city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="arrival_time" class="block text-gray-700 font-semibold mb-2">Time of arrival (if any, otherwise update later)</label>
                        <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time', $registration->arrival_time ? \Carbon\Carbon::parse($registration->arrival_time)->format('H:i') : '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('arrival_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">You can update this later if you don't know yet</p>
                </div>
            </div>

            <!-- Travel & Accommodation -->
            <div class="mb-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
                <h2 class="text-xl font-semibold mb-4 text-blue-900">Travel & Accommodation</h2>
                
                <div class="mb-4">
                    <label for="travel_mode" class="block text-gray-700 font-semibold mb-2">How are you coming? *</label>
                    <select name="travel_mode" id="travel_mode" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select travel mode</option>
                        <option value="car" {{ old('travel_mode', $registration->travel_mode) == 'car' ? 'selected' : '' }}>Car</option>
                        <option value="train" {{ old('travel_mode', $registration->travel_mode) == 'train' ? 'selected' : '' }}>Train</option>
                        <option value="flight" {{ old('travel_mode', $registration->travel_mode) == 'flight' ? 'selected' : '' }}>Flight</option>
                        <option value="bus" {{ old('travel_mode', $registration->travel_mode) == 'bus' ? 'selected' : '' }}>Bus</option>
                        <option value="other" {{ old('travel_mode', $registration->travel_mode) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('travel_mode')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="needs_stay" id="needs_stay" value="1" {{ old('needs_stay', $registration->needs_stay) ? 'checked' : '' }}
                            class="rounded text-purple-600 focus:ring-purple-500 w-5 h-5">
                        <label for="needs_stay" class="ml-2 text-gray-700 font-semibold">Do you need stay?</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="coming_with_family" id="coming_with_family" value="1" {{ old('coming_with_family', $registration->coming_with_family) ? 'checked' : '' }}
                            class="rounded text-purple-600 focus:ring-purple-500 w-5 h-5">
                        <label for="coming_with_family" class="ml-2 text-gray-700 font-semibold">Are you coming with family?</label>
                    </div>
                </div>

                <div>
                    <label for="return_journey_details" class="block text-gray-700 font-semibold mb-2">Return Journey Details</label>
                    <textarea name="return_journey_details" id="return_journey_details" rows="3" placeholder="Please share your return journey details..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('return_journey_details', $registration->return_journey_details) }}</textarea>
                    @error('return_journey_details')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Friends Selection -->
            <div class="mb-8 p-6 bg-green-50 rounded-lg border border-green-200">
                <h2 class="text-xl font-semibold mb-4 text-green-900">Search your friends and invite them to reach</h2>
                
                <div class="mb-4">
                    <input type="text" id="friend-search" placeholder="Search alumni by name..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div id="friend-results" class="max-h-64 overflow-y-auto border rounded p-3 bg-white mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2" id="friends-list">
                        @foreach($alumni as $alumnus)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-purple-50 p-2 rounded">
                                <input type="checkbox" name="friends[]" value="{{ $alumnus->id }}" 
                                    {{ in_array($alumnus->id, old('friends', $registration->friends->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded text-purple-600 focus:ring-purple-500 friend-checkbox">
                                <span class="text-sm text-gray-700">{{ $alumnus->name }} 
                                    @if($alumnus->graduation_year)
                                        <span class="text-gray-500">({{ $alumnus->graduation_year }})</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <p class="text-sm text-gray-600">Selected: <span id="selected-count">0</span> friends</p>
            </div>

            <!-- Memories -->
            <div class="mb-8 p-6 bg-yellow-50 rounded-lg border border-yellow-200">
                <h2 class="text-xl font-semibold mb-4 text-yellow-900">Share your good memories</h2>
                
                <div class="mb-4">
                    <label for="memories_description" class="block text-gray-700 font-semibold mb-2">Memories Description</label>
                    <textarea name="memories_description" id="memories_description" rows="4" placeholder="Share your memories with friends..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('memories_description', $registration->memories_description) }}</textarea>
                    
                    @if($registration->photos->count() > 0)
                        <div class="mt-4">
                            <p class="text-sm font-semibold mb-2">Existing Photos:</p>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($registration->photos as $photo)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Memory photo" class="w-full h-32 object-cover rounded-lg">
                                        @if($photo->caption)
                                            <p class="text-xs text-gray-600 mt-1">{{ $photo->caption }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="memories_photos" class="block text-gray-700 font-semibold mb-2">Upload photos with your friends</label>
                    <input type="file" name="memories_photos[]" id="memories_photos" multiple accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <p class="text-xs text-gray-500 mt-1">You can select multiple photos (Max 5MB each)</p>
                    @error('memories_photos.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="photo-preview" class="mt-4 grid grid-cols-3 gap-4"></div>
            </div>

            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 shadow-lg font-semibold">
                    Update Registration
                </button>
                <a href="{{ route('events.show', $event) }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 shadow-md font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Friend search
    document.getElementById('friend-search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const checkboxes = document.querySelectorAll('.friend-checkbox');
        let visibleCount = 0;

        checkboxes.forEach(checkbox => {
            const label = checkbox.closest('label');
            const text = label.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                label.style.display = 'flex';
                visibleCount++;
            } else {
                label.style.display = 'none';
            }
        });
    });

    // Update selected count
    document.querySelectorAll('.friend-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const count = document.querySelectorAll('.friend-checkbox:checked').length;
            document.getElementById('selected-count').textContent = count;
        });
    });

    // Initial count
    const initialCount = document.querySelectorAll('.friend-checkbox:checked').length;
    document.getElementById('selected-count').textContent = initialCount;

    // Photo preview
    document.getElementById('memories_photos').addEventListener('change', function(e) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
@endsection

