@extends('layouts.app')

@section('title', 'View Profile - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Profile Details</h1>
        <a href="{{ route('admin.profiles.pending') }}" class="text-indigo-600 hover:text-indigo-800">Back to Pending Profiles</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
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
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Photo" class="mt-2 h-32 w-32 rounded-full object-cover">
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

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <form method="POST" action="{{ route('admin.profiles.approve', $user->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700" onclick="return confirm('Are you sure you want to approve this profile?')">
                    Approve Profile
                </button>
            </form>
            <form method="POST" action="{{ route('admin.profiles.block', $user->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700" onclick="return confirm('Are you sure you want to block this profile?')">
                    Block Profile
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

