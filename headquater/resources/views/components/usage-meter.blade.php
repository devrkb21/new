@props([
    'title',
    'used',
    'limit',
])

@php
    $isUnlimited = ($limit == 0 || $limit === null);
    $percentage = $isUnlimited ? 0 : (($limit > 0) ? round(($used / $limit) * 100) : 0);
    
    // Ensure the visual percentage doesn't exceed 100 for the arc
    $arcPercentage = min($percentage, 100);

    // SVG arc calculations
    $radius = 50;
    $circumference = 2 * pi() * $radius;
    $arcLength = ($arcPercentage / 100) * ($circumference / 2); // Half circle

    // Color logic
    $colorClass = 'text-indigo-600';
    $strokeColor = '#4f46e5';
    if (!$isUnlimited) {
        if ($percentage > 90) {
            $colorClass = 'text-red-500';
            $strokeColor = '#ef4444';
        } elseif ($percentage > 70) {
            $colorClass = 'text-amber-500';
            $strokeColor = '#f59e0b';
        }
    }
@endphp

<div class="flex flex-col items-center">
    <div class="relative w-40 h-20">
        {{-- The gauge SVG --}}
        <svg class="w-full h-full" viewBox="0 0 100 50">
            {{-- Background Arc --}}
            <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#e5e7eb" stroke-width="8" />
            
            {{-- Foreground (Progress) Arc --}}
            @if($arcPercentage > 0)
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="{{ $strokeColor }}" stroke-width="8" stroke-linecap="round"
                    stroke-dasharray="{{ $circumference / 2 }}"
                    stroke-dashoffset="{{ ($circumference / 2) - $arcLength }}" />
            @endif
        </svg>

        {{-- Text inside the gauge --}}
        <div class="absolute bottom-0 w-full text-center">
            @if($isUnlimited)
                <span class="text-xl font-bold text-gray-700">{{ number_format($used) }}</span>
                <p class="text-xs text-gray-500">Used</p>
            @else
                <span class="text-xl font-bold {{ $colorClass }}">{{ $percentage }}%</span>
                <p class="text-xs text-gray-500">{{ number_format($used) }} of {{ number_format($limit) }}</p>
            @endif
        </div>
    </div>
    <h3 class="text-sm font-medium text-gray-700 mt-2">{{ $title }}</h3>
</div>