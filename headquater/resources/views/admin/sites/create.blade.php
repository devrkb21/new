<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.add_new_site_page_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.sites.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="domain" :value="__('messages.site_domain')" />
                            <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain"
                                :value="old('domain')" required autofocus :placeholder="__('messages.domain_placeholder')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('messages.assign_to_user')" />
                            <select id="user_id" name="user_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="" disabled selected>{{ __('messages.select_user_placeholder') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price_id" :value="__('messages.subscription_plan')" />
                            <select id="price_id" name="price_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="" disabled selected>{{ __('messages.select_plan_placeholder') }}</option>
                                @foreach($prices as $price)
                                    <option value="{{ $price->id }}" {{ old('price_id') == $price->id ? 'selected' : '' }}>
                                        {{ $price->plan->name }} - ৳{{ number_format($price->amount, 2) }} /
                                        {{ $price->billingPeriod->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('price_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.sites.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('messages.cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('messages.create_site') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>