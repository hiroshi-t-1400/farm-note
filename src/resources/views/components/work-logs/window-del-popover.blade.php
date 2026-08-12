@props([
    'confirmEvent' => '',
    ])

{{-- 主に<button>をslotに受けて実装 --}}
<div x-data="{ popoverOpen: false }" class="relative">
    <span @click="popoverOpen = true" class="inline-block">

        {{ $slot }}
    </span>

    <div
        x-show="popoverOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        @click.outside="popoverOpen = false"
        class="absolute -top-full left-50% mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg p-3 z-10"
    >
        <p class="text-xs text-gray-600 mb-2">この記事を削除しますか？</p>

        <div class="flex justify-end gap-2">
            <button
                type="button"
                @click="popoverOpen = false"
                class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded"
            >
                キャンセル
            </button>
            <button
                type="button"
                @click="{{ $confirmEvent }}; popoverOpen = false"
                class = 'px-2 py-1 text-xs bg-red-600 text-white hover:bg-red-700 rounded',>
                削除する
            </button>
        </div>
    </div>
</div>
