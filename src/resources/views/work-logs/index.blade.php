{{-- src/resources/views/work-logs/index.blade.php --}}

<x-layouts.layout title="日誌記録画面 - 農作業日誌">


        <x-slot:header>
            日誌の一覧
        </x-slot>

        <div x-data="{ open: true }">

            <span @click="open ? open=false : open=true" class="flex items-center w-fit py-0.5 px-1 border border-1 border-gray-100 rounded-sm shadow-md">
                作付け一覧
                <svg :class="open ? 'rotate-180' : ''" class="w-[1em] h-[1em] text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>

            {{-- <div x-show="pathName == '/work-logs/index/'"> --}}
            <div x-show="open" x-transition>
                <x-dashboard.crop-seasons :cropSeasons="$models['cropSeasons']" />
            </div>

            <x-work-logs.index-section :workLog="$models['workLog']" />
        </div>

</x-layouts.layout>

