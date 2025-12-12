@extends('layouts.dataentry')

@section('title', 'Edit Profile - ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Edit Profile: {{ $user->name }}</h1>
        <a href="{{ route('dataentry.profiles.show', $user->id) }}" class="text-teal-600 hover:text-teal-800">Back to Profile</a>
    </div>

    <form method="POST" action="{{ route('dataentry.profiles.update', $user->id) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="passing_year" class="block text-sm font-medium text-gray-700">Passing Year</label>
                    <select name="passing_year" id="passing_year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ old('passing_year', $user->passing_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                    <select name="course" id="course" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Course</option>
                        @foreach($courses as $category => $courseList)
                            <optgroup label="{{ $category }}">
                                @foreach($courseList as $courseName)
                                    <option value="{{ $courseName }}" {{ old('course', $user->course) === $courseName ? 'selected' : '' }}>{{ $courseName }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="aadhar_number" class="block text-sm font-medium text-gray-700">Aadhar Number</label>
                    <input type="text" name="aadhar_number" id="aadhar_number" value="{{ old('aadhar_number', $user->aadhar_number) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="wedding_anniversary_date" class="block text-sm font-medium text-gray-700">Wedding Anniversary</label>
                    <input type="date" name="wedding_anniversary_date" id="wedding_anniversary_date" value="{{ old('wedding_anniversary_date', $user->wedding_anniversary_date ? $user->wedding_anniversary_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @if($user->profile_image)
                        <p class="mt-1 text-sm text-gray-500">Current: <a href="{{ asset('storage/' . $user->profile_image) }}" target="_blank" class="text-teal-600 hover:text-teal-800">View</a></p>
                    @endif
                </div>
                <div>
                    <label for="proof_document" class="block text-sm font-medium text-gray-700">Proof Document</label>
                    <input type="file" name="proof_document" id="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @if($user->proof_document)
                        <p class="mt-1 text-sm text-gray-500">Current: <a href="{{ asset('storage/' . $user->proof_document) }}" target="_blank" class="text-teal-600 hover:text-teal-800">View</a></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Residence Details -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Residence Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="residence_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="residence_address" id="residence_address" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('residence_address', $user->residence_address) }}</textarea>
                </div>
                <div>
                    <label for="residence_city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="residence_city" id="residence_city" value="{{ old('residence_city', $user->residence_city) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="residence_state" class="block text-sm font-medium text-gray-700">State</label>
                    <input type="text" name="residence_state" id="residence_state" value="{{ old('residence_state', $user->residence_state) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="residence_country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select name="residence_country" id="residence_country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ old('residence_country', $user->residence_country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Employment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                    <input type="text" name="company" id="company" value="{{ old('company', $user->company) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="designation" class="block text-sm font-medium text-gray-700">Designation</label>
                    <input type="text" name="designation" id="designation" value="{{ old('designation', $user->designation) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                    <select name="employment_type" id="employment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select</option>
                        <option value="Govt" {{ old('employment_type', $user->employment_type) === 'Govt' ? 'selected' : '' }}>Govt</option>
                        <option value="Non-Govt" {{ old('employment_type', $user->employment_type) === 'Non-Govt' ? 'selected' : '' }}>Non-Govt</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="employment_address" class="block text-sm font-medium text-gray-700">Employment Address</label>
                    <textarea name="employment_address" id="employment_address" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('employment_address', $user->employment_address) }}</textarea>
                </div>
                <div>
                    <label for="employment_city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="employment_city" id="employment_city" value="{{ old('employment_city', $user->employment_city) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="employment_state" class="block text-sm font-medium text-gray-700">State</label>
                    <input type="text" name="employment_state" id="employment_state" value="{{ old('employment_state', $user->employment_state) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="employment_pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                    <input type="text" name="employment_pincode" id="employment_pincode" value="{{ old('employment_pincode', $user->employment_pincode) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="alternate_email" class="block text-sm font-medium text-gray-700">Alternate Email</label>
                    <input type="email" name="alternate_email" id="alternate_email" value="{{ old('alternate_email', $user->alternate_email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>

        <!-- Bio -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Bio</h2>
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('bio', $user->bio) }}</textarea>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('dataentry.profiles.show', $user->id) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">Cancel</a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-md hover:bg-teal-700">Update Profile</button>
        </div>
    </form>
</div>
@endsection



