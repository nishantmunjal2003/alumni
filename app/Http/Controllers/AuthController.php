<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\OtpVerificationMail;
use App\Mail\WelcomeMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Admins go directly to admin dashboard
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            }

            // Managers go directly to manager dashboard
            if ($user->hasRole('manager')) {
                return redirect()->intended('/manager');
            }

            if (! $user->canAccessDashboard()) {
                return redirect()->route('profile.complete');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Store registration data in session
        $request->session()->put('registration_data', [
            'name' => ucwords(strtolower(trim($validated['name'])), " \t\r\n\f\v"),
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Initialize OTP attempts counter
        $request->session()->put('otp_attempts', 0);

        // Generate and send OTP
        $emailOtp = EmailOtp::createForEmail($validated['email']);
        Mail::to($validated['email'])->send(new OtpVerificationMail($emailOtp->otp));

        return redirect()->route('verify.otp')->with('email', $validated['email']);
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->get('email');

        if (! $email || ! $request->session()->has('registration_data')) {
            return redirect()->route('register')->withErrors(['error' => 'Please complete the registration form first.']);
        }

        $attemptsRemaining = 3 - ($request->session()->get('otp_attempts', 0));

        return view('auth.verify-otp', compact('email', 'attemptsRemaining'));
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (! $registrationData) {
            return redirect()->route('register')->withErrors(['error' => 'Registration session expired. Please register again.']);
        }

        $email = $registrationData['email'];
        $otp = $request->validated()['otp'];

        // Get current attempt count
        $attempts = $request->session()->get('otp_attempts', 0);

        // Check if maximum attempts reached
        if ($attempts >= 3) {
            $request->session()->forget('registration_data');
            $request->session()->forget('email');
            $request->session()->forget('otp_attempts');

            return redirect()->route('register')->withErrors(['error' => 'Maximum verification attempts exceeded. Please register again.']);
        }

        if (! EmailOtp::verify($email, $otp)) {
            // Increment attempt counter
            $attempts++;
            $request->session()->put('otp_attempts', $attempts);
            $attemptsRemaining = 3 - $attempts;

            if ($attemptsRemaining > 0) {
                return back()
                    ->withInput()
                    ->withErrors(['otp' => "❌ The verification code you entered is incorrect. You have {$attemptsRemaining} more attempt(s) remaining. Please check your email and try again."]);
            } else {
                // Last attempt failed
                $request->session()->forget('registration_data');
                $request->session()->forget('email');
                $request->session()->forget('otp_attempts');

                return redirect()->route('register')->withErrors(['error' => 'Maximum verification attempts exceeded. Please register again.']);
            }
        }

        // OTP verified successfully - clear attempts counter
        $request->session()->forget('otp_attempts');

        // Create the user
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'phone' => $registrationData['phone'] ?? null,
        ]);

        // Assign 'alumni' role by default
        Role::firstOrCreate(['name' => 'alumni', 'guard_name' => 'web']);
        $user->assignRole('alumni');

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeMail($user));

        // Clear registration data from session
        $request->session()->forget('registration_data');
        $request->session()->forget('email');

        // Login the user
        Auth::login($user);

        return redirect()->route('profile.complete');
    }

    public function resendOtp(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (! $registrationData) {
            return redirect()->route('register')->withErrors(['error' => 'Registration session expired. Please register again.']);
        }

        $email = $registrationData['email'];

        // Reset attempt counter when resending OTP
        $request->session()->forget('otp_attempts');

        // Generate and send new OTP
        $emailOtp = EmailOtp::createForEmail($email);
        Mail::to($email)->send(new OtpVerificationMail($emailOtp->otp));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                Auth::login($user);
            } else {
                $user = User::create([
                    'name' => ucwords(strtolower(trim($googleUser->name)), " \t\r\n\f\v"),
                    'email' => $googleUser->email,
                    'password' => Hash::make(uniqid()),
                    'profile_image' => $googleUser->avatar,
                ]);

                // Assign 'alumni' role by default
                Role::firstOrCreate(['name' => 'alumni', 'guard_name' => 'web']);
                $user->assignRole('alumni');
                Auth::login($user);

                Mail::to($user->email)->send(new WelcomeMail($user));
            }

            // Admins go directly to admin dashboard
            if ($user->hasRole('admin')) {
                return redirect('/admin');
            }

            // Managers go directly to manager dashboard
            if ($user->hasRole('manager')) {
                return redirect('/manager');
            }

            if (! $user->canAccessDashboard()) {
                return redirect()->route('profile.complete');
            }

            return redirect('/dashboard');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Google OAuth connection error: '.$e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Unable to connect to Google. Please check your internet connection and try again.']);
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth state error: '.$e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Session expired. Please try logging in again.']);
        } catch (\Exception $e) {
            Log::error('Google OAuth error: '.$e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Unable to login with Google: '.$e->getMessage()]);
        }
    }
}
