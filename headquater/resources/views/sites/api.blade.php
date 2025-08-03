<x-client-layout>
    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.api_docs_plugin_title') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('messages.api_docs_plugin_subtitle') }}</p>
    </div>

    <div class="mt-8 space-y-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('messages.getting_started_guide') }}</h3>
                <div class="mt-4 border-l-2 border-gray-200 pl-6 space-y-8">

                    <div class="relative">
                        <div class="absolute -left-[34px] top-1.5 h-4 w-4 rounded-full bg-primary-600"></div>
                        <h4 class="font-semibold">{{ __('messages.step1_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('messages.step1_desc') }}</p>
                        <div class="mt-2">
                            <a href="{{ route('plugin.download') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                                {{ __('messages.download_plugin') }}
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[34px] top-1.5 h-4 w-4 rounded-full bg-primary-600"></div>
                        <h4 class="font-semibold">{{ __('messages.step2_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('messages.step2_desc') }}</p>

                        <div class="mt-4 space-y-4" x-data="{ apiKey: '{{ $apiKey }}' }">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('messages.your_site_domain') }}</label>
                                <input type="text" readonly value="{{ $site->domain }}"
                                    class="mt-1 w-full max-w-md bg-gray-100 border-gray-300 rounded-md shadow-sm text-sm font-mono p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('messages.global_api_key') }}</label>
                                <div class="relative mt-1 max-w-md">
                                    <input type="text" readonly :value="apiKey"
                                        class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-sm font-mono p-2 pr-10">
                                    <button @click="navigator.clipboard.writeText(apiKey); alert('{{ __('messages.api_key_copied') }}')"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-primary-600"
                                        title="{{ __('messages.copy_to_clipboard') }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[34px] top-1.5 h-4 w-4 rounded-full bg-primary-600"></div>
                        <h4 class="font-semibold">{{ __('messages.step3_title') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('messages.step3_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.api_endpoint_reference') }}</h3>

                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-800">{{ __('messages.api_headers') }}</h4>
                    <p class="mt-2 text-sm text-gray-600">{{ __('messages.api_headers_desc') }}</p>
                    <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                        <li><code class="bg-gray-200 p-1 rounded">X-Api-Key</code>: {{ __('messages.api_key_header_desc') }}</li>
                        <li><code class="bg-gray-200 p-1 rounded">X-Site-Domain</code>: {{ __('messages.site_domain_header_desc') }}</li>
                    </ul>
                </div>

                <div class="space-y-10">
                    @foreach([
                        ['key' => 'get_status', 'method' => 'GET', 'url' => '/api/v1/sites/status'],
                        ['key' => 'increment_usage', 'method' => 'POST', 'url' => '/api/v1/sites/increment-usage', 'params' => ['param_action_type_increment']],
                        ['key' => 'decrement_usage', 'method' => 'POST', 'url' => '/api/v1/sites/decrement-usage', 'params' => ['param_action_type_decrement']],
                        ['key' => 'update_settings', 'method' => 'POST', 'url' => '/api/v1/sites/settings', 'params' => ['param_checkout_tracking', 'param_fraud_blocker', 'param_courier_service', 'param_data_retention']]
                    ] as $endpoint)
                        <div>
                            <h4 class="text-md font-semibold text-gray-700">{{ __("messages.endpoint_{$endpoint['key']}_title") }}</h4>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $endpoint['method'] === 'GET' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ __("messages.{$endpoint['method']}") }}
                                </span>
                                <code class="text-sm font-mono bg-gray-100 p-1 rounded">{{ $endpoint['url'] }}</code>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ __("messages.endpoint_{$endpoint['key']}_desc") }}</p>
                            @if(isset($endpoint['params']))
                                <h5 class="font-semibold text-sm mt-3">{{ __('messages.body_parameters') }}</h5>
                                <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                                    @foreach($endpoint['params'] as $param)
                                        <li><code class="text-gray-600">{{ __("messages.{$param}") }}</code></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-client-layout>