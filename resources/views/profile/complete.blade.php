@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')
<style>
    /* Force light mode - override any dark mode styles */
    html, body {
        color-scheme: light !important;
    }
    html.dark, body.dark {
        background-color: #f3f4f6 !important;
    }
    html.dark *, body.dark * {
        --tw-bg-opacity: 1;
    }

    /* Custom Transitions for Form Steps */
    .form-step {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }
    .form-step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Input Focus Effects */
    .input-premium:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    /* Step Indicator Progress Bar */
    .progress-track {
        transition: width 0.4s ease-in-out;
    }
</style>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header & Progress -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-3">
                {{ $isEdit ? 'Update Profile' : 'Setup Profile' }}
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ $isEdit ? 'Keep your information up to date.' : 'Let’s get you on board! Complete your profile to access all features.' }}
            </p>

            <!-- Progress Indicator -->
            <div class="mt-8 relative max-w-2xl mx-auto">
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 progress-track" style="width: 33%"></div>
                </div>
                <div class="flex justify-between text-xs font-semibold text-gray-500 mt-2 uppercase tracking-wider">
                    <span id="step1-label" class="text-indigo-600">Personal</span>
                    <span id="step2-label">Contact</span>
                    <span id="step3-label">Documents</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative">
            <!-- Decorative Top Gradient -->
            <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            <div class="p-8 sm:p-10">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3"><p class="text-sm text-green-700">{{ session('success') }}</p></div>
                        </div>
                    </div>
                @endif
                @if($user->profile_status === 'pending')
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3"><p class="text-sm text-blue-700">Your profile will be reviewed by the administrator after you complete this form. Until then, you will have limited access to the dashboard.</p></div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ $isEdit ? route('profile.update') : route('profile.store') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <!-- STEP 1: Personal & Education -->
                    <div id="step1" class="form-step active">
                        <div class="mb-6 pb-2 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Academic & Personal Info</h3>
                            <span class="text-sm text-gray-400 font-medium">Step 1 of 3</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="input-premium block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg sm:text-sm shadow-sm focus:outline-none font-medium transition-colors">
                                </div>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email (Read Only) -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-gray-400 font-normal">(Cannot be changed)</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" disabled class="bg-gray-100 text-gray-500 block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg sm:text-sm shadow-sm cursor-not-allowed">
                                </div>
                            </div>

                            <!-- Course -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="course" class="block text-sm font-semibold text-gray-700 mb-2">Course / Major <span class="text-red-500">*</span></label>
                                <select name="course" id="course" required class="input-premium block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none sm:text-sm rounded-lg border transition-colors bg-white">
                                    <option value="">Select your course...</option>
                                    @foreach($courses as $category => $courseList)
                                        <optgroup label="{{ $category }}" class="font-bold text-gray-900 not-italic">
                                            @foreach($courseList as $courseName)
                                                <option value="{{ $courseName }}" {{ old('course', $user->course) === $courseName ? 'selected' : '' }} class="text-gray-700 font-normal">{{ $courseName }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('course') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Passing Year -->
                            <div>
                                <label for="passing_year" class="block text-sm font-semibold text-gray-700 mb-2">Passing Year <span class="text-red-500">*</span></label>
                                <select name="passing_year" id="passing_year" required class="input-premium block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors">
                                    <option value="">Select Year</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ old('passing_year', $user->passing_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('passing_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Enrollment No -->
                            <div>
                                <label for="enrollment_no" class="block text-sm font-semibold text-gray-700 mb-2">Enrollment No. <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                                <input type="text" name="enrollment_no" id="enrollment_no" value="{{ old('enrollment_no', $user->enrollment_no) }}" class="input-premium block w-full py-3 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors" placeholder="e.g. 123456">
                                @error('enrollment_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Dates: DOB & Anniversary -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" class="input-premium block w-full py-3 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors">
                            </div>
                            <div>
                                <label for="wedding_anniversary_date" class="block text-sm font-semibold text-gray-700 mb-2">Anniversary <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                                <input type="date" name="wedding_anniversary_date" id="wedding_anniversary_date" value="{{ old('wedding_anniversary_date', $user->wedding_anniversary_date ? $user->wedding_anniversary_date->format('Y-m-d') : '') }}" class="input-premium block w-full py-3 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors">
                            </div>
                            
                            <!-- Aadhar -->
                            <div>
                                <label for="aadhar_number" class="block text-sm font-semibold text-gray-700 mb-2">Aadhar Number <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                                <input type="text" name="aadhar_number" id="aadhar_number" value="{{ old('aadhar_number', $user->aadhar_number) }}" maxlength="12" pattern="[0-9]{12}" class="input-premium block w-full py-3 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors" placeholder="12-digit number">
                                @error('aadhar_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="nextStep(2)" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                                Next Step
                                <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Contact & Location -->
                    <div id="step2" class="form-step">
                        <div class="mb-6 pb-2 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Contact & Residence</h3>
                            <span class="text-sm text-gray-400 font-medium">Step 2 of 3</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <!-- Phone -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </div>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required class="input-premium block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors" placeholder="+91 98765 43210">
                                </div>
                                <div class="mt-2 flex items-center">
                                    <input type="hidden" name="is_phone_public" value="0">
                                    <input type="checkbox" name="is_phone_public" id="is_phone_public" value="1" {{ old('is_phone_public', $user->is_phone_public ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_phone_public" class="ml-2 block text-sm text-gray-700">Allow other alumni to see my phone number</label>
                                </div>
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="residence_address" class="block text-sm font-semibold text-gray-700 mb-2">Current Residence Address <span class="text-red-500">*</span></label>
                                <textarea name="residence_address" id="residence_address" rows="3" required class="input-premium block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors" placeholder="Street address, Apartment, etc.">{{ old('residence_address', $user->residence_address) }}</textarea>
                                @error('residence_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- City, State, Country -->
                            <div>
                                <label for="residence_city" class="block text-sm font-semibold text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                                <input type="text" name="residence_city" id="residence_city" value="{{ old('residence_city', $user->residence_city) }}" required class="input-premium block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors">
                                @error('residence_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label for="residence_state" class="block text-sm font-semibold text-gray-700 mb-2">State <span class="text-red-500">*</span></label>
                                <input type="text" name="residence_state" id="residence_state" value="{{ old('residence_state', $user->residence_state) }}" required class="input-premium block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm transition-colors">
                                @error('residence_state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label for="residence_country" class="block text-sm font-semibold text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                                <select name="residence_country" id="residence_country" required class="input-premium block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none sm:text-sm bg-white transition-colors">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        @php
                                            $selectedCountry = old('residence_country', $user->residence_country);
                                            if (empty($selectedCountry)) $selectedCountry = 'India';
                                        @endphp
                                        <option value="{{ $country }}" {{ $selectedCountry === $country ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" onclick="prevStep(1)" class="inline-flex justify-center py-3 px-6 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Back
                            </button>
                            <button type="button" onclick="nextStep(3)" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                                Next Step
                                <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Documents & Uploads -->
                    <div id="step3" class="form-step">
                        <div class="mb-6 pb-2 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Verification & ID</h3>
                            <span class="text-sm text-gray-400 font-medium">Step 3 of 3</span>
                        </div>

                        <div class="space-y-8">
                            <!-- Proof Document -->
                            <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-100">
                                <label class="block text-base font-bold text-gray-900 mb-2">Proof Document (ID Card / Marksheet) <span class="text-indigo-500 font-normal text-sm ml-1">(Highly Recommended)</span></label>
                                <p class="text-sm text-gray-600 mb-4">Required within 7 days to keep your account active.</p>
                                
                                <div class="relative">
                                    <input type="file" name="proof_document" id="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                    <label for="proof_document" class="flex flex-col items-center justify-center w-full h-40 border-2 border-indigo-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-indigo-50 transition-colors group">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div class="bg-indigo-100 rounded-full p-3 mb-3 group-hover:bg-indigo-200 transition-colors">
                                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            </div>
                                            <p class="mb-1 text-sm text-gray-700 font-medium" id="proof-text"><span class="font-bold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500">PDF, JPG, PNG (Max 5MB)</p>
                                        </div>
                                    </label>
                                </div>
                                @if($user->proof_document)
                                    <div class="mt-3 flex items-center text-sm text-green-700 bg-green-100 p-2 rounded">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Document currently uploaded. <a href="{{ asset('storage/' . $user->proof_document) }}" target="_blank" class="ml-1 underline font-semibold">View</a>
                                    </div>
                                @endif
                                @error('proof_document') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Profile Photo -->
                            <div class="flex items-start space-x-6">
                                <div class="flex-shrink-0">
                                    @if($user->profile_image)
                                        <img id="preview-image" src="{{ $user->profile_image_url }}" alt="Profile" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md">
                                    @else
                                        <div id="preview-placeholder" class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-white shadow-md">
                                            <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-bold text-gray-900 mb-1">Profile Photo <span class="text-gray-400 font-normal">(Optional)</span></label>
                                    <p class="text-xs text-gray-500 mb-3">Upload a clean headshot. JPG/PNG, max 2MB.</p>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                    @error('profile_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-between pt-6 border-t border-gray-100">
                            <button type="button" onclick="prevStep(2)" class="inline-flex justify-center py-3 px-6 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 warning-transition">
                                Back
                            </button>
                            <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-lg text-sm font-bold rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all">
                                {{ $isEdit ? 'Save Changes' : 'Complete Profile' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<script>
    function nextStep(step) {
        // Validate current step before moving (Simple check)
        const currentStep = document.querySelector('.form-step.active');
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value) {
                isValid = false;
                input.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                // Shake animation effect could be added here
            } else {
                input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            }
        });

        if (!isValid) {
            // Optional: Show a toast or alert
            return;
        }

        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step' + step).classList.add('active');
        
        // Update Progress Bar
        const bar = document.getElementById('progressBar');
        const labels = document.querySelectorAll('#step1-label, #step2-label, #step3-label');
        
        labels.forEach(l => {
            if(l) l.classList.remove('text-indigo-600', 'font-bold');
        });
        
        if (step === 1) {
            bar.style.width = '33%';
            document.getElementById('step1-label').classList.add('text-indigo-600', 'font-bold');
        } else if (step === 2) {
            bar.style.width = '66%';
            document.getElementById('step2-label').classList.add('text-indigo-600', 'font-bold');
        } else if (step === 3) {
            bar.style.width = '100%';
            document.getElementById('step3-label').classList.add('text-indigo-600', 'font-bold');
        }
    }

    function prevStep(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step' + step).classList.add('active');
        
        // Update Progress Bar
        const bar = document.getElementById('progressBar');
        const labels = document.querySelectorAll('#step1-label, #step2-label, #step3-label');
         labels.forEach(l => {
             if(l) l.classList.remove('text-indigo-600', 'font-bold');
         });

        if (step === 1) {
            bar.style.width = '33%';
            document.getElementById('step1-label').classList.add('text-indigo-600', 'font-bold');
        } else if (step === 2) {
            bar.style.width = '66%';
            document.getElementById('step2-label').classList.add('text-indigo-600', 'font-bold');
        }
    }

    // File Input Preview
    document.getElementById('proof_document').addEventListener('change', function(e) {
        if (e.target.files[0]) {
            document.getElementById('proof-text').innerHTML = `<span class="text-green-600 font-bold">✓ Selected:</span> ${e.target.files[0].name}`;
        }
    });

    // Image Preview
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-image');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Replace placeholder with image
                    const placeholder = document.getElementById('preview-placeholder');
                    const newImg = document.createElement('img');
                    newImg.id = 'preview-image';
                    newImg.src = e.target.result;
                    newImg.className = 'h-24 w-24 rounded-full object-cover border-4 border-white shadow-md';
                    placeholder.parentNode.replaceChild(newImg, placeholder);
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
