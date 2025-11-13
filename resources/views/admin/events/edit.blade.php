@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Event</h1>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="description" rows="10" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $event->description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">You can use HTML tags for formatting (e.g., &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt;)</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Event Start Date *</label>
                    <input type="datetime-local" name="event_start_date" id="event_start_date" value="{{ old('event_start_date', isset($event->event_start_date) ? $event->event_start_date->format('Y-m-d\TH:i') : ($event->event_date ?? '')->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">Event End Date <span class="text-gray-500 text-xs">(Optional - Leave blank for single day events)</span></label>
                    <input type="datetime-local" name="event_end_date" id="event_end_date" value="{{ old('event_end_date', isset($event->event_end_date) ? $event->event_end_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <div>
                <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue *</label>
                <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
                <label for="google_maps_link" class="block text-sm font-medium text-gray-700 mb-2">Google Maps Link *</label>
                <input type="url" name="google_maps_link" id="google_maps_link" value="{{ old('google_maps_link', $event->google_maps_link ?? '') }}" placeholder="https://maps.google.com/..." required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-sm text-gray-500">Paste the Google Maps link for the event location</p>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>
        </div>
        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">Update Event</button>
            <a href="{{ route('admin.events.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection




