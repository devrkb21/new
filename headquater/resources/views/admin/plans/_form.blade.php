@php
    $initialPrices = old(
        'prices',
        ($plan->exists && $plan->prices->isNotEmpty()
            ? $plan->prices->map(fn($p) => ['amount' => $p->amount, 'billing_period_id' => $p->billing_period_id])->all()
            : [['amount' => '', 'billing_period_id' => 1]])
    );
@endphp

@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-input-group :label="__('messages.plan_name')" name="name" :value="old('name', $plan->name ?? '')" required />
    <x-input-group :label="__('messages.slug')" name="slug" :value="old('slug', $plan->slug ?? '')" required
        :helper="__('messages.slug_helper_plan')" />
</div>

<div class="mt-6">
    <label for="is_public" class="inline-flex items-center cursor-pointer">
        <input type="hidden" name="is_public" value="0">
        <input id="is_public" type="checkbox"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_public" value="1"
            {{ old('is_public', $plan->is_public ?? true) ? 'checked' : '' }}>
        <span class="ml-2 text-sm text-gray-700">{{ __('messages.make_plan_public') }}</span>
    </label>
</div>

<hr class="my-6">

<div x-data='{
    prices: @json($initialPrices),
    addPrice() {
        this.prices.push({ amount: "", billing_period_id: 1 });
    },
    removePrice(index) {
        if (this.prices.length > 1) {
            this.prices.splice(index, 1);
        }
    }
}'>
    <h3 class="text-lg font-medium text-gray-900">{{ __('messages.pricing_and_billing') }}</h3>
    <div class="mt-4 space-y-4">
        <template x-for="(price, index) in prices" :key="index">
            <div class="flex items-end gap-4 p-4 border rounded-md bg-gray-50">
                <div class="flex-grow">
                    <label class="block text-sm font-medium text-gray-700">{{ __('messages.amount_currency') }}</label>
                    <input type="number" :name="`prices[${index}][amount]`" x-model="price.amount"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="{{ __('messages.amount_placeholder') }}" required step="0.01">
                </div>
                <div class="flex-grow">
                    <label class="block text-sm font-medium text-gray-700">{{ __('messages.billing_period') }}</label>
                    <select :name="`prices[${index}][billing_period_id]`" x-model="price.billing_period_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @foreach($billingPeriods as $period)
                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button" @click="removePrice(index)" x-show="prices.length > 1"
                        class="text-red-500 hover:text-red-700 font-semibold px-3 py-2">{{ __('messages.remove') }}</button>
                </div>
            </div>
        </template>
    </div>

    <button type="button" @click="addPrice()"
        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
        {{ __('messages.add_another_price') }}
    </button>
</div>

<hr class="my-6">

<h3 class="text-lg font-medium text-gray-900">{{ __('messages.feature_limits_title') }}</h3>
<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
    <x-input-group :label="__('messages.limit_checkout_tracking')" name="limit_checkouts" type="number"
        :value="old('limit_checkouts', $plan->limit_checkouts ?? 0)" required />
    <x-input-group :label="__('messages.limit_fraud_ips')" name="limit_fraud_ips" type="number"
        :value="old('limit_fraud_ips', $plan->limit_fraud_ips ?? 0)" required />
    <x-input-group :label="__('messages.limit_fraud_emails')" name="limit_fraud_emails" type="number"
        :value="old('limit_fraud_emails', $plan->limit_fraud_emails ?? 0)" required />
    <x-input-group :label="__('messages.limit_fraud_phones')" name="limit_fraud_phones" type="number"
        :value="old('limit_fraud_phones', $plan->limit_fraud_phones ?? 0)" required />
    <x-input-group :label="__('messages.limit_courier_checks')" name="limit_courier_checks" type="number"
        :value="old('limit_courier_checks', $plan->limit_courier_checks ?? 0)" required />
</div>

<div class="pt-8">
    <x-primary-button>
        {{ $buttonText ?? __('messages.save_plan') }}
    </x-primary-button>
</div>