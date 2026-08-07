{{-- resources/views/components/work-logs/show-section.blade.php --}}

@props([
    'models' => '',
    'work_log' => '',
    ])

<div
    x-data="workLogShow({
        'initialWorkLog': @js($work_log)
        })">


    <div class="main grid grid-cols-1">
        {{-- header --}}
        <div class="header">
            <p x-text="createdBy.name" class="text-xs text-gray-800 font-semibold"></p>

            <x-work-logs.details class="text-xs">
                <x-slot:summary class="gap-x-1">
                        日誌作成日：
                        <span x-text="createdAt" class=""></span>
                        <span x-show="updatedBy" x-text="`更新日 ${updatedAt}`" class=""></span>
                </x-slot>

                    <p class="text-xs text-gray-800 font-semibold">更新履歴</p>
                    <div class="ps-1 text-xs">
                        <span x-show="!updatedBy">更新されていません</span>
                        <div class="">
                            <span x-show="updatedBy" x-text="`${updatedAt} ${updatedBy}`"></span>
                        </div>

                    </div>
            </x-work-logs.details>
        </div>



        {{-- title --}}
        {{-- 作付け名称、品種名、圃場表示されている記事を一意にわかるように --}}
        <div class="title mb-4">
            <h3 x-text="title" class="pb-2 text-lg font-bold text-gray-800"></h3>

            <p x-text="`${cropSeason?.crop_name} ${cropSeason?.year}`"
                class="text-xs font-semibold text-gray-800"></p>
            <x-work-logs.details>
                <x-slot:summary class="gap-x-2">
                        <div x-text="`品種名: ${cropSeason.variety}`"
                        class="text-base font-semibold"></div>
                        <div x-text="`圃場: ${cropSeason.field_name}`"
                        class="text-base font-semibold"></div>
                </x-slot>

                <div>
                    <span x-text="`シーズン: ${cropSeason.year}年`"></span>
                </div>
                <div x-show="cropSeason.notes || ''">
                    <span x-text="cropSeason.notes"></span>
                </div>
            </x-work-logs.details>
        </div>

    <dl class="divide-y divide-gray-200">
            <x-ui.description-item label="作業内容">
                <div x-show="!content">
                    <x-ui.empty-state>
                        <x-slot:alertMessage>
                            作業内容が記入されていないようです。
                        </x-slot>
                    </x-ui.empty-state>
                </div>
                <span x-text="content"></span>
            </x-ui.description-item>

            <x-ui.description-item label="使用資材">
                <div x-show="materials.length == 0">
                    ※資材は使用されていません。
                </div>
                <template x-for="(mat, index) in materials" :key="index">
                    <div class="flex flex-col flex-wrap">
                        <dt class="text-base mb-2 font-semibold text-gray-800">
                            <div x-text="`資材 ${index + 1}`" class="text-sm font-semibold"></div>
                        </dt>
                        <dd class="flex flex-row flex-wrap border-l-4 border-gray-300 pl-3 py-1 mb-2 text-base text-gray-800">
                            <span x-text="`【${mat.type}】`" class="px-1"></span>
                            <span x-text="`${mat.name}`"></span>
                            <x-work-logs.details>
                                <x-slot:summary>
                                    {{-- <div class="grid lg:grid-cols-[auto_1fr] grid-cols-1 gap-x-20"> --}}
                                    <div class="flex flex-row flex-wrap gap-x-5">
                                        <x-ui.description-row label="使用量">
                                            <span x-text="`${mat.quantity}`" />
                                        </x-ui.description-row>

                                        <div x-show="mat.dilutionRate || ''" class="flex flex-wrap gap-5">
                                            <x-ui.description-row label="希釈倍率">
                                                <span x-text="`${mat.dilutionRate}`" />
                                            </x-ui.description-row>
                                            <x-ui.description-row label="原液量">
                                                <span x-text="`${mat.materialAmount}`" />
                                            </x-ui.description-row>
                                        </div>
                                    </div>
                                </x-slot>
                                <div class="flex flex-row flex-wrap gap-x-5">
                                    <x-ui.description-row label="メーカー">
                                        <span x-text="`${mat.manufacturer}`"></span>
                                    </x-ui.description-row>
                                    <x-ui.description-row label="標準使用量">
                                        <span x-text="`${mat.standardSprayVolume}`"></span>
                                    </x-ui.description-row>
                                </div>
                            </x-work-logs.details>

                        </dd>
                    </div>
                </template>
            </x-ui.description-item>

            <x-ui.description-item label="作業実施者">
                <template x-for="u in performedBy" :key="index">
                    {{-- <div class="grid lg:grid-cols-2 lg:gap-x-10 grid-cols-1 gap-y-2 justify-items-start place-content-start" > --}}
                    <div class="flex flex-wrap gap-y-2 gap-x-10 justify-items-start place-content-start" >
                        <span x-text="u.name"></span>
                    </div>
                </template>
            </x-ui.description-item>

        {{-- レスポンシブ適用させるボトムボタンエリアのコンポーネントつくる --}}
        <div class="grid lg:grid-cols-3 grid-cols-1 lg:gap-x-10 gap-y-2 pt-10 mb-4 lg:justity-center place-content-start">

            <x-ui.button type="button" variant="secondary-ghost" class="max-w-3xs">
                作付け一覧へ戻る
            </x-ui.button>
            <x-ui.button type="button" variant="primary-ghost" class="max-w-3xs">
                編集する
            </x-ui.button>
            <x-ui.button type="button" variant="secondary-ghost" class="max-w-3xs">
                ｘ 閉じる
            </x-ui.button>
        </div>


    </div>
</div>

