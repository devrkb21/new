@props([
    'name',
    'checked' => false
])

<div class="flex items-start">
    {{-- This hidden input ensures a '0' is submitted if the checkbox is not checked --}}
    <input name="{{ $name }}" type="hidden" value="0">

    <div class="flex items-center h-5">
        <input id="{{ $name }}" 
               name="{{ $name }}" 
               type="checkbox" 
               value="1" 
               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" 
               @if($checked) checked @endif
        >
    </div>
    <div class="ml-3 text-sm">
        <label for="{{ $name }}" class="font-medium text-gray-700">{{ $slot }}</label>
    </div>
</div>