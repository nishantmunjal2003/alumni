@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="bg-white rounded-lg shadow-md p-8">
    <h1 class="text-3xl font-bold mb-6">Edit Profile</h1>

    <form action="{{ route('alumni.update', $alumni) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $alumni->name) }}" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email', $alumni->email) }}" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $alumni->phone) }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="graduation_year" class="block text-gray-700 font-semibold mb-2">Graduation Year</label>
                <select name="graduation_year" id="graduation_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Select Year</option>
                    @for($year = date('Y'); $year >= 2004; $year--)
                        <option value="{{ $year }}" {{ old('graduation_year', $alumni->graduation_year) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="major" class="block text-gray-700 font-semibold mb-2">Major</label>
                <select name="major" id="major" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Select Major</option>
                    <option value="B.Tech CSE" {{ old('major', $alumni->major) == 'B.Tech CSE' ? 'selected' : '' }}>B.Tech CSE</option>
                    <option value="B.Tech ECE" {{ old('major', $alumni->major) == 'B.Tech ECE' ? 'selected' : '' }}>B.Tech ECE</option>
                    <option value="B.Tech EE" {{ old('major', $alumni->major) == 'B.Tech EE' ? 'selected' : '' }}>B.Tech EE</option>
                    <option value="B.Tech ME" {{ old('major', $alumni->major) == 'B.Tech ME' ? 'selected' : '' }}>B.Tech ME</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="current_position" class="block text-gray-700 font-semibold mb-2">Current Position</label>
                <input type="text" name="current_position" id="current_position" value="{{ old('current_position', $alumni->current_position) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label for="company" class="block text-gray-700 font-semibold mb-2">Company</label>
                <input type="text" name="company" id="company" value="{{ old('company', $alumni->company ?? '') }}" placeholder="Enter company name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </div>

        <div class="mb-4">
            <label for="linkedin_url" class="block text-gray-700 font-semibold mb-2">LinkedIn URL</label>
            <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $alumni->linkedin_url) }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <div class="mb-4">
            <label for="bio" class="block text-gray-700 font-semibold mb-2">Bio</label>
            <textarea name="bio" id="bio" rows="4"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('bio', $alumni->bio) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="profile_image" class="block text-gray-700 font-semibold mb-2">Profile Image</label>
            @if($alumni->profile_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $alumni->profile_image) }}" alt="Current Profile" class="w-32 h-32 rounded-full object-cover">
                </div>
            @endif
            <input type="file" name="profile_image" id="profile_image" accept="image/*"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <div class="flex items-center space-x-4">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                Update Profile
            </button>
            <a href="{{ route('alumni.show', $alumni) }}" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection

