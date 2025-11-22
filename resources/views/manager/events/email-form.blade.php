@extends('layouts.manager')

@section('title', 'Send Event Announcement')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Send Event Announcement</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                <span class="font-semibold text-indigo-600">{{ $recipientCount }}</span> 
                active alumni will receive this Event Announcement
            </p>
        </div>
        <a href="{{ route('manager.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to events
        </a>
    </div>

    <!-- Event Info Card -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-2">{{ $event->title }}</h2>
        <p class="text-indigo-100 text-sm mb-4">{{ Str::limit($event->description, 150) }}</p>
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $event->event_start_date->format('M d, Y') }}
                @if($event->event_end_date)
                    - {{ $event->event_end_date->format('M d, Y') }}
                @endif
            </span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $event->venue }}
            </span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Recipients</h2>
        <form method="GET" action="{{ route('manager.events.email', $event->id) }}" id="filter-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Name, email, course..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="passing_year" class="block text-sm font-medium text-gray-700 mb-2">Passing Year</label>
                    <select name="passing_year" id="passing_year" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Years</option>
                        @foreach($passingYears as $year)
                            <option value="{{ $year }}" {{ request('passing_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active" {{ $statusFilter == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $statusFilter == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="profile_status" class="block text-sm font-medium text-gray-700 mb-2">Profile Status</label>
                    <select name="profile_status" id="profile_status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ $profileStatusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $profileStatusFilter == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="blocked" {{ $profileStatusFilter == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('manager.events.email', $event->id) }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-md hover:bg-gray-300 transition-colors">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        <div class="mt-2 text-sm text-red-700">
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
                <h3 class="text-sm font-medium text-blue-900 mb-2">
                    Recipients ({{ $recipientCount }} {{ $statusFilter == 'inactive' ? 'inactive' : 'active' }} alumni)
                </h3>
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
                @else
                    <p class="text-xs text-blue-700 mb-3">
                        All active alumni will receive this Event Announcement email.
                    </p>
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

            <form method="POST" action="{{ route('manager.events.email.send', $event->id) }}" class="space-y-6">
                @csrf

                @foreach($recipients as $recipient)
                    <input type="hidden" name="recipient_ids[]" value="{{ $recipient->id }}">
                @endforeach

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Email Subject *</label>
                    <input type="text" name="subject" id="subject" 
                        value="{{ old('subject', 'Event Announcement: ' . $event->title) }}" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="Enter email subject">
                    <p class="mt-1 text-xs text-gray-500">A default subject has been pre-filled, but you can customize it.</p>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Email Message *</label>
                    <textarea name="message" id="message" rows="12" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="Enter your Event Announcement message here...">{{ old('message', "Dear Alumni,\n\nWe are excited to announce a new event:\n\n" . $event->title . "\n\n" . Str::limit($event->description, 200) . "\n\nDate: " . $event->event_start_date->format('M d, Y') . ($event->event_end_date ? " - " . $event->event_end_date->format('M d, Y') : "") . "\nVenue: " . $event->venue . "\n\n" . ($event->google_maps_link ? "Location: " . $event->google_maps_link . "\n\n" : "") . "We hope to see you there!\n\nBest regards,\nAlumni Portal Team") }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        The message will be personalized with each recipient's name. You can include event details, venue information, and any other relevant information.
                    </p>
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
                                <strong>Warning:</strong> This will send an email to {{ $recipientCount }} {{ $statusFilter == 'inactive' ? 'inactive' : 'active' }} alumni{{ request('passing_year') || request('search') || request('profile_status') ? ' (filtered list)' : '' }}. Please review your message carefully before sending.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-6 border-t">
                    <a href="{{ route('manager.events.index') }}" 
                        class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="w-full sm:w-auto bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 transition-colors inline-flex items-center justify-center gap-2" 
                        onclick="return confirm('Are you sure you want to send this Event Announcement to {{ $recipientCount }} recipients?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Event Announcement
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No recipients found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no active alumni with approved profiles to send the Event Announcement to.</p>
                <div class="mt-6">
                    <a href="{{ route('manager.events.index') }}" 
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors inline-block">
                        Back to Events
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

