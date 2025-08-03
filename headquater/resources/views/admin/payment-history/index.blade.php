<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.payment_history') }}</h2>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.payment.history') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="md:col-span-2">
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700">{{ __('messages.search') }}</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="{{ __('messages.search_invoices_placeholder') }}">
                            </div>

                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">{{ __('messages.all_statuses') }}</option>
                                    <option value="paid" @selected(request('status') === 'paid')>{{ __('messages.paid') }}
                                    </option>
                                    <option value="due" @selected(request('status') === 'due')>{{ __('messages.due') }}
                                    </option>
                                    <option value="cancelled" @selected(request('status') === 'cancelled')>
                                        {{ __('messages.cancelled') }}</option>
                                    <option value="refunded" @selected(request('status') === 'refunded')>
                                        {{ __('messages.refunded') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">{{ __('messages.filter') }}</button>
                            <a href="{{ route('admin.payment.history') }}"
                                class="ml-4 text-sm text-gray-600 hover:underline">{{ __('messages.clear_filters') }}</a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.invoice_no') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.user') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.site') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.amount') }}</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.status') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.date') }}</th>
                                    <th class="px-6 py-3 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-600 font-medium">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                class="hover:underline">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->site->domain ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            &#2547;{{ number_format($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @php
                                                $statusKey = str_replace('-', '_', $invoice->status);
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                if ($invoice->status === 'paid')
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                if ($invoice->status === 'due')
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                if ($invoice->status === 'cancelled')
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                if ($invoice->status === 'refunded')
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ __("messages.{$statusKey}") }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ __('messages.view_details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('messages.no_invoices_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>