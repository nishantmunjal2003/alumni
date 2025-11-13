@extends('layouts.app')

@section('title', 'Register for ' . $event->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Register for {{ $event->title }}</h1>
    <form method="POST" action="{{ route('events.registrations.store', $event->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date</label>
                <input type="date" name="arrival_date" id="arrival_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="coming_from_city" class="block text-sm font-medium text-gray-700">Coming From City</label>
                <input type="text" name="coming_from_city" id="coming_from_city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Arrival Time</label>
                <input type="time" name="arrival_time" id="arrival_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="travel_mode" class="block text-sm font-medium text-gray-700">Travel Mode</label>
                <select name="travel_mode" id="travel_mode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Select</option>
                    <option value="car">Car</option>
                    <option value="train">Train</option>
                    <option value="flight">Flight</option>
                    <option value="bus">Bus</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="needs_stay" id="needs_stay" class="h-4 w-4 text-indigo-600">
                <label for="needs_stay" class="ml-2 block text-sm text-gray-700">Need Accommodation</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="coming_with_family" id="coming_with_family" class="h-4 w-4 text-indigo-600">
                <label for="coming_with_family" class="ml-2 block text-sm text-gray-700">Coming with Family</label>
            </div>
            <div>
                <label for="return_journey_details" class="block text-sm font-medium text-gray-700">Return Journey Details</label>
                <textarea name="return_journey_details" id="return_journey_details" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div>
                <label for="memories_description" class="block text-sm font-medium text-gray-700">Memories/Description</label>
                <textarea name="memories_description" id="memories_description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div>
                <label for="photos" class="block text-sm font-medium text-gray-700">Photos (Max 10)</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="mt-1 block w-full">
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Register</button>
        </div>
    </form>
</div>
@endsection




