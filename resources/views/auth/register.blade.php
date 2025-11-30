@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6 border border-gray-100">
            <div class="text-center">
                <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="mx-auto h-20 w-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Alumni Portal</h1>
                <p class="text-sm text-gray-600 mb-6">Gurukula Kangri (Deemed to be University)</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Create your account</h2>
            </div>
            
            <form id="registerForm" class="mt-8 space-y-5" method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <div class="space-y-5">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            required 
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            value="{{ old('name') }}" 
                            minlength="2" 
                            maxlength="100" 
                            pattern="[a-zA-Z\s\-\'\.]+"
                            placeholder="Enter your full name"
                            autocomplete="name"
                        >
                        <div id="name-error" class="error-message hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span></span>
                        </div>
                        @error('name')
                            <div class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            value="{{ old('email') }}" 
                            maxlength="255" 
                            autocomplete="email"
                            placeholder="your.email@example.com"
                        >
                        <div id="email-error" class="error-message hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span></span>
                        </div>
                        @error('email')
                            <div class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400" 
                                minlength="8" 
                                maxlength="255" 
                                autocomplete="new-password"
                                placeholder="At least 8 characters"
                            >
                            <button 
                                type="button" 
                                id="togglePassword" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <div id="password-error" class="error-message hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span></span>
                        </div>
                        @error('password')
                            <div class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400" 
                                minlength="8" 
                                maxlength="255" 
                                autocomplete="new-password"
                                placeholder="Re-enter your password"
                            >
                            <button 
                                type="button" 
                                id="togglePasswordConfirmation" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eyeIconConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeOffIconConfirmation" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <div id="password_confirmation-error" class="error-message hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span></span>
                        </div>
                        @error('password_confirmation')
                            <div class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="text" 
                            class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400" 
                            value="{{ old('phone') }}" 
                            maxlength="20" 
                            pattern="[\d\s\-\+\(\)]+" 
                            autocomplete="tel"
                            placeholder="+1 (555) 123-4567"
                        >
                        <div id="phone-error" class="error-message hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span></span>
                        </div>
                        @error('phone')
                            <div class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg"
                    >
                        <span id="submitText">Create Account</span>
                        <svg id="submitSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

                <div class="text-center pt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Sign in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const phoneInput = document.getElementById('phone');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    // Password visibility toggles
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    const eyeIconConfirmation = document.getElementById('eyeIconConfirmation');
    const eyeOffIconConfirmation = document.getElementById('eyeOffIconConfirmation');

    togglePassword?.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('hidden');
        eyeOffIcon.classList.toggle('hidden');
    });

    togglePasswordConfirmation?.addEventListener('click', function() {
        const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmationInput.setAttribute('type', type);
        eyeIconConfirmation.classList.toggle('hidden');
        eyeOffIconConfirmation.classList.toggle('hidden');
    });

    // Validation functions
    function showError(fieldId, message) {
        const errorDiv = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        if (errorDiv) {
            errorDiv.querySelector('span').textContent = message;
            errorDiv.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
        }
    }

    function hideError(fieldId) {
        const errorDiv = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        }
    }

    function validateName() {
        const value = nameInput.value.trim();
        if (value.length === 0) {
            showError('name', 'Name is required.');
            return false;
        }
        if (value.length < 2) {
            showError('name', 'Name must be at least 2 characters long.');
            return false;
        }
        if (value.length > 100) {
            showError('name', 'Name cannot exceed 100 characters.');
            return false;
        }
        if (!/^[a-zA-Z\s\-\'\.]+$/.test(value)) {
            showError('name', 'Name can only contain letters, spaces, hyphens, apostrophes, and periods.');
            return false;
        }
        hideError('name');
        return true;
    }

    function validateEmail() {
        const value = emailInput.value.trim();
        if (value.length === 0) {
            showError('email', 'Email address is required.');
            return false;
        }
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(value)) {
            showError('email', 'Please enter a valid email address.');
            return false;
        }
        if (value.length > 255) {
            showError('email', 'Email address cannot exceed 255 characters.');
            return false;
        }
        hideError('email');
        return true;
    }

    function validatePassword() {
        const value = passwordInput.value;
        if (value.length === 0) {
            showError('password', 'Password is required.');
            return false;
        }
        if (value.length < 8) {
            showError('password', 'Password must be at least 8 characters long.');
            return false;
        }
        if (value.length > 255) {
            showError('password', 'Password cannot exceed 255 characters.');
            return false;
        }
        if (!/[a-zA-Z]/.test(value)) {
            showError('password', 'Password must contain at least one letter.');
            return false;
        }
        if (!/[0-9]/.test(value)) {
            showError('password', 'Password must contain at least one number.');
            return false;
        }
        hideError('password');
        return true;
    }

    function validatePasswordConfirmation() {
        const value = passwordConfirmationInput.value;
        const passwordValue = passwordInput.value;
        if (value.length === 0) {
            showError('password_confirmation', 'Please confirm your password.');
            return false;
        }
        if (value !== passwordValue) {
            showError('password_confirmation', 'Password confirmation does not match.');
            return false;
        }
        hideError('password_confirmation');
        return true;
    }

    function validatePhone() {
        const value = phoneInput.value.trim();
        if (value.length === 0) {
            hideError('phone');
            return true; // Optional field
        }
        if (value.length > 20) {
            showError('phone', 'Phone number cannot exceed 20 characters.');
            return false;
        }
        if (!/^[\d\s\-\+\(\)]+$/.test(value)) {
            showError('phone', 'Phone number can only contain digits, spaces, hyphens, plus signs, and parentheses.');
            return false;
        }
        hideError('phone');
        return true;
    }

    // Real-time validation
    nameInput.addEventListener('blur', validateName);
    nameInput.addEventListener('input', function() {
        if (nameInput.value.trim().length > 0) {
            validateName();
        }
    });

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function() {
        if (emailInput.value.trim().length > 0) {
            validateEmail();
        }
    });

    passwordInput.addEventListener('blur', validatePassword);
    passwordInput.addEventListener('input', function() {
        if (passwordInput.value.length > 0) {
            validatePassword();
            if (passwordConfirmationInput.value.length > 0) {
                validatePasswordConfirmation();
            }
        }
    });

    passwordConfirmationInput.addEventListener('blur', validatePasswordConfirmation);
    passwordConfirmationInput.addEventListener('input', function() {
        if (passwordConfirmationInput.value.length > 0) {
            validatePasswordConfirmation();
        }
    });

    phoneInput.addEventListener('blur', validatePhone);
    phoneInput.addEventListener('input', function() {
        if (phoneInput.value.trim().length > 0) {
            validatePhone();
        } else {
            hideError('phone');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isPasswordConfirmationValid = validatePasswordConfirmation();
        const isPhoneValid = validatePhone();

        if (!isNameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmationValid || !isPhoneValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Creating Account...';
        submitSpinner.classList.remove('hidden');
    });
});
</script>

<style>
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input.border-red-500 {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.error-message {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection

