<footer class="bg-white dark:bg-gray-900">
    <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    <!-- <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white ml-3">{{ config('app.name', 'Laravel') }}</span> -->
                </a>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                    {{ __('messages.footer_description') }}
                </p>
            </div>
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
                        {{ __('messages.resources') }}
                    </h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('pricing') }}" class="hover:underline">{{ __('messages.pricing') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('api.documentation') }}"
                                class="hover:underline">{{ __('messages.api_documentation') }}</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
                        {{ __('messages.company') }}
                    </h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('contact') }}" class="hover:underline">{{ __('messages.contact_us') }}</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('about.us') }}" class="hover:underline">{{ __('messages.about_us') }}</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
                        {{ __('messages.legal') }}
                    </h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="{{ route('privacy.policy') }}"
                                class="hover:underline">{{ __('messages.privacy_policy') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('terms.conditions') }}"
                                class="hover:underline">{{ __('messages.terms_conditions') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>