@props([
    'workLog' => '',
    ])

<div x-data="indexSimple({
        'initialWorkLog': @js($workLog)
    })">


    {{-- 記事がない場合のフォールバック --}}
    <div x-show="!caption">
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


    <h2
        x-text="`${caption} の日誌一覧`"
        class="text-lg font-bold text-gray-800 mb-2">
    </h2>

    <div class="flex flex-wrap gap-4 ps-5">
        <a :href="prev" :class="prevClass" >前へ</a>
        <a :href="next" :class="nextClass" >次へ</a>
    </div>

    <div class="bg-white rounded-md py-5 px-2 divide-y divide-gray-200 ">

        <template x-for="row in workLog" :key="row.id">
            <article class="py-3 px-3 text-sm text-gray-800 font-semibold relative">
                <a :href="row.url" class="py-2 text-lg font-semibold absolute inset-1"></a>
                <div class="grid sm:grid-cols-[5rem_minmax(10rem,_0)_1fr] grid-cols-1 gap-x-2 gap-y-1 items-baseline" >
                    <div x-text="`${row.workDate}`"></div>
                    <div x-text="row.title" class="py-0 text-lg font-semibold truncate"></div>
                    {{-- <a :href="row.url" x-text="row.title" class="py-2 text-lg font-semibold absolute inset-0"></a> --}}
                    <div x-text="row.createdByName"></div>
                </div>
            </article>

        </template>
    </div>

    <div class="flex flex-wrap gap-4 ps-5">
        <a :href="prev" :class="prevClass" >前へ</a>
        <a :href="next" :class="nextClass" >次へ</a>
    </div>

</div>
