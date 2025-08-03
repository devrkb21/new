<x-client-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.confirm_your_purchase') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.one_step_away') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('messages.order_summary') }}</h3>
                    <div class="mt-4 flex justify-between items-center">
                        <p class="text-gray-600">{{ $price->plan->name }} ({{ $price->billingPeriod->name }})</p>
                        <p class="font-semibold text-gray-800">&#2547;{{ number_format($price->amount, 2) }}</p>
                    </div>
                </div>

                <div class="p-6">
                    <label for="site_id"
                        class="block text-sm font-medium text-gray-700">{{ __('messages.apply_plan_to_site') }}</label>
                    <select name="site_id" id="site_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                        <option value="">{{ __('messages.select_a_site') }}</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->domain }} ({{ __('messages.current_plan') }}
                                {{ $site->plan->name }})
                            </option>
                        @endforeach
                    </select>

                    @error('site_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="price_id" value="{{ $price->id }}">

                <div class="p-6 bg-gray-50 rounded-b-lg text-right">
                    <x-primary-button>
                        {{ __('messages.proceed_to_payment_bkash') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-client-layout>