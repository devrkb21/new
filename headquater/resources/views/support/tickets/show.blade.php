<x-client-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="md:flex md:justify-between md:items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $ticket->title }}</h2>
                <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                    <span>{{ __('messages.regarding_site') }} <span
                            class="font-medium text-gray-700">{{ $ticket->site->domain ?? 'N/A' }}</span></span>
                    <span class="h-4 border-l"></span>
                    @php
                        $statusKey = str_replace('-', '_', $ticket->status);
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($ticket->status === 'open')
                            $statusClass = 'bg-green-100 text-green-800';
                        if ($ticket->status === 'in-progress')
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                        if ($ticket->status === 'closed')
                            $statusClass = 'bg-red-100 text-red-800';
                    @endphp
                    <span
                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                        {{ __("messages.{$statusKey}") }}
                    </span>
                </div>
            </div>

            @if ($ticket->status !== 'closed')
                <div class="mt-4 md:mt-0">
                    <form action="{{ route('support.tickets.close', $ticket) }}" method="POST"
                        onsubmit="return confirm('{{ __('messages.close_ticket_confirmation') }}');">
                        @csrf
                        @method('PATCH')
                        <x-danger-button type="submit">{{ __('messages.close_ticket') }}</x-danger-button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Conversation Thread --}}
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}&color=7F9CF5&background=EBF4FF"
                    alt="user avatar" class="h-10 w-10 rounded-full">
                <div class="flex-1">
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <p class="text-sm text-gray-800">{{ $ticket->message }}</p>
                        @if ($ticket->attachment_path)
                            <div class="mt-4 pt-3 border-t">
                                <a href="{{ route('support.tickets.attachment', $ticket) }}"
                                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    <svg class="h-5 w-5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M12.601 3.243a2.5 2.5 0 00-3.536 0L3.243 9.065a2.5 2.5 0 000 3.536l6.364 6.364a2.5 2.5 0 003.536-3.536l-4.243-4.243a.5.5 0 01.707-.707l4.243 4.243a1.5 1.5 0 01-2.122 2.121L3.95 11.314a1.5 1.5 0 010-2.121l5.822-5.822a1.5 1.5 0 012.121 2.121L9.065 8.42a.5.5 0 01-.707.707l2.828-2.828a2.5 2.5 0 000-3.536l-.001-.001z" />
                                    </svg>
                                    {{ __('messages.view_attached_file') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.posted_by') }} {{ $ticket->user->name }}
                        {{ __('messages.on_date') }} {{ $ticket->created_at->format('M d, Y, h:i A') }}</p>
                </div>
            </div>

            @foreach($ticket->replies as $reply)
                <div class="flex items-start gap-4 {{ $reply->user->is_admin ? 'justify-end' : '' }}">
                    @if($reply->user->is_admin)
                        <div class="flex-1 order-1">
                            <div class="bg-indigo-100 rounded-lg p-4">
                                <p class="text-sm text-gray-800">{{ $reply->message }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">{{ __('messages.replied_by') }}
                                {{ $reply->user->name }} {{ __('messages.on_date') }}
                                {{ $reply->created_at->format('M d, Y, h:i A') }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin&color=4338CA&background=C7D2FE" alt="admin avatar"
                            class="h-10 w-10 rounded-full order-2">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&color=7F9CF5&background=EBF4FF"
                            alt="user avatar" class="h-10 w-10 rounded-full">
                        <div class="flex-1">
                            <div class="bg-white rounded-lg shadow-sm p-4">
                                <p class="text-sm text-gray-800">{{ $reply->message }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.replied_by') }} {{ $reply->user->name }}
                                {{ __('messages.on_date') }} {{ $reply->created_at->format('M d, Y, h:i A') }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Reply Form Section --}}
        @if ($ticket->status !== 'closed')
            <div class="pt-6 border-t">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('messages.post_a_reply') }}</h3>
                <div class="bg-white rounded-lg shadow-sm">
                    <form action="{{ route('support.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="p-4">
                            <textarea name="message" rows="5"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                placeholder="{{ __('messages.type_your_reply') }}"></textarea>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right rounded-b-lg">
                            <x-primary-button>{{ __('messages.submit_reply') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-6 border-t text-center text-gray-500 text-sm bg-gray-50 p-4 rounded-lg">
                <p>{{ __('messages.ticket_closed_message') }}</p>
            </div>
        @endif
    </div>
</x-client-layout>