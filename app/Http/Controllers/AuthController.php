<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
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
            
            if (!$user->canAccessDashboard()) {
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
            'graduation_year' => 'nullable|string|max:10',
            'major' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'graduation_year' => $validated['graduation_year'] ?? null,
            'major' => $validated['major'] ?? null,
        ]);

        // Ensure 'user' role exists
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->assignRole('user');

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
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(uniqid()),
                    'profile_image' => $googleUser->avatar,
                ]);

                // Ensure 'user' role exists
                Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
                $user->assignRole('user');
                Auth::login($user);

                Mail::to($user->email)->send(new WelcomeMail($user));
            }

            if (!$user->canAccessDashboard()) {
                return redirect()->route('profile.complete');
            }

            return redirect('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Unable to login with Google. Please try again.']);
        }
    }
}
