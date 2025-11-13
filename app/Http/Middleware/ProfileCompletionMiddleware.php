<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileCompletionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow access to profile completion page and logout
        if ($request->routeIs('profile.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if profile is blocked
        if ($user->isProfileBlocked()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['error' => 'Your account has been blocked by the administrator. Please contact support.']);
        }

        // Check if profile is not completed or not approved
        if (!$user->canAccessDashboard()) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Please complete your profile and wait for admin approval to access the dashboard.');
        }

        return $next($request);
    }
}


