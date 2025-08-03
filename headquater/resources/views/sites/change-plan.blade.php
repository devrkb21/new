<x-client-layout>
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto space-y-8">
                {{-- Page Header --}}
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl tracking-tight">
                        {{ __('messages.change_plan') }}
                    </h2>
                    <p class="mt-2 text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                        {{ __('messages.changing_plan_for') }} <span class="font-bold">{{ $site->domain }}</span>.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                    {{-- Current Plan Card --}}
                    <div class="relative border-2 border-primary-600 rounded-2xl p-8 flex flex-col bg-white shadow-xl transition-transform transform hover:scale-105 duration-300">
                        <span class="absolute top-0 -translate-y-1/2 bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            {{ __('messages.current_plan_badge') }}
                        </span>

                        <h3 class="text-2xl font-bold text-gray-900">{{ $site->plan_name }}</h3>
                        <p class="mt-2 text-gray-500 flex-grow leading-relaxed">
                            {{ __('messages.your_current_active_plan') }}
                        </p>
                        <div class="mt-6">
                            <p class="text-5xl font-extrabold text-gray-900">
                                @if($site->price && $site->price->amount !== null)
                                    &#2547;{{ number_format($site->price->amount) }}
                                @elseif($site->custom_price_amount !== null)
                                    &#2547;{{ number_format($site->custom_price_amount) }}
                                @else
                                    {{ __('messages.free') }}
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm">
                                @if($site->price && $site->price->billingPeriod)
                                    {{ __('messages.per') }} {{ rtrim(strtolower($site->price->billingPeriod->name), 'ly') }}
                                @elseif($site->custom_price_amount !== null && $site->customBillingPeriod)
                                    {{ __('messages.per') }} {{ rtrim(strtolower($site->customBillingPeriod->name), 'ly') }}
                                @else
                                    &nbsp;
                                @endif
                            </p>
                        </div>
                        <div class="mt-8 w-full block text-center px-6 py-3 bg-gray-200 text-gray-500 font-semibold rounded-lg">
                            {{ __('messages.your_current_plan_button') }}
                        </div>
                    </div>

                    {{-- Available Plans For Upgrade/Downgrade --}}
                    @php
                        // Separate free trial plan and reorder plans
                        $freeTrialPlan = $availablePlans->filter(function ($plan) {
                            return $plan->prices && $plan->prices->contains('amount', 0);
                        })->first();
                        $paidPlans = $availablePlans->filter(function ($plan) {
                            return $plan->prices && !$plan->prices->contains('amount', 0);
                        });
                        $sortedPlans = $paidPlans->merge($freeTrialPlan ? collect([$freeTrialPlan]) : collect([]));
                    @endphp

                    @foreach($sortedPlans as $index => $plan)
                        @if($plan && $plan->prices && $plan->prices->isNotEmpty())
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
                                    @if ($plan->name === 'Agency')
                                        {{ __('messages.ideal_for_agencies') }}
                                    @else
                                        {{ $plan->description ?: __('messages.ideal_for_starters') }}
                                    @endif
                                </p>

                                {{-- Billing Period Toggle --}}
                                @if($plan->prices->count() > 1)
                                    <div class="mt-6 flex justify-center p-1 bg-gray-100 rounded-lg">
                                        @foreach($plan->prices->sortBy('billingPeriod.duration_in_days') as $price)
                                            <button @click="selectedPeriod = '{{ $price->billingPeriod->slug ?? '' }}'"
                                                :class="{ 'bg-white shadow-sm': selectedPeriod === '{{ $price->billingPeriod->slug ?? '' }}' }"
                                                class="w-full px-4 py-2 text-sm text-center font-semibold rounded-md transition-colors duration-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                                {{ $price->billingPeriod->name ?? '' }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Price Display --}}
                                <div class="mt-6 h-24">
                                    @foreach($plan->prices as $price)
                                        <div x-show="selectedPeriod === '{{ $price->billingPeriod->slug ?? '' }}'" x-cloak
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 transform translate-y-0"
                                            x-transition:leave-end="opacity-0 transform -translate-y-2">
                                            @if($price->amount == 0)
                                                <p class="text-5xl font-extrabold text-gray-900">{{ __('messages.free') }}</p>
                                                <p class="text-gray-500 text-sm">&nbsp;</p>
                                            @else
                                                <p class="text-5xl font-extrabold text-gray-900">
                                                    &#2547;{{ number_format($price->amount) }}</p>
                                                <p class="text-gray-500 text-sm">{{ __('messages.per') }}
                                                    {{ rtrim(strtolower($price->billingPeriod->name ?? ''), 'ly') }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Call to Action Button --}}
                                <div class="mt-8">
                                    @foreach($plan->prices as $price)
                                        <div x-show="selectedPeriod === '{{ $price->billingPeriod->slug ?? '' }}'" x-cloak>
                                            <form action="{{ route('checkout.show', ['price' => $price->id]) }}" method="GET">
                                                <input type="hidden" name="change_site_id" value="{{ $site->id }}">
                                                <x-primary-button type="submit" class="w-full justify-center focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                                    @if($price->amount == 0)
                                                        {{ __('messages.try_for_free') }}
                                                    @elseif($site->price && $price->amount > $site->price->amount)
                                                        {{ __('messages.upgrade') }} {{ __('messages.now_suffix') }}
                                                    @elseif($site->price)
                                                        {{ __('messages.downgrade') }} {{ __('messages.now_suffix') }}
                                                    @else
                                                        {{ __('messages.upgrade') }} {{ __('messages.now_suffix') }}
                                                    @endif
                                                </x-primary-button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-client-layout>