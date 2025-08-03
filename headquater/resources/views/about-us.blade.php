<x-marketing-layout>
    <div class="bg-white">
        {{-- Header Section --}}
        <div class="py-24 sm:py-32 bg-gray-50">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                    {{ __('messages.about_us_title') }}
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    {{ __('messages.about_us_subtitle') }}
                </p>
            </div>
        </div>

        {{-- Main Content Section --}}
        <div class="py-24 sm:py-32">
            <div class="max-w-4xl mx-auto px-6 lg:px-8">
                <article class="prose lg:prose-xl max-w-none">
                    {{-- Our Mission --}}
                    <h2>{{ __('messages.our_mission_title') }}</h2>
                    <p>{{ __('messages.our_mission_content') }}</p>
                    
                    {{-- Our Story --}}
                    <h2>{{ __('messages.our_story_title') }}</h2>
                    <p>{{ __('messages.our_story_content') }}</p>

                    {{-- Our Team --}}
                    <h2>{{ __('messages.our_team_title') }}</h2>
                    <p>{{ __('messages.our_team_content') }}</p>
                </article>
            </div>
        </div>
    </div>
</x-marketing-layout>