<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.support_tickets') }}</h2>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.support.tickets.index') }}" method="GET"
                        class="mb-4 flex items-center space-x-4">
                        <div>
                            <label for="search" class="sr-only">{{ __('messages.search') }}</label>
                            <input type="text" name="search" id="search" placeholder="{{ __('messages.search_ticket_placeholder') }}"
                                value="{{ request('search') }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="status" class="sr-only">{{ __('messages.filter_by_status') }}</label>
                            <select name="status" id="status"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="open" @if(request('status') === 'open') selected @endif>{{ __('messages.open') }}</option>
                                <option value="in-progress" @if(request('status') === 'in-progress') selected @endif>{{ __('messages.in_progress') }}</option>
                                <option value="closed" @if(request('status') === 'closed') selected @endif>{{ __('messages.closed') }}</option>
                            </select>
                        </div>
                        <x-primary-button type="submit">
                            {{ __('messages.search') }}
                        </x-primary-button>
                        @if(request()->has('search') || request()->has('status'))
                            <a href="{{ route('admin.support.tickets.index') }}"
                                class="text-sm text-gray-500 hover:text-gray-700">{{ __('messages.clear') }}</a>
                        @endif
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.user') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.title') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.domain') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.status') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.last_updated') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600">
                                            <a href="{{ route('admin.support.tickets.show', $ticket) }}"
                                                class="hover:underline">
                                                {{ Str::limit($ticket->title, 30) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a href="{{ $ticket->site ? route('admin.sites.edit', $ticket->site) : '#' }}"
                                                class="hover:underline">
                                                {{ $ticket->site->domain ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @php
                                                $statusKey = str_replace('-', '_', $ticket->status);
                                                $statusClass = '';
                                                switch ($ticket->status) {
                                                    case 'open': $statusClass = 'bg-green-100 text-green-800'; break;
                                                    case 'in-progress': $statusClass = 'bg-yellow-100 text-yellow-800'; break;
                                                    default: $statusClass = 'bg-gray-100 text-gray-800'; break;
                                                }
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ __("messages.{$statusKey}") }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('messages.no_support_tickets_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>