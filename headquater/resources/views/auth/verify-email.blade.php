<x-guest-layout>
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">

        <div class="text-center mb-6">
            {{-- Envelope Icon --}}
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100">
                <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">Verify Your Email Address</h2>
            <p class="mt-2 text-sm text-gray-600">
                Thanks for signing up! A verification link has been sent to your email. Please click the link to
                activate your account.
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md text-center">
                A new verification link has been sent to the email address you provided.
            </div>
        @endif

        {{-- Display rate-limit error message if it exists --}}
        <x-input-error :messages="$errors->get('email')" class="mb-4" />

        <div class="mt-6 flex items-center justify-between">
            <div x-data="{
                    countdown: 60,
                    timerRunning: false,
                    init() {
                        // Check for cooldown timestamp from server first, then localStorage
                        const endTime = {{ session('email_cooldown_end', 'null') }} * 1000 || localStorage.getItem('email_cooldown_end');
                        
                        if (endTime && endTime > Date.now()) {
                            this.timerRunning = true;
                            this.countdown = Math.round((endTime - Date.now()) / 1000);
                            localStorage.setItem('email_cooldown_end', endTime); // Save for manual refreshes

                            const interval = setInterval(() => {
                                this.countdown--;
                                if (this.countdown <= 0) {
                                    clearInterval(interval);
                                    this.timerRunning = false;
                                    this.countdown = 60;
                                    localStorage.removeItem('email_cooldown_end');
                                }
                            }, 1000);
                        } else {
                            localStorage.removeItem('email_cooldown_end');
                        }
                    }
                }" x-init="init()">
                {{-- Resend Button --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button x-bind:disabled="timerRunning">
                        <span x-text="timerRunning ? `Resend in ${countdown}s` : 'Resend Verification Email'"></span>
                    </x-primary-button>
                </form>
            </div>
            {{-- Logout Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-500">
            Didn't receive the email? Check your spam folder or click the resend button.
        </p>
    </div>
</x-guest-layout>