@props(['title', 'value', 'icon' => '', 'href' => null])

@php
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif class="block bg-white overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-200 sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-sm font-medium text-gray-500 truncate">
            {{ $title }}
        </h3>
        <p class="mt-1 text-3xl font-semibold text-gray-900">
            {{ $value }}
        </p>
    </div>
</{{ $tag }}>