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

<body class="font-sans text-gray-900 antialiased">
    <div x-data="{ showReturnToTop: false, mobileMenuOpen: false }"
        @scroll.window="showReturnToTop = (window.scrollY > 400)">

        <header class="bg-white shadow-sm relative">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Top">
                <div class="flex h-16 items-center justify-between">
                    {{-- Left Section: Logo --}}
                    <div class="flex items-center">
                        <a href="{{ route('home') }}">
                            <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                            <x-application-logo class="h-[45px] w-auto" />
                        </a>
                    </div>

                    {{-- Center Section: Desktop Navigation Links --}}
                    <div class="hidden lg:flex lg:gap-x-8">
                        <a href="{{ route('home') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.home') }}</a>
                        <a href="{{ route('pricing') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.pricing') }}</a>
                        <a href="{{ route('contact') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.contact_us') }}</a>
                    </div>

                    {{-- Right Section: Auth & Language (Desktop) --}}
                    <div class="hidden lg:flex lg:items-center lg:space-x-4">
                        <a href="{{ route('login') }}"
                            class="inline-block rounded-md border border-transparent bg-gray-100 px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-200">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}"
                            class="inline-block rounded-md border border-transparent bg-primary-600 px-4 py-2 text-base font-medium text-white hover:bg-primary-700">{{ __('messages.register') }}</a>

                        <a href="{{ app()->getLocale() == 'bn' ? route('language.switch', 'en') : route('language.switch', 'bn') }}"
                            class="flex items-center space-x-2 ml-4" aria-label="Switch Language">
                            <span
                                class="text-sm font-semibold {{ app()->getLocale() == 'en' ? 'text-primary-600' : 'text-gray-400' }}">EN</span>
                            <div
                                class="toggle-switch-track {{ app()->getLocale() == 'bn' ? 'lang-toggle-bn' : 'lang-toggle-en' }}">
                                <div class="toggle-switch-thumb"></div>
                            </div>
                            <span
                                class="text-sm font-semibold {{ app()->getLocale() == 'bn' ? 'text-primary-600' : 'text-gray-400' }}">BN</span>
                        </a>
                    </div>

                    {{-- Hamburger Menu Button (Mobile) --}}
                    <div class="flex items-center lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                            <span class="sr-only">Open main menu</span>
                            <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="block h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            {{-- Mobile Menu Panel --}}
            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="duration-200 ease-out"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-x-0 top-0 origin-top-right transform p-2 transition lg:hidden z-20">
                <div class="divide-y-2 divide-gray-50 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="px-5 pt-5 pb-6">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('home') }}">
                                <x-application-logo class="h-[40px] w-auto" />
                            </a>
                            <div class="-mr-2">
                                <button @click="mobileMenuOpen = false" type="button"
                                    class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                                    <span class="sr-only">Close menu</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-6">
                            <nav class="grid gap-y-8">
                                <a href="{{ route('home') }}"
                                    class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                                    <span class="text-base font-medium text-gray-900">{{ __('messages.home') }}</span>
                                </a>
                                <a href="{{ route('pricing') }}"
                                    class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                                    <span
                                        class="text-base font-medium text-gray-900">{{ __('messages.pricing') }}</span>
                                </a>
                                <a href="{{ route('contact') }}"
                                    class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                                    <span
                                        class="text-base font-medium text-gray-900">{{ __('messages.contact_us') }}</span>
                                </a>
                            </nav>
                        </div>
                    </div>
                    <div class="space-y-6 py-6 px-5">
                        <a href="{{ route('register') }}"
                            class="block w-full rounded-md bg-primary-600 px-5 py-3 text-center font-medium text-white shadow hover:bg-primary-700">{{ __('messages.register') }}</a>
                        <p class="mt-6 text-center text-base font-medium text-gray-500">
                            {{ __('messages.already_registered') }}
                            <a href="{{ route('login') }}"
                                class="text-primary-600 hover:text-primary-500">{{ __('messages.login') }}</a>
                        </p>
                        <div class="mt-6 flex justify-center">
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
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        {{-- "RETURN TO TOP" BUTTON --}}
        <div x-show="showReturnToTop" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4" class="fixed bottom-5 right-5 z-50">
            <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="flex items-center justify-center w-12 h-12 bg-primary-600 rounded-full text-white shadow-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-transform hover:-translate-y-1">
                <span class="sr-only">Go to top</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
            </button>
        </div>
        <x-footer />
        <x-copyright />
    </div>
</body>

</html>