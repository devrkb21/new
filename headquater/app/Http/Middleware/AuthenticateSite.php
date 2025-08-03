<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Site;

class AuthenticateSite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $domain = $request->header('X-Site-Domain');
        $apiKey = $request->header('X-Api-Key');

        // Get the expected key from your environment file for security
        $expectedApiKey = config('app.plugin_api_key');

        if (!$domain || !$apiKey || $apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Optional: Check if the site is even registered
        $site = Site::where('domain', $domain)->first();
            if (!$site) {
                return response()->json(['message' => 'Site not registered.'], 403);
            }

            // Allow the /sites/status endpoint to be accessed regardless of license status.
            // For all other routes, block if inactive or suspended.
            if ($request->path() !== 'api/v1/sites/status') {
                if ($site->status === 'inactive' || $site->status === 'suspended') {
                    return response()->json([
                        'message' => "Your license status is currently '{$site->status}'. Access to API resources is disabled."
                    ], 403);
                }
            }

        return $next($request);
    }
}