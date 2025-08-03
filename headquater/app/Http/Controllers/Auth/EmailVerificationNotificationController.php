<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// --- ADD THESE TWO LINES ---
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // --- START OF NEW RATE LIMITER LOGIC ---
        $throttleKey = 'resend-verification-email|' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Flash the cooldown end time to the session for the front-end timer
            session()->flash('email_cooldown_end', now()->addSeconds($seconds)->timestamp);

            throw ValidationException::withMessages([
                'email' => "Please wait {$seconds} more seconds before requesting another email.",
            ]);
        }
        // --- END OF NEW RATE LIMITER LOGIC ---

        $request->user()->sendEmailVerificationNotification();

        // --- HIT THE RATE LIMITER AFTER SENDING ---
        RateLimiter::hit($throttleKey, 60); // 60-second cooldown

        return back()
            ->with('status', 'verification-link-sent')
            ->with('email_cooldown_end', now()->addSeconds(60)->timestamp);
    }
}