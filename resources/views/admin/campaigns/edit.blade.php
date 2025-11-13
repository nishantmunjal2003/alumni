@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Campaign</h1>
    <form method="POST" action="{{ route('admin.campaigns.update', $campaign->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $campaign->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="draft" {{ $campaign->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $campaign->status == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" alt="Current image" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Update Campaign</button>
        </div>
    </form>
</div>
@endsection




