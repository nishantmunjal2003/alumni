<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user is not logged in, let auth middleware handle it (or this runs after auth)
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow Admin/Manager/DataEntry to bypass approval checks
        if ($user->hasAnyRole(['admin', 'manager', 'DataEntry'])) {
            return $next($request);
        }

        // Check if profile is approved
        if ($user->profile_status !== 'approved') {
            return redirect()->route('dashboard')
                ->with('error', 'Your profile is currently under review. access to this feature is restricted until approved.');
        }

        return $next($request);
    }
}
