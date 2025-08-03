<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, please verify your phone number by entering the 6-digit code we just sent to you. If you didn\'t receive the SMS, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'A fresh verification code has been sent to your phone number.')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('phone.verification.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" :value="__('OTP Code')" />

            <div class="flex items-center space-x-4 mt-1">
                <x-text-input id="otp" class="block w-full" type="text" name="otp" required autofocus
                    autocomplete="one-time-code" inputmode="numeric" />

                <x-primary-button>
                    {{ __('Verify') }}
                </x-primary-button>
            </div>

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
    </form>

    <div class="mt-4 flex items-center justify-between">
        <div x-data="{
                countdown: 60,
                timerRunning: false,
                init() {
                    // Check for cooldown timestamp from server first, then localStorage
                    const endTime = {{ session('otp_cooldown_end', 'null') }} * 1000 || localStorage.getItem('otp_cooldown_end');
                    
                    if (endTime && endTime > Date.now()) {
                        this.timerRunning = true;
                        this.countdown = Math.round((endTime - Date.now()) / 1000);
                        localStorage.setItem('otp_cooldown_end', endTime); // Save for manual refreshes

                        const interval = setInterval(() => {
                            this.countdown--;
                            if (this.countdown <= 0) {
                                clearInterval(interval);
                                this.timerRunning = false;
                                this.countdown = 60;
                                localStorage.removeItem('otp_cooldown_end');
                            }
                        }, 1000);
                    } else {
                        localStorage.removeItem('otp_cooldown_end');
                    }
                }
            }" x-init="init()">
            <form method="POST" action="{{ route('phone.verification.resend') }}">
                @csrf
                <button type="submit"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="timerRunning"
                    x-text="timerRunning ? `Resend in ${countdown}s` : 'Resend Verification Code'"></button>
            </form>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>