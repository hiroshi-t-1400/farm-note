@props([
    'workLog' => null,
    ])

<div x-data="indexSimple({
        'initialWorkLog': @js($workLog)
    })"
    x-cloak
>


    {{-- 記事がない場合のフォールバック --}}
    <div x-show="workLog.length == 0">
        <x-dashboard.empty-state>
            <x-slot:alertMessage>
                <span x-text="`${caption}についての作業日誌がありません`"></span>
            </x-slot>

            <x-slot:alertGuide>
                作業日誌を作成してください
            </x-slot>

            <x-slot:alertButton>
                <x-ui.button variant="alert-ghost" href="/work-logs/create">
                    作業日誌を作成する
                </x-ui.button>
            </x-slot>
        </x-dashboard.empty-state>
    </div>


    <section class="pt-5">

        <h2
            x-show="caption"
            x-text="`${caption}の日誌一覧`"
            class="text-lg font-bold text-gray-800 mb-2">
        </h2>

        <x-ui.pagenation />


        <div class="bg-white rounded-md py-5 px-2 divide-y divide-gray-200 ">
        <template x-for="row in workLog" :key="row.id">
                <article x-show="row?.uuid" class="py-3 px-3 text-sm text-gray-800 font-semibold">

                    {{-- <div class="grid sm:grid-cols-[5rem_minmax(10rem,_0)_1fr] grid-cols-1 gap-x-2 gap-y-1 items-baseline" > --}}
                    {{-- <div class="grid sm:grid-cols-[5rem_10rem_minmax(auto,_5rem)_minmax(auto,_min-content)] grid-cols-1 gap-x-2 items-baseline" > --}}
                    <div class="flex flex-wrap gap-x-2 items-baseline" >
                        <div class="flex flex-wrap gap-x-2 items-baseline">
                            <div x-text="`${row.workDate}`"></div>
                            <a :href="row.url"
                                x-text="row.title"
                                class="p-0.5 text-lg font-semibold">
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-x-2 items-baseline">

                            <div x-text="row.createdByName" class="max-w-fit"></div>
                            <x-ui.del-popover confirmEvent="deleteLog(row.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-[1.5rem] w-[1.5rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </x-ui.del-popover>
                        </div>
                    </div>

                </article>

            </template>
        </div>


        <x-ui.pagenation />

    </section>

</div>
