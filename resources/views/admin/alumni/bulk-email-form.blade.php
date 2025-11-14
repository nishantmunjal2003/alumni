@extends('layouts.admin')

@section('title', 'Send Email to Alumni')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Send Email to Alumni</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                <span class="font-semibold text-indigo-600">{{ $recipientCount }}</span> 
                {{ ($statusFilter ?? 'active') === 'inactive' ? 'inactive' : 'active' }} {{ $recipientCount == 1 ? 'alumnus' : 'alumni' }} will receive this email
            </p>
        </div>
        <a href="{{ route('admin.alumni.index', request()->except(['page'])) }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Directory
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

        @if($recipientCount > 0)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-medium text-blue-900 mb-2">Recipients ({{ $recipientCount }} {{ ($statusFilter ?? 'active') === 'inactive' ? 'inactive' : 'active' }} alumni)</h3>
                @if(request('passing_year') || request('search') || request('profile_status') || request('status'))
                    <div class="mb-3 text-xs text-blue-700">
                        <strong>Applied Filters:</strong>
                        @if(request('passing_year'))
                            Year: {{ request('passing_year') }}
                        @endif
                        @if(request('search'))
                            {{ request('passing_year') ? ' | ' : '' }}Search: "{{ request('search') }}"
                        @endif
                        @if(request('status'))
                            {{ (request('passing_year') || request('search')) ? ' | ' : '' }}Status: {{ ucfirst(request('status')) }}
                        @endif
                        @if(request('profile_status'))
                            {{ (request('passing_year') || request('search') || request('status')) ? ' | ' : '' }}Profile Status: {{ ucfirst(request('profile_status')) }}
                        @endif
                    </div>
                @endif
                <div class="max-h-40 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-blue-800">
                        @foreach($recipients->take(20) as $recipient)
                            <div class="truncate">{{ $recipient->name }} ({{ $recipient->email }})</div>
                        @endforeach
                        @if($recipientCount > 20)
                            <div class="text-blue-600 font-medium">... and {{ $recipientCount - 20 }} more</div>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.alumni.email.bulk.send') }}" class="space-y-6">
                @csrf

                @foreach($recipients as $recipient)
                    <input type="hidden" name="recipient_ids[]" value="{{ $recipient->id }}">
                @endforeach

                <input type="hidden" name="status_filter" value="{{ $statusFilter ?? 'active' }}">

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter email subject">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" id="message" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your message here...">{{ old('message') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">The message will be personalized with each recipient's name.</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Warning:</strong> This will send an email to all {{ $recipientCount }} {{ ($statusFilter ?? 'active') === 'inactive' ? 'inactive' : 'active' }} alumni{{ $recipientCount > 1 ? ' (filtered list)' : '' }}. Please review your message before sending.
                    </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-6 border-t">
                    <a href="{{ route('admin.alumni.index', request()->except(['page'])) }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-300 transition-colors touch-manipulation">
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 transition-colors touch-manipulation inline-flex items-center justify-center gap-2" onclick="return confirm('Are you sure you want to send this email to {{ $recipientCount }} recipients?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email to All
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No recipients found</h3>
                <p class="mt-1 text-sm text-gray-500">Please adjust your filters to select recipients.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.alumni.index') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors inline-block">
                        Back to Directory
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

