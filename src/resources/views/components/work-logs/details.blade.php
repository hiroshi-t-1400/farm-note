{{-- src/resources/views/components/work-logs/details.blade.php --}}

@props([
    ''
])

@php
    $detClasses = 'group p-0.5 rounded-xs';
    $sumClasses = 'flex items-center cursor-pointer font-medium list-none';
    $slotClasses = 'mt-3 text-gray-600';
@endphp


<details {{ $attributes->merge(['class' => $detClasses]) }}>
    <summary {{ $summary->attributes->merge(['class' => $sumClasses]) }}>
        {{ $summary }}

        <svg class="w-[1em] h-[1em] text-gray-500 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </summary>

    <div {{ $slot->attributes->merge(['class' => $slotClasses]) }}>
        {{ $slot }}
    </div>
</details>
