@props([
    'variant' => 'primary',
    'type' => '',
    'href' => null,
])

@php
    // variantに応じたクラスを定義
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'primary-ghost' => 'outline-1 bg-blue-500 text-white hover:bg-blue-600 hover:text-white',
        'secondary' => 'bg-gray-500 hover:bg-gray-600 text-white',
        'secondary-ghost' => 'outline-1 outline-gray-600 bg-gray-200 text-gray-600 hover:bg-gray-500 hover:text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'alert' => 'bg-amber-500 hover:bg-amber-600 text-white',
        'alert-ghost' => 'outline-1 outline-orange-500 bg-amber-500 text-white hover:enabled:bg-amber-600 hover:enabled:text-white',
        'nutral' => 'bg-gray-800 hover:bg-gray-900 text-white',

        'disabled' => 'bg-gray-300 text-gray-500 cursor-not-allowed'
    ];

    // 指定されたvariantが存在しない場合はデフォルトにフォールバック
    $selectedVariant = $variants[$variant] ?? $variants['primary'];
    // 共通のベースクラス
    $baseClasses = 'inline-flex items-center justify-center px-3 py-1 border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest disabled:bg-gray-300 disabled:text-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition';
    // 全てのクラスを結合
    $classes = "{$baseClasses} {$selectedVariant}";
@endphp

@if ($href)
    {{-- <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}> --}}
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@elseif($type == 'href')
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
