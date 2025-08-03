<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, SmsService $smsService): RedirectResponse
    {
        $request->validate([
            'login' => ['required'],
        ]);

        $login = $request->input('login');
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // --- Standard Email Flow ---
            $status = Password::sendResetLink(['email' => $login]);

            return $status == Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withInput($request->only('login'))
                    ->withErrors(['login' => __($status)]);
        } else {
            // --- New Phone OTP Flow ---
            $user = User::where('phone', $login)->first();

            if (!$user) {
                return back()->withInput($request->only('login'))
                    ->withErrors(['login' => __('We can\'t find a user with that phone number.')]);
            }

            // Generate OTP
            $otp = random_int(100000, 999999);
            Cache::put('otp_for_password_reset_' . $user->id, $otp, now()->addMinutes(10));

            // Send SMS
            $message = "[Coder Zone BD] Your password reset code is: {$otp}. It will expire in 10 minutes.";
            $smsService->send($user->phone, $message);

            // Generate a signed URL to the OTP reset form
            $resetUrl = URL::temporarySignedRoute(
                'password.reset.otp.create',
                now()->addMinutes(10),
                ['user' => $user->id]
            );

            // *** THE FIX: Redirect directly to the OTP entry page ***
            return redirect($resetUrl);
        }
    }
}