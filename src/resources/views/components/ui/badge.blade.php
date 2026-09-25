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

@elseif ($count = -1)
    <span
        {{ $attributes->merge([
            'class' => "right-0 top-0 block h-2.5 w-2.5 rounded-full bg-red-600"
        ]) }}
        aria-label="処理待ちのタスクがあります"
    ></span>
@endif
