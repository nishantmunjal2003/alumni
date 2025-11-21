@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Edit Your Profile</h1>
            <p class="text-indigo-100">
                Update your profile information, proof document, and photo as needed.
            </p>
        </div>

        <div class="p-6 md:p-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('alumni.update', $user->id) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Alumni Details Section -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-1 w-12 bg-indigo-600 rounded"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Alumni Details</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" disabled>
                            <p class="text-xs text-gray-500 mt-1">This field is taken from your registration details</p>
                        </div>

                        <div>
                            <label for="enrollment_no" class="block text-sm font-medium text-gray-700">Enrollment No. <span class="text-red-500">*</span></label>
                            <input type="text" name="enrollment_no" id="enrollment_no" value="{{ old('enrollment_no', $user->enrollment_no) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('enrollment_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passing_year" class="block text-sm font-medium text-gray-700">Passing Year <span class="text-red-500">*</span></label>
                            <select name="passing_year" id="passing_year" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ old('passing_year', $user->passing_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('passing_year')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700">Course/Major <span class="text-red-500">*</span></label>
                            <select name="course" id="course" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Course</option>
                                @foreach($courses as $category => $courseList)
                                    <optgroup label="{{ $category }}">
                                        @foreach($courseList as $courseName)
                                            <option value="{{ $courseName }}" {{ old('course', $user->course) === $courseName ? 'selected' : '' }}>{{ $courseName }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
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

                        <div class="md:col-span-2">
                            <label for="proof_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Proof Document (ID Card/Marksheet) 
                                <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <div class="mt-1">
                                <label for="proof_document" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PDF, JPG, PNG (MAX. 5MB)</p>
                                    </div>
                                    <input type="file" name="proof_document" id="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @if($user->proof_document)
                                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                                    <p class="text-sm text-green-800">
                                        <span class="font-medium">✓ Document uploaded:</span> 
                                        <a href="{{ asset('storage/' . $user->proof_document) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 underline">View Document</a>
                                    </p>
                                </div>
                            @endif
                            @error('proof_document')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Photo 
                                <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <div class="mt-1">
                                <label for="profile_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">JPG, PNG (MAX. 2MB)</p>
                                    </div>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                                </label>
                            </div>
                            @if($user->profile_image)
                                <div class="mt-3 flex items-center gap-4">
                                    <img src="{{ $user->profile_image_url }}" alt="Profile Photo" class="h-24 w-24 rounded-full object-cover border-2 border-indigo-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Current Photo</p>
                                        <p class="text-xs text-gray-500">Click above to change</p>
                                    </div>
                                </div>
                            @endif
                            @error('profile_image')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Employment Details Section -->
                <div class="border-b border-gray-200 pb-8 mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-1 w-12 bg-indigo-600 rounded"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Employment Details</h2>
                    </div>
                    
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

                <!-- Submit Button Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-500">
                            <span class="text-red-500">*</span> indicates required fields
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                            <a href="{{ route('alumni.show', $user->id) }}" class="w-full sm:w-auto bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors shadow-md hover:shadow-lg text-center">
                                Cancel
                            </a>
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-md hover:shadow-lg">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Profile
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // File input preview functionality
    document.getElementById('proof_document')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const label = this.closest('label');
            const text = label.querySelector('p.text-sm');
            if (text) {
                text.innerHTML = `<span class="font-semibold text-green-600">✓ ${file.name}</span>`;
            }
        }
    });

    document.getElementById('profile_image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const label = document.getElementById('profile_image').closest('label');
                const preview = label.querySelector('img');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-24 w-24 rounded-full object-cover border-2 border-indigo-200 mt-3';
                    label.parentElement.appendChild(img);
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
