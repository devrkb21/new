<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024"
        class="min-h-screen bg-gray-100">

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden"></div>
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 transform bg-white text-gray-800 transition-transform duration-300 ease-in-out">
            <div class="flex h-16 items-center justify-center border-b border-gray-200 px-4">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-[45px] w-auto" />
                </a>
            </div>
            <nav class="flex-1 space-y-1 px-4 py-4">
                <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('messages.main_menu') }}</h3>
                <x-admin-nav-link :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')">{{ __('messages.dashboard') }}</x-admin-nav-link>
                <x-admin-nav-link :href="route('courier.checker.index')"
                    :active="request()->routeIs('courier.checker.index')">
                    {{ __('messages.courier_checker') }}
                </x-admin-nav-link>
                <x-admin-nav-link :href="route('orders.plan')"
                    :active="request()->routeIs('orders.plan')">{{ __('messages.orders_plan') }}</x-admin-nav-link>
                <x-admin-nav-link :href="route('payment.history')"
                    :active="request()->routeIs('payment.history')">{{ __('messages.billing_history') }}</x-admin-nav-link>
                <x-admin-nav-link :href="route('profile.edit')"
                    :active="request()->routeIs('profile.edit')">{{ __('messages.account_setting') }}</x-admin-nav-link>

                <h3 class="px-2 pt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('messages.support') }}</h3>
                <x-admin-nav-link :href="route('support.tickets.index')"
                    :active="request()->routeIs('support.tickets.*')">
                    {{ __('messages.my_tickets') }}
                </x-admin-nav-link>
                <x-admin-nav-link href="/#faq" :active="false">{{ __('messages.faqs') }}</x-admin-nav-link>
                <x-admin-nav-link href="/contact" :active="false">{{ __('messages.contact') }}</x-admin-nav-link>
                <x-admin-nav-link :href="route('api.documentation')" :active="request()->routeIs('api.documentation')">
                    {{ __('messages.api_documentation') }}
                </x-admin-nav-link>
            </nav>
        </aside>

        <div class="flex flex-col justify-between min-h-screen transition-all duration-300"
            :class="{ 'lg:ml-64': sidebarOpen }">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <button @click.stop="sidebarOpen = !sidebarOpen"
                                class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path x-show="!sidebarOpen" class="inline-flex" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path x-show="sidebarOpen" class="inline-flex" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-4">
                            @if(auth()->check() && auth()->user()->is_admin)
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                    {{ __('messages.admin_panel') }}
                                </x-nav-link>
                            @endif

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
                                <div x-data="{ open: false, unread_count: {{ $unread_count ?? 0 }} }"
                                    @keydown.escape.window="open = false" class="relative">
                                    <button
                                        @click="open = !open; if(unread_count > 0) { axios.post('{{ route('notifications.markasread') }}').then(() => unread_count = 0) }"
                                        class="p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                                        <span class="sr-only">{{ __('messages.view_notifications') }}</span>
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <div x-show="unread_count > 0"
                                            class="absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-600 rounded-full text-xs text-white font-semibold"
                                            x-text="unread_count"></div>
                                    </button>
                                    <div x-show="open" @click.outside="open = false"
                                        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg border z-50"
                                        style="display: none;">
                                        <div class="p-4 font-bold border-b">{{ __('messages.notifications') }}</div>
                                        <div class="py-2 max-h-96 overflow-y-auto">
                                            @forelse($notifications as $notification)
                                                <a href="{{ $notification->link ?? '#' }}"
                                                    class="block px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 @if(!$notification->read_at) bg-blue-50 @endif">
                                                    <div class="font-bold text-gray-800 flex items-center">
                                                        @if(!$notification->read_at)
                                                            <span
                                                                class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                        @endif
                                                        {{ $notification->title }}
                                                    </div>
                                                    <p class="mt-1">{{ $notification->body }}</p>
                                                    <p class="text-xs text-gray-400 mt-2">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </a>
                                            @empty
                                                <p class="text-center text-gray-500 py-6">
                                                    {{ __('messages.no_notifications') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link
                                            :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ADD THIS BLOCK TO CHECK FOR UNVERIFIED EMAIL --}}
                @auth
                    @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                        <div class="bg-yellow-400 text-center py-2 text-sm text-yellow-900 shadow-md">
                            <p>
                                {{ __('messages.email_not_verified') }}
                                <a href="{{ route('verification.notice') }}"
                                    class="font-bold underline hover:text-yellow-800">{{ __('messages.resend_verification_link_prompt') }}</a>
                            </p>
                        </div>
                    @endif
                @endauth
                {{-- END OF VERIFICATION BLOCK --}}
            </header>

            <main class="flex-1 overflow-y-auto">

                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <div class="py-12">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <x-session-messages />
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