@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Create Event</h1>
    <p class="text-gray-600">Create a new event and send invitations to specific batches</p>
</div>

<form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-6 lg:p-8">
    @csrf

    <!-- Title and Description Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div>
            <label for="title" class="block text-gray-700 font-semibold mb-2">Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-gray-700 font-semibold mb-2">Status *</label>
            <select name="status" id="status" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Description Full Width -->
    <div class="mb-6">
        <label for="description" class="block text-gray-700 font-semibold mb-2">Description *</label>
        <textarea name="description" id="description" rows="5" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">{{ old('description') }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Image and Event Details Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Image (Left Column) -->
        <div>
            <label for="image" class="block text-gray-700 font-semibold mb-2">Event Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Upload an image for this event</p>
        </div>

        <!-- Event Date and Location/Venue (Right Columns) -->
        <div class="lg:col-span-2 space-y-4">
            <div>
                <label for="event_date" class="block text-gray-700 font-semibold mb-2">Event Date & Time *</label>
                <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
                @error('event_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location and Venue on same line, full width -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-gray-700 font-semibold mb-2">
                        Location
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Enter location or paste Google Maps link"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    <p class="text-xs text-gray-500 mt-1">Enter address or paste Google Maps link (https://maps.google.com/...)</p>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="venue" class="block text-gray-700 font-semibold mb-2">Venue</label>
                    <input type="text" name="venue" id="venue" value="{{ old('venue') }}" placeholder="Enter venue name (e.g., Auditorium Hall)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300">
                    <p class="text-xs text-gray-500 mt-1">Type the specific venue or hall name</p>
                    @error('venue')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Selection for Invitations -->
    <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Select Batches to Invite (Graduation Years)</h3>
        <p class="text-sm text-gray-600 mb-4">Choose which graduation year batches should receive invitations for this event.</p>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-48 overflow-y-auto border rounded p-3 bg-white">
            @forelse($graduationYears as $year)
                <label class="flex items-center space-x-2 cursor-pointer hover:bg-purple-50 p-2 rounded">
                    <input type="checkbox" name="target_graduation_years[]" value="{{ $year }}" 
                        {{ in_array($year, old('target_graduation_years', [])) ? 'checked' : '' }}
                        class="rounded text-purple-600 focus:ring-purple-500">
                    <span class="text-sm text-gray-700">{{ $year }}</span>
                </label>
            @empty
                <p class="col-span-4 text-sm text-gray-500 text-center py-4">No graduation years available. Alumni profiles need to have graduation years set.</p>
            @endforelse
        </div>
        
        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-sm text-blue-800">
                <strong>📧 Automatic Invitations:</strong> When you create this event, email invitations will be automatically sent to all alumni in the selected graduation year batches.
            </p>
        </div>
    </div>

    <div class="flex gap-4 pt-6 border-t border-gray-200">
        <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 font-semibold">
            Create Event
        </button>
        <a href="{{ route('events.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold">
            Cancel
        </a>
    </div>
</form>

@endsection

