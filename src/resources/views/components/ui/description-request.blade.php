{{-- src/resources/views/components/ui/description-item.blade.php --}}


@props([
    'label' => '',
])


<div class="py-0.5 flex flex-wrap justify-between">
    <dt class="text-base font-medium text-gray-600">{{ $label }}</dt>
    <dd class="text-base text-gray-800 sm:mt-0 sm:col-span-1">
        {{ $slot }}
    </dd>
</div>
