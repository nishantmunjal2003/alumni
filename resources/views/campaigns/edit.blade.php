@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Edit Campaign</h1>
    <p class="text-gray-600">Update campaign details</p>
</div>

<form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="title" class="block text-gray-700 font-semibold mb-2">Title *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('title')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-gray-700 font-semibold mb-2">Description *</label>
        <textarea name="description" id="description" rows="6" required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $campaign->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="image" class="block text-gray-700 font-semibold mb-2">Image</label>
        @if($campaign->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-32 h-32 object-cover rounded">
            </div>
        @endif
        <input type="file" name="image" id="image" accept="image/*"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="start_date" class="block text-gray-700 font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $campaign->start_date ? $campaign->start_date->format('Y-m-d') : '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            @error('start_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="end_date" class="block text-gray-700 font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            @error('end_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mb-6">
        <label for="status" class="block text-gray-700 font-semibold mb-2">Status *</label>
        <select name="status" id="status" required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $campaign->status) == 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ old('status', $campaign->status) == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-4">
        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
            Update Campaign
        </button>
        <a href="{{ route('campaigns.show', $campaign) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
            Cancel
        </a>
    </div>
</form>
@endsection


