<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => ucwords(strtolower(trim($validated['name'])), " \t\r\n\f\v"),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
        ]);

        // Assign 'alumni' role by default
        Role::firstOrCreate(['name' => 'alumni', 'guard_name' => 'web']);
        $user->assignRole('alumni');

        Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);

        return redirect()->route('profile.complete');
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
