@extends('layouts.admin')

@section('title', 'View Alumni - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-4 sm:px-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold">Alumni Profile: {{ $user->name }}</h1>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 items-start sm:items-center">
            <a href="{{ route('admin.alumni.email', $user->id) }}" class="w-full sm:w-auto text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors touch-manipulation inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Send Email
            </a>
            <a href="{{ route('admin.alumni.index') }}" class="text-sm sm:text-base text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Directory
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <!-- Alumni Details -->
        <div class="border-b pb-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Alumni Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Enrollment No.</label>
                    <p class="mt-1 text-gray-900">{{ $user->enrollment_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Passing Year</label>
                    <p class="mt-1 text-gray-900">{{ $user->passing_year ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Course/Major</label>
                    <p class="mt-1 text-gray-900">{{ $user->course ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Aadhar Number</label>
                    <p class="mt-1 text-gray-900">{{ $user->aadhar_number ?? 'N/A' }}</p>
                </div>
                @if($user->date_of_birth)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="mt-1 text-gray-900">{{ $user->date_of_birth->format('F d, Y') }}</p>
                    </div>
                @endif
                @if($user->wedding_anniversary_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Wedding Anniversary Date</label>
                        <p class="mt-1 text-gray-900">{{ $user->wedding_anniversary_date->format('F d, Y') }}</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Current Residence Address</label>
                    <p class="mt-1 text-gray-900">{{ $user->residence_address ?? 'N/A' }}</p>
                </div>
                @if($user->residence_city)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">City</label>
                        <p class="mt-1 text-gray-900">{{ $user->residence_city }}</p>
                    </div>
                @endif
                @if($user->residence_state)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">State</label>
                        <p class="mt-1 text-gray-900">{{ $user->residence_state }}</p>
                    </div>
                @endif
                @if($user->residence_country)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Country</label>
                        <p class="mt-1 text-gray-900">{{ $user->residence_country }}</p>
                    </div>
                @endif
                @if($user->proof_document)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Proof Document</label>
                        <a href="{{ asset('storage/' . $user->proof_document) }}" target="_blank" class="mt-1 text-indigo-600 hover:text-indigo-800">View Document</a>
                    </div>
                @endif
                @if($user->profile_image)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Profile Photo</label>
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Photo" class="mt-2 h-32 w-32 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="mt-2 h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center hidden">
                            <span class="text-indigo-600 font-semibold text-2xl">{{ getUserInitials($user->name) }}</span>
                        </div>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Profile Photo</label>
                        <div class="mt-2 h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold text-2xl">{{ getUserInitials($user->name) }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Employment Details -->
        <div class="border-b pb-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Employment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Company</label>
                    <p class="mt-1 text-gray-900">{{ $user->company ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Designation</label>
                    <p class="mt-1 text-gray-900">{{ $user->designation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Employment Type</label>
                    <p class="mt-1 text-gray-900">{{ $user->employment_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-gray-900">{{ $user->employment_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">City</label>
                    <p class="mt-1 text-gray-900">{{ $user->employment_city ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">State</label>
                    <p class="mt-1 text-gray-900">{{ $user->employment_state ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Pincode</label>
                    <p class="mt-1 text-gray-900">{{ $user->employment_pincode ?? 'N/A' }}</p>
                </div>
                @if($user->alternate_email)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Alternate Email</label>
                        <p class="mt-1 text-gray-900">{{ $user->alternate_email }}</p>
                    </div>
                @endif
                @if($user->linkedin_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">LinkedIn Profile</label>
                        <a href="{{ $user->linkedin_url }}" target="_blank" class="mt-1 text-indigo-600 hover:text-indigo-800">{{ $user->linkedin_url }}</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bio Section -->
        @if($user->bio)
        <div class="border-b pb-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Bio</h2>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $user->bio }}</p>
        </div>
        @endif

        <!-- Status Information -->
        <div class="border-b pb-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Account Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Status</label>
                    <p class="mt-1">
                        @if($user->status === 'active')
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Profile Status</label>
                    <p class="mt-1">
                        @if($user->profile_status === 'approved')
                            <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">Approved</span>
                        @elseif($user->profile_status === 'pending')
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Blocked</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Profile Completed</label>
                    <p class="mt-1">
                        @if($user->profile_completed)
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Yes</span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">No</span>
                        @endif
                    </p>
                </div>
                @if($user->profile_submitted_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Profile Submitted</label>
                        <p class="mt-1 text-gray-900">{{ $user->profile_submitted_at->format('F d, Y g:i A') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 pt-6 border-t">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="w-full sm:w-auto text-center bg-indigo-600 text-white px-6 py-2.5 rounded-md hover:bg-indigo-700 transition-colors touch-manipulation inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection

