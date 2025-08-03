<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\SmsService;
use Illuminate\Support\Facades\Cache;
use App\Rules\ValidPhoneNumber;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, SmsService $smsService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => [
                'required',
                'string',
                new ValidPhoneNumber, // <-- 2. Apply the new rule
                'unique:' . User::class
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'website_url' => ['nullable', 'string', 'regex:/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'website_url' => $request->website_url,
        ]);

        // --- OTP Logic ---
        $otp = random_int(100000, 999999);
        Cache::put('otp_for_user_' . $user->id, $otp, now()->addMinutes(10));
        $message = "[Coder Zone BD] Your OTP is: {$otp}. Do not share this code.";
        $smsService->send($user->phone, $message);

        // --- MODIFIED: Site Linking Logic ---
        // Initialize a success message for the redirect.
        $successMessage = 'Registration successful. Please verify your phone to continue.';

        // If the user provided a website URL, try to link it.
        if ($request->filled('website_url')) {
            // Find an existing site with this domain that does NOT have a user yet.
            $site = Site::where('domain', $request->website_url)->whereNull('user_id')->first();

            if ($site) {
                $site->user_id = $user->id;
                $site->save();
                // Update the success message to inform the user.
                $successMessage = 'Registration successful and your site has been linked!';
            }
        }

        // Create a welcome notification for the new user
        $user->notifications()->create([
            'title' => 'Welcome to ' . config('app.name') . '!',
            'body' => 'Thank you for joining. Please verify your phone number to get started.',
            'link' => route('dashboard')
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Redirect to the dashboard with the appropriate success message.
        return redirect(route('dashboard', absolute: false))->with('success', $successMessage);
    }
}