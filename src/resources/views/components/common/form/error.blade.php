{{-- バリデーションメッセージ --}}
@props(['field', 'addUuid' => ''])

<span x-show="getError(`{{ $field }}`, {{ $addUuid }})"
    x-text="getError(`{{ $field }}`, {{ $addUuid }})"
    {{-- {{ $attributes->merge(['class' => 'alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2']) }} --}}
    class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
    role="alert">
</span>
