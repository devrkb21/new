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

    <header class="bg-white shadow-sm">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="flex h-16 items-center justify-between border-b border-gray-200">
                {{-- Left Section: Logo and Navigation Links --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                        <x-application-logo class="h-[45px] w-auto" />
                    </a>
                    <div class="ml-10 hidden space-x-8 lg:block">
                        <a href="{{ route('home') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.home') }}</a>
                        <a href="{{ route('pricing') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.pricing') }}</a>
                        <a href="{{ route('contact') }}"
                            class="text-base font-medium text-gray-500 hover:text-gray-900">{{ __('messages.contact_us') }}</a>
                    </div>
                </div>

                {{-- Right Section: Auth Buttons & Language Switcher --}}
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center space-x-4">
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
                </div>
            </div>
            {{-- Mobile Nav --}}
            <div class="flex flex-wrap justify-center items-center py-4 lg:hidden">
                <a href="{{ route('home') }}"
                    class="text-base font-medium text-gray-500 hover:text-gray-900 mx-3">{{ __('messages.home') }}</a>
                <a href="{{ route('pricing') }}"
                    class="text-base font-medium text-gray-500 hover:text-gray-900 mx-3">{{ __('messages.pricing') }}</a>
                <a href="{{ route('contact') }}"
                    class="text-base font-medium text-gray-500 hover:text-gray-900 mx-3">{{ __('messages.contact_us') }}</a>
                <div class="ml-4">
                    @if (app()->getLocale() == 'bn')
                        <a href="{{ route('language.switch', 'en') }}"
                            class="inline-block rounded-md border border-gray-200 px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">
                            EN
                        </a>
                    @else
                        <a href="{{ route('language.switch', 'bn') }}"
                            class="inline-block rounded-md border border-gray-200 px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">
                            বাংলা
                        </a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>