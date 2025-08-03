<x-admin-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('messages.create_new_billing_period') }}</h2>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('admin.billing-periods.store') }}" method="POST">
                    @csrf
                    @include('admin.billing-periods._form', ['buttonText' => __('messages.create_period')])
                </form>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>