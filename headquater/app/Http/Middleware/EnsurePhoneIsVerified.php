<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated, has not verified their phone,
        // and is not already on a verification-related route to prevent redirect loops.
        if (
            $request->user() &&
            !$request->user()->phone_verified_at &&
            !$request->routeIs('phone.verification.*')
        ) {
            // Redirect them to the phone verification notice page.
            return redirect()->route('phone.verification.notice');
        }

        return $next($request);
    }
}