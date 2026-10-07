{{-- /var/www/src/resources/views/components/presentation/index-item.blade.php --}}
@props([
    'labels' => '',
    'show_link' => '',
    'large_values' => '',
    'small_values' => '',
])

<x-ui.pagenation />

<div class="hidden sm:flex flex-col py-4">
    <div class="grid grid-cols-[repeat(auto-fill,_8rem)] gap-x-4 items-center
        pb-2 mb-2 border-b-2 border-gray-200 text-xs font-bold text-gray-500 tracking-wider"
    >
        {{ $labels }}
    </div>

    <template x-for="data in indexData" :key="data.userId">
        <div class="grid grid-cols-1 grid-cols-[repeat(auto-fill,_8rem)] gap-x-4
            py-2 border-b border-gray-300 relative hover:bg-blue-100/40 transition-colors group"
        >
            {{ $show_link }}

            {{ $large_values }}
        </div>
    </template>
</div>

<div class="sm:hidden flex flex-col py-4">
    <template x-for="data in indexData" :key="data.userId">
        <div class="grid grid-cols-1 py-2 border-b border-gray-300 relative transition-colors group">
            {{ $show_link }}

            {{ $small_values }}
        </div>
    </template>
</div>

<x-ui.pagenation />
