@extends('layouts.manager')

@section('title', 'Create Fundraising Campaign')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create Fundraising Campaign</h1>
        <p class="mt-2 text-sm text-gray-600">Create a campaign to raise support from alumni for specific causes like infrastructure, scholarships, or campus development.</p>
    </div>

    <form method="POST" action="{{ route('manager.campaigns.store') }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 sm:p-8">
        @csrf
        <div class="space-y-6">
            <!-- Campaign Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Campaign Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                    placeholder="e.g., Donate a Brick - New Library Building"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campaign Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Campaign Description *</label>
                <textarea name="description" id="description" rows="8" required 
                    placeholder="Describe the cause, its importance, and how alumni support will make a difference..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Provide detailed information about the campaign cause and its impact.</p>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campaign Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Campaign Image</label>
                <input type="file" name="image" id="image" accept="image/*" 
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">Upload an image that represents your campaign (max 2MB). Recommended size: 1200x600px</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Donation Link -->
            <div>
                <label for="donation_link" class="block text-sm font-medium text-gray-700 mb-2">Donation Link</label>
                <input type="url" name="donation_link" id="donation_link" value="{{ old('donation_link') }}" 
                    placeholder="https://payment-gateway.com/donate/..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Add the payment gateway or donation platform link. You can add this later if not ready yet.</p>
                @error('donation_link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Save for later)</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Make it live)</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Draft campaigns are only visible to admins. Published campaigns are visible to all alumni.</p>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('manager.campaigns.index') }}" 
                class="inline-flex justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                class="inline-flex justify-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Create Campaign
            </button>
        </div>
    </form>
</div>
@endsection
