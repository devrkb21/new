<x-marketing-layout>
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-24">
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 items-center gap-16">
            <div class="text-center lg:text-left">
                <h2 class="text-indigo-600 text-sm font-bold uppercase tracking-wider">{{ __('messages.hero_subtitle') }}</h2>
                <h1 class="mt-4 text-5xl font-extrabold tracking-tight text-gray-900 sm:text-6xl">
                    {{ __('messages.hero_title') }}
                </h1>
                <p class="mt-6 text-lg text-gray-700 max-w-xl">
                    {{ __('messages.hero_description') }}
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#pricing"
                        class="px-6 py-3 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg font-medium shadow">
                        {{ __('messages.choose_a_plan') }}
                    </a>
                    <a href="#features"
                        class="px-6 py-3 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-lg font-medium">
                        {{ __('messages.explore_features') }}
                    </a>
                </div>
                <div class="mt-10">
                    <p class="text-sm text-gray-500 mb-3">{{ __('messages.helping_stores_grow') }}</p>
                    <div class="flex items-center justify-center lg:justify-start gap-6">
                        <img src="{{ asset('https://woocommerce.com/wp-content/uploads/2025/01/Logo-Primary.png') }}"
                            alt="WooCommerce" class="h-8">
                        <img src="{{ asset('https://s.w.org/style/images/about/WordPress-logotype-alternative.png') }}"
                            alt="WordPress" class="h-16">
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.store_at_a_glance') }}</h3>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex justify-between"><span>📦 {{ __('messages.orders_this_week') }}</span><span
                                class="font-semibold text-gray-900">135</span></li>
                        <li class="flex justify-between"><span>❌ {{ __('messages.abandoned_carts') }}</span><span
                                class="text-red-600 font-semibold">27</span></li>
                        <li class="flex justify-between"><span>✅ {{ __('messages.deliveries_confirmed') }}</span><span
                                class="text-green-600 font-semibold">102</span></li>
                        <li class="flex justify-between"><span>🚫 {{ __('messages.blocked_fraud_orders') }}</span><span
                                class="text-yellow-500 font-semibold">6</span></li>
                    </ul>
                    <p class="mt-6 text-center text-xs text-gray-400">{{ __('messages.powered_by') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">{{ __('messages.your_complete_toolkit') }}</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('messages.boost_sales_prevent_loss') }}
                </p>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    {{ __('messages.features_summary') }}
                </p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.343 1.087-.835l.383-1.437M7.5 14.25L5.106 5.165A2.25 2.25 0 002.854 3H2.25" /></svg>
                            </div>
                            {{ __('messages.feature_title_1') }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">{{ __('messages.feature_description_1') }}</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                            </div>
                            {{ __('messages.feature_title_2') }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">{{ __('messages.feature_description_2') }}</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a.625.625 0 01.625-.625h16.5a.625.625 0 01.625.625v1.875m-17.25 4.5h16.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.375 6.375a1.125 1.125 0 011.697 0l2.25 2.25a1.125 1.125 0 010 1.697l-2.25 2.25a1.125 1.125 0 01-1.697-1.697l.87-.87H3.375a1.125 1.125 0 01-1.125-1.125V7.5a1.125 1.125 0 011.125-1.125h3.87l-.87-.87a1.125 1.125 0 010-1.697zM17.625 6.375a1.125 1.125 0 011.697 0l2.25 2.25a1.125 1.125 0 010 1.697l-2.25 2.25a1.125 1.125 0 01-1.697-1.697l.87-.87H13.5a1.125 1.125 0 01-1.125-1.125V7.5a1.125 1.125 0 011.125-1.125h3.87l-.87-.87a1.125 1.125 0 010-1.697z" /></svg>
                            </div>
                            {{ __('messages.feature_title_3') }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">{{ __('messages.feature_description_3') }}</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V5.25A2.25 2.25 0 0018 3H6.75A2.25 2.25 0 004.5 5.25v.75m0 0v.75m0-1.5h13.5m-13.5 0V3.75" /></svg>
                            </div>
                            {{ __('messages.feature_title_4') }}
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">{{ __('messages.feature_description_4') }}</dd>
                    </div>

                </dl>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="bg-gray-50 py-20 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">{{ __('messages.simple_setup') }}</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('messages.how_it_works_title') }}
                </p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                            </div>
                            {{ __('messages.how_it_works_step1_title') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">{{ __('messages.how_it_works_step1_desc') }}</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197" /></svg>
                            </div>
                            {{ __('messages.how_it_works_step2_title') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">{{ __('messages.how_it_works_step2_desc') }}</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 01-8.306 3.444z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.5l-8.25 8.25-2.25-2.25-1.5 1.5-3-3-1.5 1.5-1.5-1.5 3-3 1.5 1.5 2.25-2.25L13.5 3.75m6 3.75l-3 3" /></svg>
                            </div>
                            {{ __('messages.how_it_works_step3_title') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">{{ __('messages.how_it_works_step3_desc') }}</p>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </section>

    <section id="pricing" class="bg-gray-100 py-20 sm:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-base font-semibold leading-7 text-indigo-600">{{ __('messages.simple_transparent_pricing') }}</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        {{ __('messages.pricing_page_title') }}
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        {{ __('messages.pricing_page_subtitle') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($plans as $plan)
                    @if($plan->prices->isNotEmpty())
                    <div
                    x-data="{ selectedPeriod: '{{ $plan->prices->sortBy('billingPeriod.duration_in_days')->first()->billingPeriod->slug ?? '' }}' }"
                    class="bg-white rounded-2xl shadow-lg p-8 flex flex-col">

                    <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    <p class="mt-2 text-gray-600 flex-grow">
                    {{ $plan->description }}
                    </p>

                    @if($plan->prices->count() > 1)
                    <div class="mt-6 flex justify-center p-1 bg-gray-100 rounded-lg">
                    @foreach($plan->prices->sortBy('billingPeriod.duration_in_days') as $price)
                    <button @click="selectedPeriod = '{{ $price->billingPeriod->slug }}'"
                    :class="{ 'bg-white shadow': selectedPeriod === '{{ $price->billingPeriod->slug }}' }"
                    class="w-full px-4 py-2 text-sm text-center font-semibold rounded-md transition-colors duration-300">
                    {{ $price->billingPeriod->name }}
                    </button>
                    @endforeach
                    </div>
                    @endif

                    <div class="mt-6 h-24">
                    @foreach($plan->prices as $price)
                    <div x-show="selectedPeriod === '{{ $price->billingPeriod->slug }}'" x-cloak>
                    @if($price->amount == 0)
                    <p class="text-5xl font-extrabold text-gray-900">{{ __('messages.free') }}</p>
                    <p class="text-gray-500">{{ __('messages.free_forever') }}</p>
                    @else
                    <p class="text-5xl font-extrabold text-gray-900">&#2547;{{ number_format($price->amount) }}</p>
                    @if($price->billingPeriod->duration_in_days > 0)
                    <p class="text-gray-500">
                    {{ __('messages.per_' . rtrim(strtolower($price->billingPeriod->name), 'ly')) }}
                    </p>
                    @else
                    <p class="text-gray-500">{{ __('messages.one_time_payment') }}</p>
                    @endif
                    @endif
                    </div>
                    @endforeach
                    </div>

                    <a href="{{ route('subscribe.create') }}"
                    class="mt-8 w-full block text-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors duration-200">
                    {{ __('messages.get_started') }}
                    </a>

                    <ul class="mt-8 space-y-4 text-gray-600 text-sm">
                    <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span>
                    {{ $plan->limit_checkouts > 0 ? number_format($plan->limit_checkouts) : __('messages.unlimited') }} {{ __('messages.feature_checkout_tracking') }}
                    </li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span>
                    {{ $plan->limit_courier_checks > 0 ? number_format($plan->limit_courier_checks) : __('messages.unlimited') }} {{ __('messages.feature_courier_checks') }}</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span>
                    {{ $plan->limit_fraud_ips > 0 ? number_format($plan->limit_fraud_ips) : __('messages.unlimited') }} {{ __('messages.feature_fraud_ips') }}
                    </li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span>
                    {{ $plan->limit_fraud_emails > 0 ? number_format($plan->limit_fraud_emails) : __('messages.unlimited') }} {{ __('messages.feature_fraud_emails') }}</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✔️</span>
                    {{ $plan->limit_fraud_phones > 0 ? number_format($plan->limit_fraud_phones) : __('messages.unlimited') }} {{ __('messages.feature_fraud_phones') }}</li>
                    </ul>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section id="testimonial" class="relative isolate bg-white pb-32 pt-24 sm:pt-32">
        <div class="absolute inset-x-0 top-1/2 -z-10 -translate-y-1/2 transform-gpu overflow-hidden opacity-30 blur-3xl">
            <div class="mx-auto aspect-[1108/632] w-[69.25rem] bg-gradient-to-r from-[#80caff] to-[#4f46e5]"></div>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-xl text-center">
                <h2 class="text-lg font-semibold leading-8 tracking-tight text-indigo-600">
                    {{ __('messages.testimonial_title') }}
                </h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('messages.testimonial_subtitle') }}
                </p>
            </div>
            <div class="mx-auto mt-16 flow-root sm:mt-20">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
                    <div class="text-sm leading-6 text-center text-gray-900 lg:col-span-1">
                        <figure>
                            <blockquote class="font-semibold">
                                <p>“{{ __('messages.testimonial_1_quote') }}”</p>
                            </blockquote>
                            <figcaption class="mt-6 flex items-center justify-center gap-x-4"><img
                                    class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/png" alt="">
                                <div>
                                    <div class="font-semibold">{{ __('messages.testimonial_1_name') }}</div>
                                    <div class="text-gray-600">{{ __('messages.testimonial_1_role') }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="text-sm leading-6 text-center text-gray-900 lg:col-span-1">
                        <figure>
                            <blockquote class="font-semibold">
                                <p>“{{ __('messages.testimonial_2_quote') }}”</p>
                            </blockquote>
                            <figcaption class="mt-6 flex items-center justify-center gap-x-4"><img
                                    class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/png" alt="">
                                <div>
                                    <div class="font-semibold">{{ __('messages.testimonial_2_name') }}</div>
                                    <div class="text-gray-600">{{ __('messages.testimonial_2_role') }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="text-sm leading-6 text-center text-gray-900 lg:col-span-1">
                        <figure>
                            <blockquote class="font-semibold">
                                <p>“{{ __('messages.testimonial_3_quote') }}”</p>
                            </blockquote>
                            <figcaption class="mt-6 flex items-center justify-center gap-x-4"><img
                                    class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/png" alt="">
                                <div>
                                    <div class="font-semibold">{{ __('messages.testimonial_3_name') }}</div>
                                    <div class="text-gray-600">{{ __('messages.testimonial_3_role') }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="faq" class="bg-white py-20 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-4xl divide-y divide-gray-900/10">
                <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900">{{ __('messages.faq_title') }}</h2>
                <div x-data="{ open: 1 }" class="mt-10 space-y-6 divide-y divide-gray-900/10">
                    <div class="pt-6">
                        <dt>
                            <button @click="open = (open === 1 ? null : 1)" type="button"
                                class="flex w-full items-start justify-between text-left text-gray-900">
                                <span class="text-base font-semibold leading-7">{{ __('messages.faq1_q') }}</span>
                                <span class="ml-6 flex h-7 items-center">
                                    <svg :class="open === 1 ? 'rotate-180' : 'rotate-0'" class="h-6 w-6 transform transition-transform"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd x-show="open === 1" x-cloak class="mt-2 pr-12">
                            <p class="text-base leading-7 text-gray-600">{{ __('messages.faq1_a') }}</p>
                        </dd>
                    </div>
                    <div class="pt-6">
                        <dt>
                            <button @click="open = (open === 2 ? null : 2)" type="button"
                                class="flex w-full items-start justify-between text-left text-gray-900">
                                <span class="text-base font-semibold leading-7">{{ __('messages.faq2_q') }}</span>
                                <span class="ml-6 flex h-7 items-center">
                                    <svg :class="open === 2 ? 'rotate-180' : 'rotate-0'" class="h-6 w-6 transform transition-transform"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd x-show="open === 2" x-cloak class="mt-2 pr-12">
                            <p class="text-base leading-7 text-gray-600">{{ __('messages.faq2_a') }}</p>
                        </dd>
                    </div>
                    <div class="pt-6">
                        <dt>
                            <button @click="open = (open === 3 ? null : 3)" type="button"
                                class="flex w-full items-start justify-between text-left text-gray-900">
                                <span class="text-base font-semibold leading-7">{{ __('messages.faq3_q') }}</span>
                                <span class="ml-6 flex h-7 items-center">
                                    <svg :class="open === 3 ? 'rotate-180' : 'rotate-0'" class="h-6 w-6 transform transition-transform"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd x-show="open === 3" x-cloak class="mt-2 pr-12">
                            <p class="text-base leading-7 text-gray-600">{{ __('messages.faq3_a') }}</p>
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:justify-between lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                {!! nl2br(e(__('messages.cta_title'))) !!}
            </h2>
            <div class="mt-10 flex items-center gap-x-6 lg:mt-0 lg:flex-shrink-0">
                <a href="{{ route('register') }}"
                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('messages.get_started') }}</a>
                <a href="{{ route('contact') }}"
                    class="text-sm font-semibold leading-6 text-gray-900">{{ __('messages.contact_sales') }} <span
                        aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
</x-marketing-layout>