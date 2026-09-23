{{-- src/resources/views/components/ui/bottom-buttons.blade.php src/resources/views/components/ui/button.blade.php src/resources/views/components/ui/del-popover.blade.php src/resources/views/components/ui/description-item.blade.php src/resources/views/components/ui/description-request.blade.php src/resources/views/components/ui/description-row.blade.php src/resources/views/components/ui/empty-state.blade.php src/resources/views/components/ui/form-group.blade.php src/resources/views/components/ui/form-label.blade.php src/resources/views/components/ui/input.blade.php src/resources/views/components/ui/pagenation.blade.php src/resources/views/components/ui/select.blade.php src/resources/views/components/ui/textarea.blade.php --}}

@props([
    'label' => '',
])

<div class="p-1 sm:px-5 my-1 flex flex-wrap justify-start bg-white">
    <dt class="text-base font-semibold text-gray-800 w-full max-w-[6rem] sm:max-w-[10rem]">
        {{ $label }}
    </dt>
    <dd class="text-base text-gray-800 sm:mt-0">
        {{ $slot }}
    </dd>
</div>
