<x-admin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-session-messages />

            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">
                        {{ __('messages.invoice_title', ['number' => $invoice->invoice_number]) }}</h2>
                    @php
                        $statusKey = str_replace('-', '_', $invoice->status);
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($invoice->status === 'paid')
                            $statusClass = 'bg-green-100 text-green-800';
                        if ($invoice->status === 'due')
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                        if ($invoice->status === 'cancelled')
                            $statusClass = 'bg-red-100 text-red-800';
                    @endphp
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">{{ __("messages.{$statusKey}") }}</span>
                </div>
                <div>
                    <a href="{{ route('admin.invoices.download', $invoice) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        {{ __('messages.download_pdf') }}
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200 grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-xs text-gray-500 uppercase tracking-wider">{{ __('messages.billed_to') }}</h3>
                        <p class="text-gray-800 font-medium">{{ $invoice->user->name }}</p>
                        <p class="text-gray-600">{{ $invoice->user->email }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-xs text-gray-500 uppercase tracking-wider">{{ __('messages.invoice_date') }}
                        </h3>
                        <p class="text-gray-800 font-medium">{{ $invoice->created_at->format('M d, Y') }}</p>
                        <h3 class="text-xs text-gray-500 uppercase tracking-wider mt-2">{{ __('messages.due_date') }}
                        </h3>
                        <p class="text-gray-800 font-medium">{{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($invoice->transactions->isNotEmpty())
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xs text-gray-500 uppercase tracking-wider mb-2">
                            {{ __('messages.bkash_transactions') }}</h3>
                        @foreach($invoice->transactions->sortBy('created_at') as $transaction)
                            @if($transaction->status == 'refunded')
                                <p class="text-sm text-gray-800 mt-1">{{ __('messages.bkash_refund_id') }}:
                                    {{ $transaction->gateway_transaction_id }}</p>
                            @else
                                <p class="text-sm text-gray-800 mt-1">{{ __('messages.bkash_payment_id') }}:
                                    {{ $transaction->gateway_transaction_id }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="p-6">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="py-2 text-left text-sm font-medium text-gray-500">
                                    {{ __('messages.description') }}</th>
                                <th class="py-2 text-right text-sm font-medium text-gray-500">
                                    {{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                <tr class="border-b">
                                    <td class="py-4">
                                        <p class="font-medium text-gray-800">{{ $item->description }}</p>
                                        <p class="text-sm text-gray-600">{{ __('messages.for_site') }}
                                            {{ $invoice->site->domain }}</p>
                                    </td>
                                    <td class="py-4 text-right font-medium">&#2547;{{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="py-4 text-right font-medium text-gray-500">{{ __('messages.subtotal') }}</td>
                                <td class="py-4 text-right font-medium text-gray-800">
                                    &#2547;{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-right font-bold text-gray-900">{{ __('messages.total_due') }}</td>
                                <td class="py-2 text-right font-bold text-lg text-primary-600">
                                    &#2547;{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4">
                    @if($invoice->status == 'due')
                        <form action="{{ route('invoices.cancel', $invoice) }}" method="POST"
                            onsubmit="return confirm('{{ __('messages.cancel_invoice_confirm') }}');">
                            @csrf
                            <x-secondary-button type="submit">{{ __('messages.cancel_invoice') }}</x-secondary-button>
                        </form>
                        <form action="{{ route('admin.invoices.markaspaid', $invoice) }}" method="POST"
                            onsubmit="return confirm('{{ __('messages.mark_as_paid_confirm') }}');">
                            @csrf
                            <x-primary-button type="submit">{{ __('messages.mark_as_paid') }}</x-primary-button>
                        </form>
                    @endif

                    @if($invoice->status == 'paid')
                        <div>
                            <x-danger-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-refund')">{{ __('messages.refund_via_bkash') }}</x-danger-button>

                            <x-modal name="confirm-refund" :show="$errors->get('reason') ? true : false" focusable>
                                <form action="{{ route('admin.invoices.refund', $invoice) }}" method="POST" class="p-6">
                                    @csrf
                                    <h2 class="text-lg font-medium text-gray-900">{{ __('messages.confirm_refund') }}</h2>
                                    <p class="mt-1 text-sm text-gray-600">{{ __('messages.refund_reason_prompt') }}</p>
                                    <div class="mt-6">
                                        <x-input-label for="reason" :value="__('messages.reason_for_refund')" />
                                        <x-text-input id="reason" name="reason" class="mt-1 block w-full"
                                            :placeholder="__('messages.reason_placeholder')" required autofocus />
                                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button
                                            x-on:click="$dispatch('close')">{{ __('messages.cancel') }}</x-secondary-button>
                                        <x-danger-button class="ms-3">{{ __('messages.process_refund') }}</x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>