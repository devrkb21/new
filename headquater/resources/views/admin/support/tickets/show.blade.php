<x-admin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800">{{ $ticket->title }}</h2>
            <div class="text-sm text-gray-500 mb-4">
                <p>{{ __('messages.ticket_from') }} {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                <p>{{ __('messages.for_site') }}
                    <a href="{{ $ticket->site ? route('admin.sites.edit', $ticket->site) : '#' }}"
                        class="text-indigo-600 hover:underline">
                        {{ $ticket->site->domain ?? 'N/A' }}
                    </a>
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <div class="border-b pb-4">
                    <p class="text-gray-700">{{ $ticket->message }}</p>
                    @if($ticket->attachment_path)
                        <div class="mt-4">
                            <a href="{{ route('support.tickets.attachment', $ticket) }}"
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M12.601 3.243a2.5 2.5 0 00-3.536 0L3.243 9.065a2.5 2.5 0 000 3.536l6.364 6.364a2.5 2.5 0 003.536-3.536l-4.243-4.243a.5.5 0 01.707-.707l4.243 4.243a1.5 1.5 0 01-2.122 2.121L3.95 11.314a1.5 1.5 0 010-2.121l5.822-5.822a1.5 1.5 0 012.121 2.121L9.065 8.42a.5.5 0 01-.707.707l2.828-2.828a2.5 2.5 0 000-3.536l-.001-.001z" />
                                </svg>
                                {{ __('messages.view_attachment') }}
                            </a>
                        </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ __('messages.posted_by') }} {{ $ticket->user->name }}
                        {{ __('messages.on_date') }} {{ $ticket->created_at->format('M d, Y, h:i A') }}</p>
                </div>

                <div class="space-y-4">
                    @foreach($ticket->replies as $reply)
                        <div class="p-4 rounded-md {{ $reply->user->is_admin ? 'bg-indigo-50' : 'bg-gray-50' }}">
                            <p class="text-gray-700">{{ $reply->message }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ __('messages.replied_by') }} {{ $reply->user->name }} {{ __('messages.on_date') }}
                                {{ $reply->created_at->format('M d, Y, h:i A') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t grid md:grid-cols-2 gap-6 items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ __('messages.post_a_reply') }}</h3>
                        <form action="{{ route('admin.support.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <textarea name="message" rows="5"
                                class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            <div class="mt-4 text-right">
                                <x-primary-button>{{ __('messages.submit_reply') }}</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ __('messages.update_status') }}</h3>
                        <form action="{{ route('admin.support.tickets.status', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="open" @if($ticket->status === 'open') selected @endif>
                                    {{ __('messages.open') }}</option>
                                <option value="in-progress" @if($ticket->status === 'in-progress') selected @endif>
                                    {{ __('messages.in_progress') }}</option>
                                <option value="closed" @if($ticket->status === 'closed') selected @endif>
                                    {{ __('messages.closed') }}</option>
                            </select>
                            <div class="mt-4 text-right">
                                <x-secondary-button
                                    type="submit">{{ __('messages.update_status') }}</x-secondary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>