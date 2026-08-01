@props([
    'type' => 'text',
    'name' => '',
    'id' => '',
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-white border-2 border-gray-500 text-gray-900 placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed',
        'highlight' => 'bg-white border-2 border-indigo-300 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500',
        'error' => 'bg-red-50 border border-red-500 text-gray-900 placeholder-red-300 focus:ring-1 focus:ring-red-500 focus:border-red-500',
    ];

    if ($variant == 'readonly') {
        // readonlyの例外
        $classes = 'bg-gray-50 border border-gray-200 rounded-sm px-2 sm:py-1 py-2 shadow-sm text-base text-gray-700 cursor-default select-none';
    } else {
        $selectedVariant = $variants[$variant] ?? $variants['default'];
        $baseClasses = 'text-base rounded-sm px-2 sm:py-0 py-2 shadow-sm transition';
        $classes = "{$baseClasses} {$selectedVariant}";
    }
    $id = $id ?: $name;

@endphp

@if ($type == 'number' || $type == 'numeric')
    {{-- Alpine.jsによって半角英数をふるいにかけているので、万が一Alpine.jsのスコープ外で呼び出す場合は文字列の入力が許容されているので注意すること --}}
    @php
        $classes = "{$classes} text-right";
    @endphp
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $id }}"
        inputmode="numeric"
        x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')"
        {{ $attributes->merge(['class' => $classes]) }}
    >
{{-- <input type="text" inputmode="numeric" {{ $attributes->merge(['class' => $classes, '@input' => "\$el.value = \$el.value.replace(/[^0-9]/g, '')"]) }}> --}}
@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $attributes->merge(['class' => $classes]) }}>
@endif
