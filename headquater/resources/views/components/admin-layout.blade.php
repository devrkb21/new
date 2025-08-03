<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - {{ __('messages.admin_panel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @keydown.escape.window="sidebarOpen = false"
        class="min-h-screen bg-gray-100">

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 transform bg-white text-gray-800 transition-transform duration-300 ease-in-out">
            <div class="flex h-16 items-center justify-center border-b border-gray-200 px-4">
                <a href="{{ route('admin.dashboard') }}">
                    <x-application-logo class="block h-[45px] w-auto" />
                </a>
            </div>
            <nav class="flex-1 space-y-1 px-4 py-4">
                <x-admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('messages.dashboard') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('messages.user_management') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.sites.index')" :active="request()->routeIs('admin.sites.*')">
                    {{ __('messages.registered_sites') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    {{ __('messages.manage_plans') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.billing-periods.index')"
                    :active="request()->routeIs('admin.billing-periods.*')">
                    {{ __('messages.billing_periods') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.payment.history')"
                    :active="request()->routeIs('admin.payment.history')">
                    {{ __('messages.payment_history') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.support.tickets.index')"
                    :active="request()->routeIs('admin.support.tickets.*')">
                    {{ __('messages.support_tickets') }}
                </x-admin-nav-link>
            </nav>
        </aside>

        <div class="flex flex-col justify-between min-h-screen transition-all duration-300"
            :class="{ 'lg:ml-64': sidebarOpen }">
            <header class="flex-shrink-0 border-b border-gray-200 bg-white shadow-sm">
                <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <button @click="sidebarOpen = !sidebarOpen" type="button"
                            class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <span class="sr-only">{{ __('messages.toggle_sidebar') }}</span>
                            <svg x-show="!sidebarOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg x-show="sidebarOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="ml-auto flex items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <div class="ml-auto flex items-center gap-x-4">
                                        <a href="{{ app()->getLocale() == 'bn' ? route('language.switch', 'en') : route('language.switch', 'bn') }}"
                                            class="flex items-center space-x-2" aria-label="Switch Language">
                                            <span
                                                class="text-sm font-semibold {{ app()->getLocale() == 'en' ? 'text-primary-600' : 'text-gray-400' }}">EN</span>
                                            <div
                                                class="toggle-switch-track {{ app()->getLocale() == 'bn' ? 'lang-toggle-bn' : 'lang-toggle-en' }}">
                                                <div class="toggle-switch-thumb"></div>
                                            </div>
                                            <span
                                                class="text-sm font-semibold {{ app()->getLocale() == 'bn' ? 'text-primary-600' : 'text-gray-400' }}">BN</span>
                                        </a>
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button
                                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                    <div>{{ Auth::user()->name }}</div>
                                                    <div class="ml-1">
                                                        <svg class="h-4 w-4 fill-current"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <x-dropdown-link
                                                    :href="route('dashboard')">{{ __('messages.client_dashboard') }}</x-dropdown-link>
                                                <x-dropdown-link
                                                    :href="route('profile.edit')">{{ __('messages.profile') }}</x-dropdown-link>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <x-dropdown-link :href="route('logout')"
                                                        onclick="event.preventDefault(); this.closest('form').submit();">{{ __('messages.logout') }}</x-dropdown-link>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    </div>

                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link
                                        :href="route('dashboard')">{{ __('messages.client_dashboard') }}</x-dropdown-link>
                                    <x-dropdown-link
                                        :href="route('profile.edit')">{{ __('messages.profile') }}</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">{{ __('messages.logout') }}</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
        <x-copyright />
    </div>
    @stack('scripts')
</body>

</html>