<x-client-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.my_support_tickets') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('messages.view_support_requests') }}</p>
            </div>
            <div>
                <a href="{{ route('support.tickets.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                    {{ __('messages.create_new_ticket') }}
                </a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.title') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.domain') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.priority') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.status') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.last_updated') }}</th>
                            {{-- ADDED ACTION HEADER --}}
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">{{ __('messages.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-600 font-medium">
                                    <a href="{{ route('support.tickets.show', $ticket) }}" class="hover:underline">
                                        {{ Str::limit($ticket->title, 35) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->site->domain ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ __("messages.priority_{$ticket->priority}") }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @php
                                        $statusClass = '';
                                        $statusKey = str_replace('-', '_', $ticket->status);
                                        switch ($ticket->status) {
                                            case 'open':
                                                $statusClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'in-progress':
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                break;
                                        }
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ __("messages.{$statusKey}") }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                {{-- ADDED ACTION CELL --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('support.tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('messages.view_details') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    {{ __('messages.no_support_tickets_yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
</x-client-layout>