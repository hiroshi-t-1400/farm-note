@props([
    'workLog' => ''
    ])

<div x-data="indexSingle({
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

    <div class="bg-white rounded-md py-5 px-2 divide-y divide-gray-200 ">

        <template x-for="row in workLog" :key="row.id">
            <article class="grid grid-cols-1 py-3 px-3 text-sm text-gray-800 font-semibold">
                <span x-text="row.createdByName"></span>
                <div class="flex flex-wrap gap-x-4 gap-y-1">
                    <span x-text="`作成: ${row.createdAt}`"></span>
                    <span x-show="row.updatedBy" x-text="`更新: ${row.updatedAt}`"></span>
                </div>
                <a :href="row.url" x-text="row.title" class="py-2 text-lg font-semibold"></a>
            </article>

        </template>
    </div>
</div>
