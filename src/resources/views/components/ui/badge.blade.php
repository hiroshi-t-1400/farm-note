@props([
    'count' => 0,
])

@if ($count > 0)
    <span
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center px-2 py-0.5 ml-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full'
        ]) }}
    >
        {{ $count }}
    </span>
@endif
