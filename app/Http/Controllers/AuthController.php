<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Mail\WelcomeMail;

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

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/alumni');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Format name: First letter capital, rest lowercase
        $formattedName = ucfirst(strtolower(trim($validated['name'])));
        
        $user = User::create([
            'name' => $formattedName,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => 'active',
        ]);

        // Assign alumnus role by default
        $user->assignRole('alumnus');

        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            // Log error but don't prevent registration
            \Log::error('Welcome email failed: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('alumni.edit', $user)
            ->with('success', 'Registration successful! Please complete your profile.');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Format name: First letter capital, rest lowercase
                $formattedName = ucfirst(strtolower(trim($googleUser->name)));
                
                // Create new user
                $user = User::create([
                    'name' => $formattedName,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(16)), // Random password since OAuth
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);

                // Assign alumnus role by default
                $user->assignRole('alumnus');

                // Send welcome email
                try {
                    Mail::to($user->email)->send(new WelcomeMail($user));
                } catch (\Exception $e) {
                    \Log::error('Welcome email failed: ' . $e->getMessage());
                }
            }

            // Update profile image if available
            if ($googleUser->avatar && !$user->profile_image) {
                // You can download and store the avatar if needed
                // For now, we'll just store the URL
            }

            Auth::login($user, true); // Remember user

            return redirect()->route('alumni.index')
                ->with('success', 'Successfully logged in with Google!');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
