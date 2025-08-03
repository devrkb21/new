<x-marketing-layout>
    <div class="bg-gray-50">
        <div class="container mx-auto px-4 py-16 lg:py-24">
            <div class="max-w-6xl mx-auto">
                {{-- Page Header --}}
                <div class="text-center mb-16">
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
                        {{ __('messages.pricing_page_title') }}
                    </h1>
                    <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                        {{ __('messages.pricing_page_subtitle') }}
                    </p>
                </div>

                {{-- Pricing Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        // Separate free trial plan and reorder plans
                        $freeTrialPlan = $plans->filter(function ($plan) {
                            return $plan->prices->contains('amount', 0);
                        })->first();
                        $paidPlans = $plans->filter(function ($plan) {
                            return !$plan->prices->contains('amount', 0);
                        });
                        $sortedPlans = $paidPlans->merge(collect([$freeTrialPlan]));
                    @endphp

                    @foreach($sortedPlans as $index => $plan)
                        @if($plan && $plan->prices->isNotEmpty())
                            <div x-data="{ selectedPeriod: '{{ $plan->prices->sortBy('billingPeriod.duration_in_days')->first()->billingPeriod->slug ?? '' }}' }"
                                class="relative bg-white rounded-2xl shadow-xl p-8 flex flex-col transition-transform transform hover:scale-105 duration-300 {{ $index === 1 ? 'border-2 border-primary-600' : '' }}">

                                {{-- Popular Plan Badge --}}
                                @if($index === 1)
                                    <span class="absolute top-0 right-0 bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-bl-lg rounded-tr-lg">
                                        {{ __('messages.most_popular') }}
                                    </span>
                                @endif

                                <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                <p class="mt-2 text-gray-500 flex-grow leading-relaxed">
                                    {{ $plan->description }}
                                </p>

                                {{-- Billing Period Toggle --}}
                                @if($plan->prices->count() > 1)
                                    <div class="mt-6 flex justify-center p-1 bg-gray-100 rounded-lg">
                                        @foreach($plan->prices->sortBy('billingPeriod.duration_in_days') as $price)
                                            <button @click="selectedPeriod = '{{ $price->billingPeriod->slug }}'"
                                                :class="{ 'bg-white shadow-sm': selectedPeriod === '{{ $price->billingPeriod->slug }}' }"
                                                class="w-full px-4 py-2 text-sm text-center font-semibold rounded-md transition-colors duration-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                                {{ $price->billingPeriod->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Price Display --}}
                                <div class="mt-6 h-24">
                                    @foreach($plan->prices as $price)
                                        <div x-show="selectedPeriod === '{{ $price->billingPeriod->slug }}'" x-cloak
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 transform translate-y-0"
                                            x-transition:leave-end="opacity-0 transform -translate-y-2">
                                            @if($price->amount == 0)
                                                <p class="text-5xl font-extrabold text-gray-900">{{ __('messages.free') }}</p>
                                                <p class="text-gray-500 text-sm">{{ __('messages.free_forever') }}</p>
                                            @else
                                                <p class="text-5xl font-extrabold text-gray-900">
                                                    &#2547;{{ number_format($price->amount) }}</p>
                                                @if($price->billingPeriod->duration_in_days > 0)
                                                    <p class="text-gray-500 text-sm">
                                                        {{ __('messages.per_' . rtrim(strtolower($price->billingPeriod->name), 'ly')) }}</p>
                                                @else
                                                    <p class="text-gray-500 text-sm">{{ __('messages.one_time_payment') }}</p>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Call to Action Button --}}
                                <div class="mt-8">
                                    @foreach($plan->prices as $price)
                                        <div x-show="selectedPeriod === '{{ $price->billingPeriod->slug }}'" x-cloak>
                                            <a href="{{ route('subscribe.create') }}"
                                                class="w-full block text-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                                {{ $price->amount == 0 ? __('messages.try_for_free') : __('messages.get_started') }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Features List --}}
                                <ul class="mt-8 space-y-4 text-gray-600 text-sm">
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✔️</span>
                                        <span>{{ $plan->limit_checkouts > 0 ? number_format($plan->limit_checkouts) : __('messages.unlimited') }} {{ __('messages.feature_checkout_tracking') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✔️</span>
                                        <span>{{ $plan->limit_courier_checks > 0 ? number_format($plan->limit_courier_checks) : __('messages.unlimited') }} {{ __('messages.feature_courier_checks') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✔️</span>
                                        <span>{{ $plan->limit_fraud_ips > 0 ? number_format($plan->limit_fraud_ips) : __('messages.unlimited') }} {{ __('messages.feature_fraud_ips') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✔️</span>
                                        <span>{{ $plan->limit_fraud_emails > 0 ? number_format($plan->limit_fraud_emails) : __('messages.unlimited') }} {{ __('messages.feature_fraud_emails') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-green-500 mr-2">✔️</span>
                                        <span>{{ $plan->limit_fraud_phones > 0 ? number_format($plan->limit_fraud_phones) : __('messages.unlimited') }} {{ __('messages.feature_fraud_phones') }}</span>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-marketing-layout>