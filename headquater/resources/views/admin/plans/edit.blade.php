<x-admin-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('messages.edit_plan', ['name' => $plan->name]) }}</h2>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.plans._form', ['buttonText' => __('messages.update_plan')])
                </form>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>