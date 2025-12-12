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

        if (! $user) {
            return redirect()->route('login');
        }

        // Eager load roles to avoid N+1 queries
        if (! $user->relationLoaded('roles')) {
            $user->load('roles');
        }

        // Allow access to profile completion page, profile edit (if approved), and logout
        if ($request->routeIs('profile.complete') || $request->routeIs('profile.store') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if user is admin, manager, or DataEntry (single check using loaded roles)
        $isAdminOrManagerOrDataEntry = $user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('DataEntry');

        // Allow access to profile edit if user can access dashboard
        if ($request->routeIs('profile.edit') || $request->routeIs('profile.update')) {
            if ($isAdminOrManagerOrDataEntry || ($user->isProfileComplete() && ! $user->isProfileBlocked())) {
                return $next($request);
            }
        }

        // Admins, managers, and DataEntry bypass profile completion checks
        if ($isAdminOrManagerOrDataEntry) {
            return $next($request);
        }

        // Check if profile is blocked or user is inactive
        if ($user->isProfileBlocked() || $user->status === 'inactive') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['error' => 'Your account has been deactivated. Please contact support if you believe this is an error.']);
        }

        // Check if profile is not completed (avoid calling canAccessDashboard again)
        if (! ($user->isProfileComplete() && ! $user->isProfileBlocked())) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Please complete your profile to access the dashboard.');
        }

        return $next($request);
    }
}
