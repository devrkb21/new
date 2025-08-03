<x-client-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto pt-8 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="space-y-12">
                <!-- Welcome Section -->
                <section aria-labelledby="welcome-heading">
                    <h1 id="welcome-heading" class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('messages.welcome_user', ['name' => $user->name]) }}
                    </h1>
                    <p class="mt-3 text-lg text-gray-500 leading-relaxed">{{ __('messages.welcome_message') }}</p>
                </section>

                <!-- Overview Section -->
                <section aria-labelledby="overview-heading">
                    <h2 id="overview-heading" class="text-2xl font-semibold text-gray-800 mb-6">
                        {{ __('messages.overview') }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a href="{{ route('orders.plan', ['status' => 'active']) }}"
                            class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.active_plans_services') }}">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                                {{ __('messages.active_plans_services') }}
                            </h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $active_plans }}</p>
                        </a>
                        <a href="{{ route('orders.plan', ['status' => 'renewal_soon']) }}"
                            class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.renewal_soon') }}">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                                {{ __('messages.renewal_soon') }}
                            </h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $renewal_soon_plans }}</p>
                        </a>
                        <a href="{{ route('orders.plan', ['status' => 'expired']) }}"
                            class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.expired_plan') }}">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                                {{ __('messages.expired_plan') }}
                            </h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $expired_plans }}</p>
                        </a>
                        <a href="{{ route('support.tickets.index', ['status' => 'open']) }}"
                            class="block p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.active_support_tickets') }}">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                                {{ __('messages.active_support_tickets') }}
                            </h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $active_tickets }}</p>
                        </a>
                    </div>
                </section>

                <!-- Plugins and Community Section -->
                <section aria-labelledby="plugins-community-heading" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- WordPress Plugin Card -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl flex flex-col">
                        <div class="p-8 bg-gradient-to-r from-[#21759B] to-[#1A5F7A] text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-white" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path
                                            d="M3.66675,11.99976c-0.00055,3.1933,1.82367,6.10608,4.69678,7.49969L4.38818,8.60846C3.91162,9.67548,3.66583,10.83112,3.66675,11.99976z M12.14648,12.7287l-2.50073,7.2652c1.67889,0.49438,3.47076,0.44788,5.1217-0.13293c-0.02319-0.0365-0.04315-0.07489-0.05969-0.11487L12.14648,12.7287z M17.62573,11.57953c-0.01117-0.815-0.24902-1.61078-0.68701-2.29816c-0.44788-0.56354-0.7312-1.23999-0.8186-1.95453c-0.0202-0.79413,0.60535-1.45526,1.39941-1.47894c0.03699,0,0.07202,0.00446,0.10791,0.00671C14.23444,2.74658,8.96436,2.97766,5.85638,6.37061C5.55566,6.69885,5.2818,7.05066,5.03735,7.42267C5.23291,7.42889,5.41724,7.4328,5.57361,7.4328c0.87146,0,2.22107-0.10602,2.22107-0.10602C7.98462,7.3158,8.14752,7.46088,8.15851,7.65082C8.16919,7.83551,8.03204,7.99567,7.8479,8.01355c0,0-0.45166,0.05286-0.95361,0.07916l3.03442,9.02649l1.82397-5.4693l-1.29834-3.55713c-0.44897-0.02631-0.87402-0.07916-0.87402-0.07916c-0.18933-0.01831-0.328-0.18665-0.30975-0.37598c0.01782-0.18427,0.17804-0.32147,0.36279-0.31079c0,0,1.37585,0.10602,2.19458,0.10602c0.87146,0,2.22131-0.10602,2.22131-0.10602c0.18988-0.01111,0.35291,0.13385,0.36401,0.32373c0.0108,0.18494-0.12653,0.34534-0.31091,0.36304c0,0-0.45203,0.05286-0.95361,0.07916l3.01147,8.95776l0.85962-2.72406C17.35553,13.44556,17.55969,12.51996,17.62573,11.57953z M19.36877,8.85889c-0.01447,1.02673-0.2298,2.04077-0.63391,2.98474l-2.54517,7.35889c3.90363-2.27075,5.28845-7.23743,3.12299-11.20044C19.35059,8.28607,19.36932,8.57233,19.36877,8.85889z M12,2.00012c-5.52283,0-10,4.47717-10,10s4.47717,10,10,10s10-4.47717,10-10S17.52283,2.00012,12,2.00012z M15.65869,20.66162c-2.92645,1.23846-6.28082,0.91241-8.91394-0.86652c-1.51147-1.02045-2.69464-2.45721-3.40637-4.13629c-1.23877-2.92645-0.9126-6.28094,0.8667-8.91394c1.02026-1.5116,2.45703-2.69489,4.13623-3.40631c2.92645-1.23846,6.28082-0.91241,8.91394,0.86652c1.51147,1.02045,2.69464,2.45721,3.40637,4.13629c1.23877,2.92645,0.9126,6.28094-0.8667,8.91394C18.77466,18.76691,17.33789,19.9502,15.65869,20.66162z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-extrabold">{{ __('messages.wordpress_plugin') }}</h3>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-blue-100 leading-relaxed">
                                {{ __('messages.wordpress_plugin_desc') }}
                            </p>
                        </div>
                        <div class="p-6 bg-gray-50 mt-auto">
                            <a href="{{ route('plugin.download') }}"
                                class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                {{ __('messages.download_plugin') }} (.zip)
                            </a>
                        </div>
                    </div>

                    <!-- WhatsApp Community Card -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl flex flex-col">
                        <div class="p-8 bg-gradient-to-r from-[#075E54] to-[#054D44] text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z"
                                            fill="url(#wa-gradient)" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z"
                                            fill="white" />
                                        <path
                                            d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z"
                                            fill="white" />
                                        <defs>
                                            <linearGradient id="wa-gradient" x1="26.5" y1="7" x2="4" y2="28"
                                                gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#5BD066" />
                                                <stop offset="1" stop-color="#27B43E" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-extrabold">{{ __('messages.wp_community') }}</h3>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-green-100 leading-relaxed">
                                {{ __('messages.wp_community_desc') }}
                            </p>
                        </div>
                        <div class="p-6 bg-gray-50 mt-auto">
                            <a href="https://chat.whatsapp.com/CYgv8YvXRJI4lGXGkm6TVz" target="_blank"
                                rel="noopener noreferrer"
                                class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#25D366] hover:bg-[#128C7E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-[#25D366]">
                                <svg class="w-5 h-5 mr-2 fill-white" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 32 32">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16 31C23.732 31 30 24.732 30 17C30 9.26801 23.732 3 16 3C8.26801 3 2 9.26801 2 17C2 19.5109 2.661 21.8674 3.81847 23.905L2 31L9.31486 29.3038C11.3014 30.3854 13.5789 31 16 31ZM16 28.8462C22.5425 28.8462 27.8462 23.5425 27.8462 17C27.8462 10.4576 22.5425 5.15385 16 5.15385C9.45755 5.15385 4.15385 10.4576 4.15385 17C4.15385 19.5261 4.9445 21.8675 6.29184 23.7902L5.23077 27.7692L9.27993 26.7569C11.1894 28.0746 13.5046 28.8462 16 28.8462Z"
                                        fill="#BFC8D0" />
                                    <path
                                        d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z"
                                        fill="url(#paint0_linear_87_7264)" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z"
                                        fill="white" />
                                    <path
                                        d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z"
                                        fill="white" />
                                    <defs>
                                        <linearGradient id="paint0_linear_87_7264" x1="26.5" y1="7" x2="4" y2="28"
                                            gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#5BD066" />
                                            <stop offset="1" stop-color="#27B43E" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                {{ __('messages.join_community') }}
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Account Management Section -->
                <section aria-labelledby="account-management-heading" class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
                    <h2 id="account-management-heading" class="text-2xl font-semibold text-gray-800 mb-6">
                        {{ __('messages.account_management') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-between p-6 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.personal_info') }}">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('messages.personal_info') }}</h3>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    {{ __('messages.personal_info_desc') }}
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-between p-6 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.security_setting') }}">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('messages.security_setting') }}</h3>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    {{ __('messages.security_setting_desc') }}
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="/payment-history"
                            class="flex items-center justify-between p-6 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.billing_history') }}">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('messages.billing_history') }}</h3>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    {{ __('messages.billing_history_desc') }}
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="/orders-plan"
                            class="flex items-center justify-between p-6 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="{{ __('messages.account_reports') }}">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('messages.account_reports') }}</h3>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    {{ __('messages.account_reports_desc') }}
                                </p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </section>

                <!-- Call to Action Section -->
                <section aria-labelledby="cta-heading"
                    class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-xl shadow-lg">
                    <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white opacity-75" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div class="flex-grow text-center md:text-left">
                            <h3 id="cta-heading" class="text-2xl font-bold text-white">
                                {{ __('messages.explore_plans_promo') }}
                            </h3>
                            <p class="mt-2 text-indigo-100 leading-relaxed">{{ __('messages.explore_plans_desc') }}</p>
                        </div>
                        <div class="flex-shrink-0 flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                            <a href="{{ route('subscribe.create') }}"
                                class="px-6 py-3 bg-white text-indigo-600 text-center font-semibold rounded-lg hover:bg-indigo-50 transition-colors duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('messages.view_our_plans') }}
                            </a>
                            <a href="/support/tickets"
                                class="px-6 py-3 bg-transparent border-2 border-indigo-400 text-white text-center font-semibold rounded-lg hover:bg-indigo-500 hover:border-indigo-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('messages.get_support') }}
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-client-layout>