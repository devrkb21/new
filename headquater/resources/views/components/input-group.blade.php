@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'helper' => '',
    'required' => false,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"
               class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               @if($required) required @endif>
    </div>

    @if($helper)
        <p class="mt-2 text-sm text-gray-500">{{ $helper }}</p>
    @endif
</div>