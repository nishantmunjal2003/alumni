@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6 border border-gray-100">
            <div class="text-center">
                <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" class="mx-auto h-20 w-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Alumni Portal</h1>
                <p class="text-sm text-gray-600 mb-6">Gurukula Kangri (Deemed to be University)</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Verify Your Email</h2>
                <p class="mt-2 text-sm text-gray-600">We've sent a verification code to <strong class="text-indigo-600">{{ $email }}</strong></p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->has('otp'))
                <div class="bg-red-50 border-2 border-red-500 p-5 rounded-lg shadow-lg animate-slideDown" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-base font-bold text-red-900 mb-2">❌ Incorrect Verification Code</h3>
                            <p class="text-sm font-semibold text-red-800 mb-3">{{ $errors->first('otp') }}</p>
                            <div class="bg-white border border-red-200 rounded p-3 mt-3">
                                <p class="text-sm font-medium text-red-900 mb-2">Please try again:</p>
                                <ul class="text-xs text-red-700 space-y-1 list-disc list-inside">
                                    <li>Double-check the 6-digit code from your email</li>
                                    <li>Make sure you entered all digits correctly</li>
                                    <li>The code expires in 10 minutes</li>
                                    <li>You can request a new code if needed</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($attemptsRemaining < 3 && $attemptsRemaining > 0)
                <div class="bg-yellow-50 border-2 border-yellow-400 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-yellow-800">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                    {{ $attemptsRemaining }} {{ $attemptsRemaining === 1 ? 'Attempt' : 'Attempts' }} Left
                                </span>
                                You have <strong>{{ $attemptsRemaining }}</strong> {{ $attemptsRemaining === 1 ? 'attempt' : 'attempts' }} remaining before you'll need to register again.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form id="otpForm" class="mt-6 space-y-5" method="POST" action="{{ route('verify.otp') }}" novalidate>
                @csrf
                <div>
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-1.5">Verification Code</label>
                    <input 
                        id="otp" 
                        name="otp" 
                        type="text" 
                        required 
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="w-full px-4 py-3 border {{ $errors->has('otp') ? 'border-red-500 bg-red-50 animate-shake' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('otp') ? 'focus:ring-red-500 focus:border-red-500' : 'focus:ring-indigo-500 focus:border-transparent' }} transition-all duration-200 text-center text-3xl tracking-[0.5em] font-mono text-gray-900 placeholder-gray-400" 
                        placeholder="000000"
                        value=""
                        autofocus
                    >
                    @error('otp')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm font-medium text-red-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        </div>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 text-center">
                        @if($errors->has('otp'))
                            <span class="text-red-600 font-medium">Please enter the correct 6-digit code and try again.</span>
                        @else
                            Enter the 6-digit code sent to your email. The code expires in 10 minutes.
                        @endif
                    </p>
                </div>

                <div class="space-y-4">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg"
                    >
                        <span id="submitText">Verify Email</span>
                        <svg id="submitSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>

                    <div class="text-center">
                        <form method="POST" action="{{ route('otp.resend') }}" class="inline" id="resendForm">
                            @csrf
                            <button type="submit" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium transition-colors">
                                Didn't receive the code? Resend
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center pt-4 border-t border-gray-200">
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-800 transition-colors">Back to Registration</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    const form = document.getElementById('otpForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const resendForm = document.getElementById('resendForm');

    // Auto-format OTP input to only allow numbers
    otpInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Auto-focus next or submit when 6 digits entered
        if (e.target.value.length === 6) {
            // Optional: auto-submit after short delay
            // setTimeout(() => {
            //     form.submit();
            // }, 300);
        }
    });

    // Paste event handler
    otpInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
        const numbersOnly = pastedData.replace(/[^0-9]/g, '').substring(0, 6);
        otpInput.value = numbersOnly;
        if (numbersOnly.length === 6) {
            otpInput.focus();
        }
    });

    // Clear input on page load if there was an error
    @if($errors->has('otp'))
        otpInput.value = '';
        otpInput.focus();
        
        // Scroll to error message
        setTimeout(() => {
            const errorDiv = document.querySelector('.bg-red-50.border-2');
            if (errorDiv) {
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    @endif

    // Form submission
    form.addEventListener('submit', function(e) {
        const otpValue = otpInput.value.trim();
        
        if (otpValue.length !== 6) {
            e.preventDefault();
            otpInput.classList.add('border-red-500', 'bg-red-50', 'animate-shake');
            otpInput.focus();
            setTimeout(() => {
                otpInput.classList.remove('animate-shake');
            }, 500);
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Verifying...';
        submitSpinner.classList.remove('hidden');
    });

    // Clear error styling when user starts typing
    otpInput.addEventListener('input', function() {
        if (this.classList.contains('border-red-500')) {
            this.classList.remove('border-red-500', 'bg-red-50', 'animate-shake');
            this.classList.add('border-gray-300');
        }
    });
    
    // Auto-focus and clear on error
    @if($errors->has('otp'))
        setTimeout(() => {
            otpInput.focus();
            otpInput.select();
        }, 300);
    @endif

    // Resend form submission
    resendForm.addEventListener('submit', function(e) {
        const submitBtn = resendForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
    });

    // Focus on input when page loads
    otpInput.focus();
});
</script>

<style>
#otp:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

#otp.border-red-500,
#otp:has-error {
    border-color: #ef4444;
    background-color: #fef2f2;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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

.animate-slideDown {
    animation: slideDown 0.3s ease-out;
}

/* Shake animation for error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>
@endsection

