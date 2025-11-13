@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Event</h1>
    <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $event->description) }}</textarea>
            </div>
            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date *</label>
                <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="venue" class="block text-sm font-medium text-gray-700">Venue *</label>
                <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $event->status == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Update Event</button>
        </div>
    </form>
</div>
@endsection




