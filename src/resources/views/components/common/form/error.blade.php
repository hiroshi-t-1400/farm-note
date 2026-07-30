{{-- バリデーションメッセージ --}}
@props(['field', 'add_uuid'])

<span x-text="getError(`{{ $field }}`, `{{ $add_uuid }}`)"
    class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
    role="alert">
</span>
