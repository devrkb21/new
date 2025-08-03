<x-admin-layout>
    {{-- 1. Dedicated Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.registered_sites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Button is now inside the main container for better alignment --}}
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.sites.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                    {{ __('messages.add_new_site') }}
                </a>
            </div>

            {{-- 2. Improved Filter Section --}}
            <div class="p-4 bg-white rounded-lg shadow-sm mb-6">
                <form action="{{ route('admin.sites.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700">{{ __('messages.search_domain_or_owner_email') }}</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('messages.search_domain_or_owner_email_placeholder') }}">
                        </div>
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700">{{ __('messages.plan') }}</label>
                            <select name="plan_id" id="plan_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('messages.all_plans') }}</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" @if(request('plan_id') == $plan->id) selected @endif>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="active" @if(request('status') == 'active') selected @endif>{{ __('messages.active') }}</option>
                                <option value="inactive" @if(request('status') == 'inactive') selected @endif>{{ __('messages.inactive') }}</option>
                                <option value="suspended" @if(request('status') == 'suspended') selected @endif>{{ __('messages.suspended') }}</option>
                                <option value="expired" @if(request('status') == 'expired') selected @endif>{{ __('messages.expired') }}</option>
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">{{ __('messages.filter') }}</button>
                            <a href="{{ route('admin.sites.index') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50"
                                title="{{ __('messages.clear_filters') }}">{{ __('messages.clear') }}</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.domain') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.owner') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.plan') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.next_billing_date') }}</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('messages.edit') }}</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sites as $site)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="http://{{ $site->domain }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                {{ $site->domain }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ __('messages.registered_on') }} {{ $site->created_at ? $site->created_at->format('M d, Y') : 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($site->user)
                                            <div class="text-sm font-medium text-gray-900">{{ $site->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $site->user->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">{{ __('messages.no_owner') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->plan->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $statusKey = str_replace('-', '_', $site->status);
                                            $statusClasses = [
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-gray-100 text-gray-800',
                                                'suspended' => 'bg-red-100 text-red-800',
                                                'expired' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            $class = $statusClasses[strtolower($site->status)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                            {{ __("messages.{$statusKey}") }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($site->plan_expires_at)
                                            {{ $site->plan_expires_at->format('M d, Y') }}
                                        @else
                                            <span class="text-gray-400">{{ __('messages.lifetime') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.sites.edit', $site) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                            {{ __('messages.manage') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center py-16 px-6">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('messages.no_sites_found') }}</h3>
                                            <p class="mt-1 text-sm text-gray-500">{{ __('messages.no_sites_found_desc') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($sites->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $sites->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>