@extends('layouts.app')

@section('title', 'Update Employment Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Employment Details</h1>
            <p class="mt-2 text-gray-600">Keep your professional information current to connect with peers.</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <form method="POST" action="{{ route('profile.employment.update') }}" class="p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Company -->
                    <div>
                        <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">Company / Organization</label>
                        <input type="text" name="company" id="company" value="{{ old('company', $user->company) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" placeholder="e.g. Google, TCS, Govt of India">
                        @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label for="designation" class="block text-sm font-semibold text-gray-700 mb-2">Designation / Role</label>
                        <input type="text" name="designation" id="designation" value="{{ old('designation', $user->designation) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" placeholder="e.g. Senior Developer">
                        @error('designation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label for="employment_type" class="block text-sm font-semibold text-gray-700 mb-2">Employment Type</label>
                        <select name="employment_type" id="employment_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border bg-white">
                            <option value="">Select Type</option>
                            <option value="Govt" {{ old('employment_type', $user->employment_type) === 'Govt' ? 'selected' : '' }}>Government</option>
                            <option value="Non-Govt" {{ old('employment_type', $user->employment_type) === 'Non-Govt' ? 'selected' : '' }}>Private / Corporate</option>
                            <option value="Business" {{ old('employment_type', $user->employment_type) === 'Business' ? 'selected' : '' }}>Business / Self-Employed</option>
                            <option value="Other" {{ old('employment_type', $user->employment_type) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('employment_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Work Address -->
                    <div>
                        <label for="employment_address" class="block text-sm font-semibold text-gray-700 mb-2">Work Address</label>
                        <textarea name="employment_address" id="employment_address" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">{{ old('employment_address', $user->employment_address) }}</textarea>
                        @error('employment_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Work Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="employment_city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input type="text" name="employment_city" id="employment_city" value="{{ old('employment_city', $user->employment_city) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">
                            @error('employment_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                         <div>
                            <label for="employment_state" class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                            <input type="text" name="employment_state" id="employment_state" value="{{ old('employment_state', $user->employment_state) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">
                            @error('employment_state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                         <div class="col-span-1 md:col-span-2">
                            <label for="employment_country" class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <select name="employment_country" id="employment_country" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border bg-white">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('employment_country', $user->employment_country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                            @error('employment_country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Employment Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
