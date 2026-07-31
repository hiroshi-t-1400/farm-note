@props([
    'label' => '',
    'name' => '',
    'required' => false
])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 gap-1 bg-white mb-1 px-1 py-1']) }}>
    @if ($label)
        <x-ui.form-label :for="$name">
            {{ $label }}
        </x-ui.form-label>
    @endif

    {{ $slot }}

    <x-common.form.error field='title' />
</div>
