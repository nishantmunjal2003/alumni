<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log GET requests to avoid clutter (or log all if desired)
        // Also exclude admin/analytics routes to avoid recursive logging of viewing logs
        if ($request->isMethod('get') && !$request->is('admin/analytics*') && !$request->is('_debugbar*')) {
            try {
                // In a real production app with a package like stevebauman/location, 
                // we would get location here. For now we will just log the IP.
                $country = null;
                $city = null;
                
                $exception = null;
                if ($response->exception) {
                    $exception = $response->exception->getMessage();
                }

                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'ip_address' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'country' => $country,
                    'city' => $city,
                    'status_code' => $response->getStatusCode(),
                    'exception' => $exception
                ]);
            } catch (\Exception $e) {
                // Fail silently
            }
        }

        return $response;
    }
}
