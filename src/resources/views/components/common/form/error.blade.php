{{-- src/resources/views/components/common/form/error.blade.php --}}

@props([
    'field' => '',
])

<div
    x-show="getError('{{ $field }}')"
    dusk="{{ $field }}-error-message"
    class="flex flex-wrap"
>
    <template x-for="error in getError('{{ $field }}')" :key="index">
        <span
            x-text="error"
            class="alert alert-danger text-sm text-red-500 font-semibold px-2"
            role="alert"
        ></span>
    </template>
</div>
