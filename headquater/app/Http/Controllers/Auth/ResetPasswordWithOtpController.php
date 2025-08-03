<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService; // <-- Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter; // <-- Add this
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException; // <-- Add this
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResetPasswordWithOtpController extends Controller
{
    /**
     * Display the password reset view for OTP.
     */
    public function create(Request $request, $userId): View
    {
        $user = User::findOrFail($userId);
        return view('auth.reset-password-with-otp', ['request' => $request, 'user' => $user]);
    }

    /**
     * Handle an incoming new password request via OTP.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'otp' => ['required', 'string', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::findOrFail($request->user_id);
        $cachedOtp = Cache::get('otp_for_password_reset_' . $user->id);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withInput()->withErrors(['otp' => 'The provided OTP is invalid or has expired.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        Cache::forget('otp_for_password_reset_' . $user->id);

        return redirect()->route('login')->with('status', __('Your password has been reset successfully!'));
    }

    /**
     * Resend the password reset OTP.
     */
    public function resend(Request $request, SmsService $smsService): RedirectResponse
    {
        $request->validate(['user_id' => 'required|integer|exists:users,id']);
        $user = User::findOrFail($request->user_id);

        // --- RATE LIMITER LOGIC ---
        $throttleKey = 'resend-password-otp|' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'otp' => "Please wait {$seconds} more seconds before requesting another OTP.",
            ]);
        }

        // --- OTP GENERATION AND SENDING LOGIC ---
        $otp = random_int(100000, 999999);
        Cache::put('otp_for_password_reset_' . $user->id, $otp, now()->addMinutes(10));

        $message = "[Coder Zone BD] Your password reset OTP is: {$otp}.";
        $smsService->send($user->phone, $message);

        // --- HIT THE RATE LIMITER ---
        RateLimiter::hit($throttleKey, 60); // 60-second cooldown

        return back()->with('status', 'A new OTP has been sent to your phone number.');
    }
}