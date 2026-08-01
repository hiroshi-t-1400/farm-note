{{-- バリデーションメッセージ --}}
@props([
    'field' => '',
])

<span
    x-show="getError('{{ $field }}')"
    x-text="getError('{{ $field }}')"
    class="alert alert-danger text-sm text-red-500 font-semibold px-2"
    role="alert">
</span>
