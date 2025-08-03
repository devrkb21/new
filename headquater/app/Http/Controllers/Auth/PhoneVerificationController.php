<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Services\SmsService;
// --- ADD THESE TWO LINES ---
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    /**
     * Display the phone verification notice view.
     */
    public function show(Request $request): View
    {
        return view('auth.verify-phone');
    }

    /**
     * Mark the authenticated user's phone number as verified.
     */
    public function verify(Request $request, SmsService $smsService): RedirectResponse
    {
        $user = $request->user();
        $request->validate(['otp' => 'required|string|digits:6']);

        $cachedOtp = Cache::get('otp_for_user_' . $user->id);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'The provided OTP is invalid or has expired.']);
        }

        // Mark phone as verified and clear the OTP
        $user->update(['phone_verified_at' => now()]);
        Cache::forget('otp_for_user_' . $user->id);

        return redirect()->route('dashboard')->with('status', 'Your phone number has been verified!');
    }

    /**
     * Resend the phone verification OTP.
     */
    public function resend(Request $request, SmsService $smsService): RedirectResponse
    {
        $user = $request->user();

        if ($user->phone_verified_at) {
            return redirect()->route('dashboard');
        }

        // --- START OF NEW RATE LIMITER LOGIC ---
        $throttleKey = 'resend-otp|' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // For the front-end timer to work with validation errors,
            // we'll flash the end time to the session.
            session()->flash('otp_cooldown_end', now()->addSeconds($seconds)->timestamp);

            throw ValidationException::withMessages([
                'phone' => "Please wait {$seconds} more seconds before requesting another code.",
            ]);
        }
        // --- END OF NEW RATE LIMITER LOGIC ---


        // Generate and send a new OTP
        $otp = random_int(100000, 999999);
        Cache::put('otp_for_user_' . $user->id, $otp, now()->addMinutes(10));

        $message = "[Coder Zone BD] Your new OTP is: {$otp}.";
        
        $smsService->send($user->phone, $message);

        // --- HIT THE RATE LIMITER AFTER SENDING ---
        RateLimiter::hit($throttleKey, 60); // 60-second cooldown

        return back()
            ->with('status', 'A fresh verification code has been sent to your phone number.')
            ->with('otp_cooldown_end', now()->addSeconds(60)->timestamp);
    }
}