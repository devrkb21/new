<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('messages.admin_dashboard') }}</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <x-stat-card :title="__('messages.total_users')" :value="$totalUsers"
                    :href="route('admin.users.index')" />

                <x-stat-card :title="__('messages.open_support_tickets')" :value="$openTickets"
                    :href="route('admin.support.tickets.index', ['status' => 'open'])" />

                <x-stat-card :title="__('messages.due_invoices')" :value="$dueInvoices"
                    :href="route('admin.payment.history', ['status' => 'due'])" />

                <x-stat-card :title="__('messages.total_site')" :value="$totalSites"
                    :href="route('admin.sites.index')" />

                <x-stat-card :title="__('messages.active_basic')" :value="$basicUsers" :href="$basicPlanId ? route('admin.sites.index', ['plan_id' => $basicPlanId]) : '#'" />

                <x-stat-card :title="__('messages.active_standard')" :value="$standardUsers" :href="$standardPlanId ? route('admin.sites.index', ['plan_id' => $standardPlanId]) : '#'" />
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">{{ __('messages.new_site_registrations_chart_title') }}</h3>
                    <div style="height: 300px;">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Recent Users --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('messages.recent_users') }}</h3>
                        <ul class="mt-4 space-y-4">
                            @forelse($latestUsers as $user)
                                <li class="flex items-center space-x-4">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span
                                            class="font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500 text-right">{{ $user->created_at->diffForHumans() }}
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <p class="text-sm text-gray-500">{{ __('messages.no_new_user') }}</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                {{-- Recent Sites --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('messages.recent_sites') }}</h3>
                        <ul class="mt-4 space-y-4">
                            @forelse($latestSites as $site)
                                <li class="flex items-center space-x-4">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span
                                            class="font-bold text-green-600">{{ strtoupper(substr($site->domain, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $site->domain }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ __('messages.plan_label') }}
                                            {{ $site->plan->name }}</p>
                                    </div>
                                    <a href="{{ route('admin.sites.edit', $site) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('messages.view') }}</a>
                                </li>
                            @empty
                                <li>
                                    <p class="text-sm text-gray-500">{{ __('messages.no_new_sites_registered') }}</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('registrationsChart').getContext('2d');
            const registrationsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: '{{ __('messages.chart_label_new_sites') }}',
                        data: @json($chartData),
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-admin-layout>