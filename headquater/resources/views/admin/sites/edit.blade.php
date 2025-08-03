<x-admin-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.manage_site') }}</h2>
                <span class="text-sm font-mono text-gray-500">{{ $site->domain }}</span>
            </div>
            <a href="{{ route('admin.sites.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                &larr; {{ __('messages.back_to_sites_list') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-session-messages />

            {{-- Main Form for Updating --}}
            <form action="{{ route('admin.sites.update', $site) }}" method="POST" x-data='{
                plans: @json($plans->keyBy("id")),
                selectedPlanId: "{{ old('plan_id', $site->plan_id ?? $customPlan->id) }}",
                customPlanId: "{{ $customPlan->id }}",
                selectedPriceId: {{ old('price_id', $site->price_id) ?? 'null' }},
                availablePrices: [],
                updatePrices() {
                    if (this.selectedPlanId == this.customPlanId || !this.plans[this.selectedPlanId]) {
                        this.availablePrices = [];
                        this.selectedPriceId = null;
                        return;
                    }
                    this.availablePrices = this.plans[this.selectedPlanId].prices;
                    if (!this.availablePrices.find(p => p.id == this.selectedPriceId)) {
                        this.selectedPriceId = this.availablePrices.length ? this.availablePrices[0].id : null;
                    }
                },
                init() { this.updatePrices(); }
            }'>
                @csrf
                @method('PUT')

                {{-- Usage Details Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('messages.usage_details') }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 text-center">
                            <x-usage-meter :title="__('messages.feature_checkout_tracking')" :used="$site->usage_checkouts" :limit="$site->getLimit('limit_checkouts')" />
                            <x-usage-meter :title="__('messages.feature_courier_checks')" :used="$site->usage_courier_checks" :limit="$site->getLimit('limit_courier_checks')" />
                            <x-usage-meter :title="__('messages.feature_fraud_ips')" :used="$site->usage_fraud_ips" :limit="$site->getLimit('limit_fraud_ips')" />
                            <x-usage-meter :title="__('messages.feature_fraud_emails')" :used="$site->usage_fraud_emails" :limit="$site->getLimit('limit_fraud_emails')" />
                            <x-usage-meter :title="__('messages.feature_fraud_phones')" :used="$site->usage_fraud_phones" :limit="$site->getLimit('limit_fraud_phones')" />
                        </div>
                    </div>
                </div>

                {{-- Two-Column Layout for Settings --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.subscription_and_status') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">{{ __('messages.assign_to_user') }}</label>
                                    <select id="user_id" name="user_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected(old('user_id', $site->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="plan_id" class="block text-sm font-medium text-gray-700">{{ __('messages.plan') }}</label>
                                    <select id="plan_id" name="plan_id" x-model="selectedPlanId"
                                        @change="updatePrices()"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="{{ $customPlan->id }}">{{ $customPlan->name }}</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="selectedPlanId != customPlanId" x-cloak>
                                    <label for="price_id" class="block text-sm font-medium text-gray-700">{{ __('messages.price_and_billing') }}</label>
                                    <select id="price_id" name="price_id" x-model="selectedPriceId"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        :disabled="selectedPlanId == customPlanId">
                                        <template x-for="price in availablePrices" :key="price.id">
                                            <option :value="price.id"
                                                x-text="`${price.billing_period.name} - ৳${price.amount}`"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label for="plan_expires_at" class="block text-sm font-medium text-gray-700">{{ __('messages.plan_expiration_date') }}</label>
                                    <input type="date" name="plan_expires_at" id="plan_expires_at"
                                        value="{{ old('plan_expires_at', $site->plan_expires_at ? $site->plan_expires_at->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('messages.leave_blank_for_auto_calculation') }}</p>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
                                    <select id="status" name="status"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @foreach(['active', 'inactive', 'suspended', 'expired'] as $status)
                                            <option value="{{ $status }}" @selected($site->status == $status)>
                                                {{ __("messages.{$status}") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.plugin_settings') }}</h3>
                            <div class="space-y-4">
                                <x-boolean-toggle name="checkout_tracking_enabled"
                                    :checked="old('checkout_tracking_enabled', optional($site->settings)->checkout_tracking_enabled)">{{ __('messages.enable_checkout_tracking') }}</x-boolean-toggle>
                                <x-boolean-toggle name="fraud_blocker_enabled" :checked="old('fraud_blocker_enabled', optional($site->settings)->fraud_blocker_enabled)">{{ __('messages.enable_fraud_blocker') }}</x-boolean-toggle>
                                <x-boolean-toggle name="courier_service_enabled"
                                    :checked="old('courier_service_enabled', optional($site->settings)->courier_service_enabled)">{{ __('messages.enable_courier_service') }}</x-boolean-toggle>
                                <div>
                                    <label for="data_retention_days" class="block text-sm font-medium text-gray-700">{{ __('messages.data_retention_days') }}</label>
                                    <input type="number" name="data_retention_days" id="data_retention_days"
                                        value="{{ old('data_retention_days', optional($site->settings)->data_retention_days) }}"
                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                       {{-- RESTORED: Custom Plan Configuration Card --}}
<div x-show="selectedPlanId == customPlanId" x-cloak
    class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.custom_plan_configuration') }}</h3>
    <div class="space-y-4">
        <div>
            <label for="custom_price_amount" class="block text-sm font-medium text-gray-700">{{ __('messages.custom_price_currency') }}</label>
            <input type="number" step="0.01" name="custom_price_amount" id="custom_price_amount"
                :disabled="selectedPlanId != customPlanId"
                value="{{ old('custom_price_amount', $site->custom_price_amount) }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label for="custom_billing_period_id" class="block text-sm font-medium text-gray-700">{{ __('messages.custom_billing_period') }}</label>
            <select name="custom_billing_period_id" id="custom_billing_period_id"
                :disabled="selectedPlanId != customPlanId"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($billingPeriods as $period)
                    <option value="{{ $period->id }}" @selected(old('custom_billing_period_id', $site->custom_billing_period_id) == $period->id)>{{ $period->name }}</option>
                @endforeach
            </select>
        </div>
        <hr class="my-4">
        <h4 class="text-md font-semibold text-gray-700">{{ __('messages.custom_feature_limits') }}</h4>
        <p class="text-xs text-gray-500 -mt-2 mb-2">{{ __('messages.custom_feature_limits_desc') }}</p>
        <div class="space-y-2 text-sm">
            @foreach([
                    'limit_checkouts' => __('messages.limit_checkout'),
                    'limit_fraud_ips' => __('messages.limit_fraud_ip'),
                    'limit_fraud_emails' => __('messages.limit_fraud_email'),
                    'limit_fraud_phones' => __('messages.limit_fraud_phone'),
                    'limit_courier_checks' => __('messages.limit_courier_check'),
                ] as $key => $label)
                    <div>
                        <label for="{{$key}}" class="block text-sm font-medium text-gray-700">{{$label}}</label>
                        <input type="number" id="{{$key}}" name="custom_limits[{{$key}}]" 
                            :disabled="selectedPlanId != customPlanId"
                            value="{{ old('custom_limits.' . $key, $site->custom_limits[$key] ?? 0) }}" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm">
                    </div>
            @endforeach
        </div>
    </div>
</div>
                    </div>
                </div>

                {{-- Sticky Save Footer --}}
                <div class="mt-8 pt-5 sticky bottom-0">
                    <div class="flex justify-end bg-white p-4 rounded-lg shadow-lg border">
                        <x-primary-button>{{ __('messages.save_changes') }}</x-primary-button>
                    </div>
                </div>
            </form>
                      
                 {{-- Danger Zone for Deletion --}}
            <div class="mt-12 pt-8 border-t-2 border-dashed">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-l-4 border-red-500">
                        <h3 class="text-lg font-medium text-red-600">{{ __('messages.danger_zone') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('messages.danger_zone_desc') }}</p>
                        <div class="mt-4">
                            <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-site-deletion')">
                                {{ __('messages.delete_this_site') }}
                            </x-danger-button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deletion Confirmation Modal --}}
            <x-modal name="confirm-site-deletion" focusable>
                <form method="post" action="{{ route('admin.sites.destroy', $site) }}" class="p-6">
                    @csrf
                    @method('delete')
                    <h2 class="text-lg font-medium text-gray-900">{{ __('messages.are_you_sure') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('messages.delete_site_confirmation_desc', ['domain' => "<strong>{$site->domain}</strong>"]) }}
                    </p>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">{{ __('messages.cancel') }}</x-secondary-button>
                        <x-danger-button class="ml-3">{{ __('messages.delete_site') }}</x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-admin-layout>