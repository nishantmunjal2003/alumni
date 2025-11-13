@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Create Event</h1>
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date *</label>
                <input type="datetime-local" name="event_date" id="event_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="venue" class="block text-sm font-medium text-gray-700">Venue *</label>
                <input type="text" name="venue" id="venue" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                <input type="text" name="location" id="location" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full">
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Create Event</button>
        </div>
    </form>
</div>
@endsection




