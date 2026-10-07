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
