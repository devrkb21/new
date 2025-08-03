<x-client-layout>
    <div x-data="courierChecker()" class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.courier_checker_title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.courier_checker_subtitle') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <form @submit.prevent="performCheck" class="p-6 space-y-4">
                @csrf
                <div>
                    <x-input-label for="phone_number" :value="__('messages.customer_phone_number')" />
                    <x-text-input id="phone_number" x-model="phoneNumber" class="mt-1 block w-full" type="text"
                        name="phone_number" :placeholder="__('messages.phone_number_placeholder')" required />
                </div>

                <div>
                    <x-input-label for="site_id" :value="__('messages.select_site_for_tracking')" />
                    <select x-model="siteId" id="site_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        required>
                        <option value="">{{ __('messages.select_a_site') }}</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->domain }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <x-primary-button type="submit" x-bind:disabled="loading">
                        <span x-show="loading" class="animate-spin mr-2 -ml-1 h-5 w-5 text-white">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                        <span x-text="loading ? translations.checking : translations.check_success_rate"></span>
                    </x-primary-button>
                </div>
            </form>
        </div>

        <div x-show="error" x-cloak class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" x-text="error">
        </div>

        <div x-show="results" x-cloak class="bg-white rounded-lg shadow-sm p-6" id="results-container">
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- This script block passes translations to your JavaScript component --}}
        <script>
            const translations = {
                error_select_site: "{{ __('messages.error_select_site') }}",
                error_unexpected: "{{ __('messages.error_unexpected') }}",
                checking: "{{ __('messages.checking') }}",
                check_success_rate: "{{ __('messages.check_success_rate') }}",
                results_for: "{{ __('messages.results_for') }}",
                total_orders: "{{ __('messages.total_orders') }}",
                delivered: "{{ __('messages.delivered') }}",
                canceled: "{{ __('messages.canceled') }}",
                courier: "{{ __('messages.courier') }}",
                orders: "{{ __('messages.orders') }}",
                rate: "{{ __('messages.rate') }}",
            };
        </script>

        <script>
            function courierChecker() {
                return {
                    loading: false,
                    phoneNumber: '',
                    siteId: '{{ $sites->first()->id ?? '' }}',
                    results: null,
                    error: '',
                    chart: null,
                    performCheck() {
                        if (!this.siteId) {
                            this.error = translations.error_select_site;
                            return;
                        }
                        this.loading = true;
                        this.error = '';
                        this.results = null;

                        axios.post('{{ route('courier.checker.check') }}', {
                            phone_number: this.phoneNumber,
                            site_id: this.siteId
                        })
                            .then(response => {
                                this.results = response.data;
                                this.$nextTick(() => this.renderResults());
                            })
                            .catch(error => {
                                if (error.response && error.response.data && error.response.data.message) {
                                    this.error = error.response.data.message;
                                } else {
                                    this.error = translations.error_unexpected;
                                }
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },
                    renderResults() {
                        const container = document.getElementById('results-container');
                        const logos = {
                            pathao: 'https://pathao.com/wp-content/uploads/sites/6/2019/02/Pathao_Logo-.svg',
                            steadfast: 'https://steadfast.com.bd/landing-page/asset/images/logo/logo.svg',
                            redx: 'https://redx.com.bd/images/new-redx-logo.svg'
                        };

                        const formatRow = (name, data) => {
                            const rate = data.total > 0 ? Math.round((data.success / data.total) * 100) + '%' : 'N/A';
                            return `
                                    <tr class="border-b">
                                        <td class="px-4 py-3"><img src="${logos[name]}" alt="${name}" class="h-6"></td>
                                        <td class="px-4 py-3 font-mono text-center">${data.error ? 'N/A' : data.total}</td>
                                        <td class="px-4 py-3 font-mono text-center text-green-600">${data.error ? 'N/A' : data.success}</td>
                                        <td class="px-4 py-3 font-mono text-center text-red-600">${data.error ? 'N/A' : data.cancelled}</td>
                                        <td class="px-4 py-3 font-semibold text-center">${rate}</td>
                                    </tr>
                                `;
                        };

                        container.innerHTML = `
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-1 flex flex-col items-center justify-center">
                                        <div class="relative w-40 h-40">
                                            <canvas id="courier-chart"></canvas>
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                                <div class="text-3xl font-bold text-gray-900">${this.results.grand_total.rate}%</div>
                                                <div class="text-sm font-semibold" style="color: ${this.results.level.color};">${this.results.level.name}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <h3 class="text-lg font-bold text-gray-800">${translations.results_for} ${this.phoneNumber}</h3>
                                        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                                            <div class="bg-gray-100 p-4 rounded-lg">
                                                <span class="text-2xl font-bold text-gray-800">${this.results.grand_total.total}</span>
                                                <span class="text-sm text-gray-600 block">${translations.total_orders}</span>
                                            </div>
                                             <div class="bg-green-100 p-4 rounded-lg">
                                                <span class="text-2xl font-bold text-green-700">${this.results.grand_total.success}</span>
                                                <span class="text-sm text-green-800 block">${translations.delivered}</span>
                                            </div>
                                             <div class="bg-red-100 p-4 rounded-lg">
                                                <span class="text-2xl font-bold text-red-700">${this.results.grand_total.cancelled}</span>
                                                <span class="text-sm text-red-800 block">${translations.canceled}</span>
                                            </div>
                                        </div>
                                        <div class="mt-6 overflow-x-auto">
                                            <table class="min-w-full text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left font-semibold text-gray-600">${translations.courier}</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-600">${translations.orders}</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-600">${translations.delivered}</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-600">${translations.canceled}</th>
                                                        <th class="px-4 py-2 text-center font-semibold text-gray-600">${translations.rate}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y">
                                                    ${formatRow('pathao', this.results.pathao)}
                                                    ${formatRow('steadfast', this.results.steadfast)}
                                                    ${formatRow('redx', this.results.redx)}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;

                        this.renderChart();
                    },
                    renderChart() {
                        if (this.chart) {
                            this.chart.destroy();
                        }
                        const ctx = document.getElementById('courier-chart').getContext('2d');
                        this.chart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [this.results.grand_total.rate, 100 - this.results.grand_total.rate],
                                    backgroundColor: [this.results.level.color, '#e5e7eb'],
                                    borderWidth: 0,
                                    cutout: '80%'
                                }]
                            },
                            options: {
                                plugins: {
                                    tooltip: { enabled: false }
                                }
                            }
                        });
                    }
                }
            }
        </script>
    @endpush
</x-client-layout>