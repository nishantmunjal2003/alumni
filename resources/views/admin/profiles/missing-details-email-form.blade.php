@extends('layouts.admin')

@section('title', isset($singleUser) ? 'Send Email to ' . $singleUser->name : 'Send Email to Alumni with Missing Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">
                @if(isset($singleUser))
                    Send Email to {{ $singleUser->name }}
                @else
                    Send Email to Alumni with Missing Details
                @endif
            </h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                <span class="font-semibold text-indigo-600">{{ $recipientCount }}</span> 
                {{ $recipientCount == 1 ? 'alumnus' : 'alumni' }} will receive this email
            </p>
        </div>
        <a href="{{ isset($singleUser) ? route('admin.profiles.view', $singleUser->id) : route('admin.profiles.pending') }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to {{ isset($singleUser) ? 'Profile' : 'Pending Profiles' }}
        </a>
    </div>

    <!-- Filter Form - Hide if Single User -->
    @if(!isset($singleUser))
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.profiles.missing-details.email') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="missing_type" class="block text-sm font-medium text-gray-700 mb-2">Filter by Missing Details</label>
                    <select name="missing_type" id="missing_type" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="both" {{ $missingType === 'both' ? 'selected' : '' }}>Both (Proof Document or Enrollment No)</option>
                        <option value="proof_document" {{ $missingType === 'proof_document' ? 'selected' : '' }}>Missing Proof Document Only</option>
                        <option value="enrollment_no" {{ $missingType === 'enrollment_no' ? 'selected' : '' }}>Missing Enrollment No Only</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name, email, course..." class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filters
                </button>
                @if(request('search') || request('missing_type') !== 'both')
                    <a href="{{ route('admin.profiles.missing-details.email') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                <!-- Error display logic stays same -->
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
                <h3 class="text-sm font-medium text-blue-900 mb-2">Recipients ({{ $recipientCount }})</h3>
                <!-- Debug info logic can stay or be simplified -->
                
                <div class="max-h-40 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-blue-800">
                        @foreach($recipients->take(20) as $recipient)
                            <div class="truncate">
                                {{ $recipient->name }} ({{ $recipient->email }})
                                @if(!$recipient->proof_document || trim($recipient->proof_document ?? '') === '')
                                    <span class="text-red-600">[No Proof]</span>
                                @endif
                                @if(!$recipient->enrollment_no || trim($recipient->enrollment_no ?? '') === '')
                                    <span class="text-orange-600">[No Enrollment]</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profiles.missing-details.email.send') }}" class="space-y-6">
                @csrf

                @foreach($recipients as $recipient)
                    <input type="hidden" name="recipient_ids[]" value="{{ $recipient->id }}">
                @endforeach

                <input type="hidden" name="missing_type" value="{{ $missingType }}">

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', 'Please Update Your Missing Profile Details') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter email subject">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" id="message" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your message here...">@if(old('message')){{ old('message') }}@elseif(isset($singleUser))Dear {{ $singleUser->name }},

We noticed that your profile is missing some important details. Please log in to your account and update the following information:

@if(!$singleUser->proof_document)
- Proof Document (ID Card/Marksheet)
@endif
@if(!$singleUser->enrollment_no)
- Enrollment Number
@endif
[Add any other missing details here]

Thank you for your cooperation.

Best regards,
Alumni Portal Team
@else
Dear @{{name}},

We noticed that your profile is missing some important details. Please log in to your account and update the following information:
@if($missingType === 'proof_document' || $missingType === 'both')
- Proof Document (ID Card/Marksheet)
@endif
@if($missingType === 'enrollment_no' || $missingType === 'both')
- Enrollment Number
@endif

Thank you for your cooperation.

Best regards,
Alumni Portal Team
@endif</textarea>
                    <p class="mt-1 text-sm text-gray-500">The message will be personalized. @if(!isset($singleUser)) Use @{{name}} as a placeholder. @endif</p>
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
                        <strong>Warning:</strong> This will send an email to {{ $recipientCount }} recipient(s). Please review your message before sending.
                    </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-6 border-t">
                    <a href="{{ isset($singleUser) ? route('admin.profiles.view', $singleUser->id) : route('admin.profiles.pending') }}" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-300 transition-colors touch-manipulation">
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 transition-colors touch-manipulation inline-flex items-center justify-center gap-2" onclick="return confirm('Are you sure you want to send this email?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email {{ isset($singleUser) ? '' : 'to All' }}
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No recipients found</h3>
                <p class="mt-1 text-sm text-gray-500">No alumni found with missing details based on your filters.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.profiles.pending') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors inline-block">
                        Back to Pending Profiles
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

