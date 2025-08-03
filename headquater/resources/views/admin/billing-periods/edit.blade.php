<x-admin-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('messages.edit_billing_period', ['name' => $billingPeriod->name]) }}</h2>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('admin.billing-periods.update', $billingPeriod) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.billing-periods._form', ['billingPeriod' => $billingPeriod, 'buttonText' => __('messages.update_period')])
                </form>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>