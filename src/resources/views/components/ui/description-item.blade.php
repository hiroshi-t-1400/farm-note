{{-- src/resources/views/components/ui/description-item.blade.php --}}


@props([
    'label' => '',
])

<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
    <dt class="text-base font-medium text-gray-600">{{ $label }}</dt>
    <dd class="mt-1 text-base text-gray-800 sm:mt-0 sm:col-span-2 border-l-4 border-gray-200 ">
        {{ $slot }}
    </dd>
</div>

