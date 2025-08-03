<x-client-layout>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.create_new_ticket') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.provide_ticket_detail') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <form action="{{ route('support.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <x-input-group :label="__('messages.title')" name="title" required
                        :placeholder="__('messages.title_placeholder_ticket')" />

                    <div>
                        <x-input-label for="site_id" :value="__('messages.which_site_for_ticket')" />
                        <select name="site_id" id="site_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">{{ __('messages.select_a_domain') }}</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->domain }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="priority" :value="__('messages.priority')" />
                        <select name="priority" id="priority"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="low">{{ __('messages.priority_low') }}</option>
                            <option value="medium">{{ __('messages.priority_medium') }}</option>
                            <option value="high">{{ __('messages.priority_high') }}</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('messages.message')" />
                        <textarea name="message" id="message" rows="6"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                    </div>

                    <div>
                        <x-input-label for="attachment" :value="__('messages.attachment_optional')" />
                        <input type="file" name="attachment" id="attachment" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                        " />
                        <p class="mt-1 text-xs text-gray-500">{{ __('messages.allowed_file_types') }}</p>
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>
                </div>
                <div class="p-6 bg-gray-50 rounded-b-lg text-right">
                    <x-primary-button>{{ __('messages.submit_ticket') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-client-layout>