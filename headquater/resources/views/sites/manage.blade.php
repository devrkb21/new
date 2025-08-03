<x-client-layout>
    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.manage_site') }}</h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('messages.manage_site_description', ['domain' => $site->domain]) }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('sites.api', $site) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('messages.api_and_plugin') }}
            </a>
            <a href="{{ route('sites.change_plan', $site) }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                {{ __('messages.change_plan') }}
            </a>
        </div>
    </div>

    {{-- Main Dashboard Grid --}}
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column (Main Content) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Usage Details Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('messages.usage_details') }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-y-6 gap-x-2 text-center">
                        <x-usage-meter :title="__('messages.feature_checkout_tracking')" :used="$site->usage_checkouts"
                            :limit="$site->getLimit('limit_checkouts')" />
                        <x-usage-meter :title="__('messages.feature_courier_checks')"
                            :used="$site->usage_courier_checks" :limit="$site->getLimit('limit_courier_checks')" />
                        <x-usage-meter :title="__('messages.feature_fraud_ips')" :used="$site->usage_fraud_ips"
                            :limit="$site->getLimit('limit_fraud_ips')" />
                        <x-usage-meter :title="__('messages.feature_fraud_emails')" :used="$site->usage_fraud_emails"
                            :limit="$site->getLimit('limit_fraud_emails')" />
                        <x-usage-meter :title="__('messages.feature_fraud_phones')" :used="$site->usage_fraud_phones"
                            :limit="$site->getLimit('limit_fraud_phones')" />
                    </div>
                </div>
            </div>

            {{-- Plugin Settings Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('sites.settings.update', $site) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('messages.plugin_settings') }}</h3>

                        <x-boolean-toggle name="checkout_tracking_enabled"
                            :checked="optional($site->settings)->checkout_tracking_enabled">
                            <span class="font-medium text-gray-700">{{ __('messages.enable_checkout_tracking') }}</span>
                            <p class="text-gray-500 text-xs">{{ __('messages.enable_checkout_tracking_desc') }}</p>
                        </x-boolean-toggle>

                        <x-boolean-toggle name="fraud_blocker_enabled"
                            :checked="optional($site->settings)->fraud_blocker_enabled">
                            <span class="font-medium text-gray-700">{{ __('messages.enable_fraud_blocker') }}</span>
                            <p class="text-gray-500 text-xs">{{ __('messages.enable_fraud_blocker_desc') }}</p>
                        </x-boolean-toggle>

                        <x-boolean-toggle name="courier_service_enabled"
                            :checked="optional($site->settings)->courier_service_enabled">
                            <span class="font-medium text-gray-700">{{ __('messages.enable_courier_service') }}</span>
                            <p class="text-gray-500 text-xs">{{ __('messages.enable_courier_service_desc') }}</p>
                        </x-boolean-toggle>

                        <div class="pt-4">
                            <label for="data_retention_days"
                                class="block text-sm font-medium text-gray-700">{{ __('messages.data_retention_period') }}</label>
                            <select id="data_retention_days" name="data_retention_days"
                                class="mt-1 block w-full max-w-xs pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                @php $current_retention = optional($site->settings)->data_retention_days ?? 30; @endphp
                                <option value="7" @if($current_retention == 7) selected @endif>{{ __('messages.days_7') }}
                                </option>
                                <option value="15" @if($current_retention == 15) selected @endif>
                                    {{ __('messages.days_15') }}</option>
                                <option value="30" @if($current_retention == 30) selected @endif>
                                    {{ __('messages.days_30') }}</option>
                                <option value="60" @if($current_retention == 60) selected @endif>
                                    {{ __('messages.days_60') }}</option>
                                <option value="90" @if($current_retention == 90) selected @endif>
                                    {{ __('messages.days_90') }}</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500">{{ __('messages.data_retention_period_desc') }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 text-right">
                        <x-primary-button type="submit">{{ __('messages.save_settings') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column (Sidebar) --}}
        <div class="lg:col-span-1 space-y-8">

            {{-- Subscription Details Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.subscription_details') }}</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.status') }}</span>
                            @php
                                $statusKey = str_replace('-', '_', $site->status);
                                $statusClass = 'bg-gray-100 text-gray-800';
                                if ($site->status === 'active')
                                    $statusClass = 'bg-green-100 text-green-800';
                                if ($site->status === 'inactive')
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                if ($site->status === 'suspended')
                                    $statusClass = 'bg-red-100 text-red-800';
                            @endphp
                            <span class="font-semibold px-2 py-0.5 rounded-full text-xs {{ $statusClass }}">
                                {{ __("messages.{$statusKey}") }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.current_plan') }}</span>
                            <span class="font-semibold text-gray-900">{{ $site->plan_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.plan_price') }}</span>
                            <span class="font-semibold text-gray-900">
                                @if($site->plan_id && $site->price)
                                    &#2547;{{ number_format($site->price->amount) }} /
                                    {{ rtrim(strtolower($site->price->billingPeriod->name), 'ly') }}
                                @elseif($site->custom_price_amount !== null)
                                    &#2547;{{ number_format($site->custom_price_amount) }} /
                                    {{ $site->customBillingPeriod ? rtrim(strtolower($site->customBillingPeriod->name), 'ly') : '' }}
                                @else
                                    {{ __('messages.free') }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.renews_on') }}</span>
                            <span
                                class="font-semibold text-gray-900">{{ $site->plan_expires_at ? $site->plan_expires_at->format('M d, Y') : __('messages.lifetime') }}</span>
                        </div>
                    </div>
                </div>
                @if($site->next_plan_id)
                    <div class="border-t border-gray-200 p-6">
                        <p class="text-sm text-blue-800 bg-blue-50 p-3 rounded-lg">
                            <span class="font-bold">{{ __('messages.plan_change_scheduled') }}</span>
                            {{ __('messages.plan_will_update_to') }} <span
                                class="font-semibold">{{ $site->nextPlan->name }}</span> {{ __('messages.on_date') }} <span
                                class="font-semibold">{{ $site->plan_change_at->format('M d, Y') }}</span>.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>