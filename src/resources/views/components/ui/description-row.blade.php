<!-- src/resources/views/components/ui/description-row.php -->

@props([
    'label' => ''
])

    <span class="text-base font-medium text-gray-600">
        {{ $label }}
    </span>
    <span class="sm:ps-0 ps-[1em] text-base text-gray-800">
        {{ $slot }}
    </span>
