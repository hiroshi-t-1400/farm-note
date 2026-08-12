{{-- resources/views/components/work-logs/show-section.blade.php --}}

@props([
    'workLog' => '',
    ])

<div
    x-data="showSingleLog({
        'initialWorkLog': @js($workLog)
        })">

    <div class="main grid grid-cols-1">
        {{-- header --}}
        <div class="header">
            <p x-text="createdBy.name" class="text-xs text-gray-800 font-semibold"></p>

            <x-work-logs.details class="text-xs w-fit">
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

            <p x-text="`${cropSeason?.cropSeasonsNameYear}`"
                class="text-xs font-semibold text-gray-800"></p>
            <x-work-logs.details class="text-base w-fit">
                <x-slot:summary class="flex-wrap gap-x-2">
                    <div x-text="`品種名: ${cropSeason.variety}`"
                        class="text-base font-semibold"></div>
                    <div x-text="`圃場: ${cropSeason.fieldName}`"
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

        {{-- 作業詳細 --}}
        <dl class="divide-y divide-gray-200">
            <x-ui.description-item label="作業日">
                <span x-text="workDate"></span>
                <span x-text="status" class="text-red-400"></span>
            </x-ui.description-item>

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
                    {{-- 資材１件分のコンテナ --}}
                    <div class="grid grid-cols-1">
                        <dt class="text-base font-medium text-gray-600">
                            <div x-text="mat.indexStr" class="text-base font-semibold"></div>
                        </dt>
                        <dd class="mt-1 text-base text-gray-800 sm:mt-0 sm:col-span-2 border-l-4 border-gray-200 ">
                            <div class="flex flex-wrap gap-x-1 text-base font-medium text-gray-700">
                                <div x-text="mat.typeLabel" class="px-1"></div>
                                <div x-text="mat.name"></div>
                            </div>
                            <div class="grid sm:grid-cols-[auto_1fr] grid-cols-1 gap-y-1 gap-x-5 px-2">

                                <x-ui.description-row label="使用量">
                                    <span x-text="mat.quantity" ></span>
                                </x-ui.description-row>

                                <x-ui.description-row x-show="mat.dilutionRate" label="希釈倍率">
                                    <span x-text="mat.dilutionRate" ></span>
                                </x-ui.description-row>

                                <x-ui.description-row x-show="mat.dilutionRate" label="原液量">
                                    <span x-text="mat.materialAmount" ></span>
                                </x-ui.description-row>
                            </div>

                            {{-- 資材詳細の表示の仕方を検討する。農薬などは情報が多く抜粋では不十分ではないか、資材マスター表示などを検討すべきか --}}
                            {{-- <div>
                                <x-work-logs.details>
                                    <x-slot:summary>
                                        資材詳細
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
                            </div> --}}

                        </dd>
                    </div>
                </template>
            </x-ui.description-item>

            <x-ui.description-item label="作業実施者">
                <template x-for="u in performedBy" :key="index">
                    <div class="flex flex-wrap gap-y-2 gap-x-10 justify-items-start place-content-start" >
                        <span x-text="u.name"></span>
                    </div>
                </template>
            </x-ui.description-item>
        </dl>

        {{-- 下部アクションボタン --}}
        <x-work-logs.action-buttons >

            <x-ui.button
                type="href"
                ::href="editUrl"
                variant="alert-ghost"
            >
                編集する
            </x-ui.button>

            <x-work-logs.window-del-popover confirmEvent="deleteLog()" >
                <x-ui.button
                    type="button"
                    variant="danger"
                    class="w-full">
                    削除
                </x-ui.button>
            </x-work-logs.window-del-popover>

            <x-ui.button
                type="href"
                ::href="backUrl"
                variant="secondary-ghost">
                作付け一覧へ戻る
            </x-ui.button>
            {{-- スマホでモーダル表示を実装したときのUI --}}
            {{-- <x-ui.button type="button" variant="secondary-ghost">
                ｘ 閉じる
            </x-ui.button> --}}
        </x-work-logs.action-buttons>

    </div>
</div>

