@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <x-input-group :label="__('messages.name')" name="name" :value="old('name', $billingPeriod->name ?? '')" required
        :placeholder="__('messages.example_monthly')" />
    <x-input-group :label="__('messages.slug')" name="slug" :value="old('slug', $billingPeriod->slug ?? '')" required
        :placeholder="__('messages.example_slug_monthly')" :helper="__('messages.slug_helper')" />
    <x-input-group :label="__('messages.duration_in_days')" name="duration_in_days" type="number"
        :value="old('duration_in_days', $billingPeriod->duration_in_days ?? 30)" required
        :helper="__('messages.duration_helper')" />
</div>

<div class="pt-8">
    {{-- The button text is passed as a variable, but we provide 'Save' as a default translation --}}
    <x-primary-button>{{ $buttonText ?? __('messages.save') }}</x-primary-button>
</div>