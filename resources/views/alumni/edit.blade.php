@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Profile</h1>
    <form method="POST" action="{{ route('alumni.update', $user->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="graduation_year" class="block text-sm font-medium text-gray-700">Graduation Year</label>
                <input type="text" name="graduation_year" id="graduation_year" value="{{ old('graduation_year', $user->graduation_year) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="major" class="block text-sm font-medium text-gray-700">Major</label>
                <input type="text" name="major" id="major" value="{{ old('major', $user->major) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="current_position" class="block text-sm font-medium text-gray-700">Current Position</label>
                <input type="text" name="current_position" id="current_position" value="{{ old('current_position', $user->current_position) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                <input type="text" name="company" id="company" value="{{ old('company', $user->company) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $user->bio) }}</textarea>
            </div>
            
            <div>
                <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Current profile" class="mt-2 w-32 h-32 rounded-full">
                @endif
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Update Profile</button>
        </div>
    </form>
</div>
@endsection




