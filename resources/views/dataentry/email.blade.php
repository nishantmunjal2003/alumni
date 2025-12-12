@extends('layouts.dataentry')

@section('title', 'Send Email - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold">Send Email to {{ $user->name }}</h1>
        <a href="{{ route('dataentry.profiles.show', $user->id) }}" class="text-sm sm:text-base text-teal-600 hover:text-teal-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Profile
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        @if(count($missingFields) > 0)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-amber-800">Missing Profile Details</h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <p class="mb-2">The following fields are missing from the profile:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($missingFields as $field)
                                <li>{{ $field }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Profile Complete</h3>
                    <p class="mt-1 text-sm text-green-700">All required profile fields are filled. You can still send a custom message to the user.</p>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('dataentry.profiles.email.send', $user->id) }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="recipient" class="block text-sm font-medium text-gray-700 mb-2">
                    Recipient
                </label>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-md">
                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center">
                        <span class="text-teal-600 font-semibold text-sm">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Subject <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="subject" 
                    id="subject" 
                    value="{{ old('subject', 'Complete Your Alumni Profile') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                    required
                >
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Message <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="message" 
                    id="message" 
                    rows="15"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 font-mono text-sm"
                    required
                >{{ old('message', $defaultMessage) }}</textarea>
                @error('message')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">
                    Tip: You can edit the message above to customize it before sending.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t">
                <a href="{{ route('dataentry.profiles.show', $user->id) }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 text-white px-6 py-2.5 rounded-md hover:bg-teal-700 transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Email
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



