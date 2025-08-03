<x-client-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.my_sites_and_subscriptions') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('messages.manage_sites_and_plans') }}</p>
            </div>
            <div>
                <a href="{{ route('sites.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                    {{ __('messages.add_new_site') }}
                </a>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <a href="{{ route('orders.plan') }}"
                class="px-3 py-1 text-sm rounded-full {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __('messages.all_sites') }}</a>
            <a href="{{ route('orders.plan', ['status' => 'active']) }}"
                class="px-3 py-1 text-sm rounded-full {{ request('status') == 'active' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __('messages.active') }}</a>
            <a href="{{ route('orders.plan', ['status' => 'renewal_soon']) }}"
                class="px-3 py-1 text-sm rounded-full {{ request('status') == 'renewal_soon' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __('messages.renewal_soon') }}</a>
            <a href="{{ route('orders.plan', ['status' => 'expired']) }}"
                class="px-3 py-1 text-sm rounded-full {{ request('status') == 'expired' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __('messages.expired') }}</a>
        </div>

        <div class="space-y-4">
            @forelse($sites as $site)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 md:flex md:items-center md:justify-between gap-6">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-primary-600">{{ $site->plan->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ __('messages.domain') }} {{ $site->domain }}</p>
                                </div>
                                <div class="text-sm text-gray-500 text-right">
                                    {{ __('messages.next_bill') }} <span class="font-semibold">
                                        {{ $site->plan_expires_at ? $site->plan_expires_at->format('M d, Y') : __('messages.lifetime') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 rounded-md grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-700">
                                <div>
                                    <span class="text-xs text-gray-500">{{ __('messages.plan_activated_on') }}</span>
                                    <p class="font-semibold">{{ $site->plan_activated_at ? $site->plan_activated_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">{{ __('messages.billing') }}</span>
                                    <p class="font-semibold">{{ $site->price && $site->price->billingPeriod ? $site->price->billingPeriod->name : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">{{ __('messages.price') }}</span>
                                    <p class="font-semibold">&#2547;{{ $site->price ? number_format($site->price->amount, 2) : '0.00' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">{{ __('messages.subscription_id') }}</span>
                                    <p class="font-semibold truncate">#{{ $site->id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex-shrink-0 mt-6 md:mt-0 md:w-48 md:ml-6 md:pl-6 md:border-l flex flex-col items-center justify-center space-y-3">
                            @if($site->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 w-full justify-center">{{ __('messages.active') }}</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 w-full justify-center">{{ __("messages.{$site->status}") }}</span>
                            @endif

                            <a href="{{ route('sites.manage', $site) }}" class="w-full text-center">
                                <x-primary-button class="w-full justify-center">{{ __('messages.manage_site') }}</x-primary-button>
                            </a>

                            @if(!$site->next_plan_id)
                                <a href="{{ route('sites.change_plan', $site) }}" class="w-full text-center">
                                    <x-secondary-button class="w-full justify-center">{{ __('messages.change_plan') }}</x-secondary-button>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- CORRECTED AND MORE ROBUST LOGIC FOR THE SCHEDULED CHANGE BANNER --}}
                    @if($site->next_plan_id && $site->plan_change_at)
                        <div class="bg-yellow-50 border-t border-yellow-200 px-6 py-3">
                            <p class="text-sm text-yellow-800">
                                <span class="font-bold">{{ __('messages.scheduled_change') }}</span> {{ __('messages.plan_will_change_to') }}
                                @if($site->nextPlan)
                                    <strong>{{ $site->nextPlan->name }}</strong>
                                @endif
                                @if($site->nextPrice && $site->nextPrice->billingPeriod)
                                    <strong>({{ $site->nextPrice->billingPeriod->name }})</strong>
                                @endif
                                {{ __('messages.on_date') }} <strong>{{ $site->plan_change_at->format('M d, Y') }}</strong>.
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500">{{ __('messages.no_sites_linked') }}</p>
                    <a href="{{ route('sites.create') }}"
                        class="mt-4 inline-block px-6 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors duration-200">
                        {{ __('messages.add_your_first_site') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-client-layout>