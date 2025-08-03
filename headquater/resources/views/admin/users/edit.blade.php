<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.edit_user_title', ['name' => $user->name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{
            submitVerification(url) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                let token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            }
        }">

            <x-session-messages />

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8">

                    {{-- Left Column: User Details & Password --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Card 1: Profile Information --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    {{ __('messages.profile_information') }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.name') }}</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.email_address') }}</label>
                                        <div class="mt-1 flex items-center space-x-2">
                                            <input type="email" name="email" id="email"
                                                value="{{ old('email', $user->email) }}"
                                                class="flex-grow border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required>

                                            @if ($user->hasVerifiedEmail())
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 shrink-0">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ __('messages.verified') }}
                                                </span>
                                            @else
                                                <button type="button"
                                                    @click="submitVerification('{{ route('admin.users.verify-email', $user) }}')"
                                                    class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded hover:bg-indigo-200 shrink-0">
                                                    {{ __('messages.mark_as_verified') }}
                                                </button>
                                            @endif
                                        </div>
                                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.phone_number') }}</label>
                                        <div class="mt-1 flex items-center space-x-2">
                                            <input type="text" name="phone" id="phone"
                                                value="{{ old('phone', $user->phone) }}"
                                                class="flex-grow border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @if ($user->phone_verified_at)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 shrink-0">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ __('messages.verified') }}
                                                </span>
                                            @else
                                                <button type="button"
                                                    @click="submitVerification('{{ route('admin.users.verify-phone', $user) }}')"
                                                    class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded hover:bg-indigo-200 shrink-0">
                                                    {{ __('messages.mark_as_verified') }}
                                                </button>
                                            @endif
                                        </div>
                                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.address') }}</label>
                                        <input type="text" name="address" id="address"
                                            value="{{ old('address', $user->address) }}"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Change Password --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.change_password') }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">{{ __('messages.leave_password_blank') }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.new_password') }}</label>
                                        <input type="password" name="password" id="password"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700">{{ __('messages.confirm_new_password') }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 flex items-center justify-start gap-4">
                                <x-primary-button>{{ __('messages.save_changes') }}</x-primary-button>
                                <a href="{{ route('admin.users.index') }}"
                                    class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('messages.cancel') }}</a>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Permissions & Delete --}}
                    <div class="space-y-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    {{ __('messages.permissions_and_status') }}</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center">
                                        <input type="hidden" name="is_admin" value="0">
                                        <input type="checkbox" name="is_admin" value="1"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            @if(old('is_admin', $user->is_admin)) checked @endif>
                                        <span
                                            class="ml-2 text-sm text-gray-600">{{ __('messages.make_user_admin') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-l-4 border-red-500">
                                <h3 class="text-lg font-medium text-red-600">{{ __('messages.delete_user') }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ __('messages.delete_user_warning_short') }}</p>
                                @if(auth()->id() !== $user->id && $user->id !== 1)
                                    <div class="mt-4" x-data>
                                        <x-danger-button
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                                            {{ __('messages.delete_user') }}
                                        </x-danger-button>
                                    </div>
                                @else
                                    <p class="mt-4 text-sm font-semibold text-gray-500">
                                        {{ __('messages.core_admin_cannot_be_deleted') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Deletion Confirmation Modal --}}
            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('admin.users.destroy', $user) }}" class="p-6">
                    @csrf
                    @method('delete')
                    <h2 class="text-lg font-medium text-gray-900">{{ __('messages.are_you_sure') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('messages.delete_user_modal_desc') }}
                    </p>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button
                            x-on:click="$dispatch('close')">{{ __('messages.cancel') }}</x-secondary-button>
                        <x-danger-button class="ml-3">{{ __('messages.delete_user') }}</x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-admin-layout>