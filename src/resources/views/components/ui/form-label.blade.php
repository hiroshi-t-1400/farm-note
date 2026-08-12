@props([
    'required' => false
])

@php
    $baseClasses = 'block font-semibold text-sm text-gray-700';
@endphp

<label {{ $attributes->merge(['class' => $baseClasses]) }}>
    {{ $slot }}
    @if ($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>
