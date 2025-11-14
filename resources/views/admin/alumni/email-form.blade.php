@extends('layouts.admin')

@section('title', 'Send Email to ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Send Email</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">To: {{ $user->name }} ({{ $user->email }})</p>
        </div>
        <a href="{{ route('admin.alumni.view', $user->id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Profile
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Please correct the following errors:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.alumni.email.send', $user->id) }}" class="space-y-6">
            @csrf

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter email subject">
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea name="message" id="message" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your message here...">{{ old('message') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">The message will be personalized with the recipient's name.</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-6 border-t">
                <a href="{{ route('admin.alumni.view', $user->id) }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-300 transition-colors touch-manipulation">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 transition-colors touch-manipulation inline-flex items-center justify-center gap-2">
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

