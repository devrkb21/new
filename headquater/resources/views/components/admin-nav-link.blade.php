@props(['href', 'active'])

@php
$classes = ($active ?? false)
            // Classes for the ACTIVE link
            ? 'flex items-center px-3 py-2 text-sm font-medium text-primary-700 bg-primary-50 rounded-md'
            // Classes for INACTIVE links
            : 'flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-100 hover:text-gray-900';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>