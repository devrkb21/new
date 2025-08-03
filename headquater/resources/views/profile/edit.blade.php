<x-client-layout>
    <div x-data="{ activeTab: 'profile' }" class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.account_setting') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.account_setting_desc') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Left Side Navigation --}}
            <div class="md:col-span-1">
                <nav class="flex flex-col space-y-2">
                    <button @click="activeTab = 'profile'"
                        :class="{ 'bg-primary-100 text-primary-700': activeTab === 'profile', 'text-gray-600 hover:bg-gray-100 hover:text-gray-900': activeTab !== 'profile' }"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-left transition-colors">
                        {{ __('messages.profile') }}
                    </button>
                    <button @click="activeTab = 'password'"
                        :class="{ 'bg-primary-100 text-primary-700': activeTab === 'password', 'text-gray-600 hover:bg-gray-100 hover:text-gray-900': activeTab !== 'password' }"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-left transition-colors">
                        {{ __('messages.update_password') }}
                    </button>
                    <button @click="activeTab = 'delete'"
                        :class="{ 'bg-red-50 text-red-700': activeTab === 'delete', 'text-gray-600 hover:bg-gray-100 hover:text-gray-900': activeTab !== 'delete' }"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-left transition-colors">
                        {{ __('messages.delete_account') }}
                    </button>
                </nav>
            </div>

            {{-- Right Side Content --}}
            <div class="md:col-span-3">
                {{-- Profile Information Card --}}
                <div x-show="activeTab === 'profile'" x-cloak
                    x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- Update Password Card --}}
                <div x-show="activeTab === 'password'" x-cloak
                    x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                {{-- Delete Account Card --}}
                <div x-show="activeTab === 'delete'" x-cloak
                    x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-client-layout>