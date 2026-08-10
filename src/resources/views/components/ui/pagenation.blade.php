@props([
    ''
    ])

@php
    $wrapClasses = 'flex flex-wrap gap-4 ps-5';

    $enable = 'hover:text-gray-900 hover:underline transition-colors duration-150';
    $disable = 'text-gray-300 select-none';
@endphp


<div {{ $attributes->merge(['class' => $wrapClasses]) }}>

    <template x-if="prev">
        <a :href="prev" class="{{ $enable }}" >前へ</a>
    </template>
    <template x-if="!prev">
        <a class="{{ $disable }}" tabindex="-1" >前へ</a>
    </template>

    <template x-if="next">
        <a :href="next" class="{{ $enable }}" >次へ</a>
    </template>
    <template x-if="!next">
        <a class="{{ $disable }}" tabindex="-1" >次へ</a>
    </template>

</div>
