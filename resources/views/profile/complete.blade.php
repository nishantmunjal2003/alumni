@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-2">Complete Your Profile</h1>
        <p class="text-gray-600 mb-6">Please fill in all required details to access the dashboard. Your profile will be reviewed by the administrator.</p>

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif

        @if($user->profile_status === 'pending')
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                Your profile is pending approval. Please wait for admin review.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Alumni Details Section -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Alumni Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" disabled>
                        <p class="text-xs text-gray-500 mt-1">This field is taken from your registration details</p>
                    </div>

                    <div>
                        <label for="passing_year" class="block text-sm font-medium text-gray-700">Passing Year <span class="text-red-500">*</span></label>
                        <input type="text" name="passing_year" id="passing_year" value="{{ old('passing_year', $user->passing_year) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('passing_year')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course/Major <span class="text-red-500">*</span></label>
                        <select name="course" id="course" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Course</option>
                            <option value="B.Tech CSE" {{ old('course', $user->course) === 'B.Tech CSE' ? 'selected' : '' }}>B.Tech CSE</option>
                            <option value="B.Tech ECE" {{ old('course', $user->course) === 'B.Tech ECE' ? 'selected' : '' }}>B.Tech ECE</option>
                            <option value="B.Tech EE" {{ old('course', $user->course) === 'B.Tech EE' ? 'selected' : '' }}>B.Tech EE</option>
                            <option value="B.Tech ME" {{ old('course', $user->course) === 'B.Tech ME' ? 'selected' : '' }}>B.Tech ME</option>
                        </select>
                        @error('course')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="aadhar_number" class="block text-sm font-medium text-gray-700">Aadhar Number (Optional)</label>
                        <input type="text" name="aadhar_number" id="aadhar_number" value="{{ old('aadhar_number', $user->aadhar_number) }}" maxlength="12" pattern="[0-9]{12}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('aadhar_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth (Optional)</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="wedding_anniversary_date" class="block text-sm font-medium text-gray-700">Wedding Anniversary Date (Optional)</label>
                        <input type="date" name="wedding_anniversary_date" id="wedding_anniversary_date" value="{{ old('wedding_anniversary_date', $user->wedding_anniversary_date ? $user->wedding_anniversary_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('wedding_anniversary_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="residence_address" class="block text-sm font-medium text-gray-700">Current Residence Address <span class="text-red-500">*</span></label>
                        <textarea name="residence_address" id="residence_address" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('residence_address', $user->residence_address) }}</textarea>
                        @error('residence_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="residence_city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                        <input type="text" name="residence_city" id="residence_city" value="{{ old('residence_city', $user->residence_city) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('residence_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="residence_state" class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                        <input type="text" name="residence_state" id="residence_state" value="{{ old('residence_state', $user->residence_state) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('residence_state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="residence_country" class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                        <select name="residence_country" id="residence_country" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                @php
                                    $selectedCountry = old('residence_country', $user->residence_country);
                                    if (empty($selectedCountry)) {
                                        $selectedCountry = 'India';
                                    }
                                @endphp
                                <option value="{{ $country }}" {{ $selectedCountry === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('residence_country')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proof_document" class="block text-sm font-medium text-gray-700">Proof Document (ID Card/Marksheet) <span class="text-red-500">*</span></label>
                        <input type="file" name="proof_document" id="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full">
                        @if($user->proof_document)
                            <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/' . $user->proof_document) }}" target="_blank" class="text-indigo-600">View Document</a></p>
                        @endif
                        @error('proof_document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                    </div>

                    <div>
                        <label for="profile_image" class="block text-sm font-medium text-gray-700">Upload Photo <span class="text-red-500">*</span></label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full">
                        @if($user->profile_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @endif
                        @error('profile_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG (Max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Employment Details Section -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Employment Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700">Company <span class="text-red-500">*</span></label>
                        <input type="text" name="company" id="company" value="{{ old('company', $user->company) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('company')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-700">Designation <span class="text-red-500">*</span></label>
                        <input type="text" name="designation" id="designation" value="{{ old('designation', $user->designation) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('designation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700">Govt/Non-Govt <span class="text-red-500">*</span></label>
                        <select name="employment_type" id="employment_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="Govt" {{ old('employment_type', $user->employment_type) === 'Govt' ? 'selected' : '' }}>Govt</option>
                            <option value="Non-Govt" {{ old('employment_type', $user->employment_type) === 'Non-Govt' ? 'selected' : '' }}>Non-Govt</option>
                        </select>
                        @error('employment_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="employment_address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                        <textarea name="employment_address" id="employment_address" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('employment_address', $user->employment_address) }}</textarea>
                        @error('employment_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_city" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                        <input type="text" name="employment_city" id="employment_city" value="{{ old('employment_city', $user->employment_city) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('employment_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_state" class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                        <input type="text" name="employment_state" id="employment_state" value="{{ old('employment_state', $user->employment_state) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('employment_state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_pincode" class="block text-sm font-medium text-gray-700">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="employment_pincode" id="employment_pincode" value="{{ old('employment_pincode', $user->employment_pincode) }}" maxlength="6" pattern="[0-9]{6}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('employment_pincode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alternate_email" class="block text-sm font-medium text-gray-700">Alternate Email (Optional)</label>
                        <input type="email" name="alternate_email" id="alternate_email" value="{{ old('alternate_email', $user->alternate_email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('alternate_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn Profile Link (Optional)</label>
                        <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('linkedin_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Submit Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

