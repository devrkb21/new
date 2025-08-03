<x-client-layout>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.add_new_site_page_title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.add_new_site_page_desc') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <form action="{{ route('sites.link') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <x-input-group :label="__('messages.site_domain')" name="domain" required
                        :placeholder="__('messages.site_domain_placeholder')"
                        :helper="__('messages.site_domain_helper')" />
                </div>
                <div class="p-6 bg-gray-50 rounded-b-lg text-right">
                    <x-primary-button>{{ __('messages.add_site') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-client-layout>