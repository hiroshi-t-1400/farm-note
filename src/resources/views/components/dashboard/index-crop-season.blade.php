@props([
    'crop_seasons' => '',
])

<div x-data="indexLog({
        initialCropSeasons: @js($crop_seasons)
    })"
    class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 opacity-75"
>

    <x-dashboard.right-hand>

        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-800">作物の一覧</h3>
        </x-slot>

        <x-slot:alertTemplateTag>
            {{-- 作付けマスターのレコードが無い場合 --}}
            <template x-if="allCropSeasons.length === 0">
        </x-slot>

            <x-slot:alertMessage>
                作付け作物の登録がありません
            </x-slot>

            <x-slot:alertGuide>
                作付けマスターに栽培中の作物を登録してください。
            </x-slot>

            <x-slot:alertButton>
                <x-ui.button variant="alert" href="/master/crop-seasons/create" >
                    作付マスターを作成する
                </x-ui.button>
            </x-slot>
        {{-- 上部のtemplate閉じタグはコンポーネントの中に --}}

        {{-- 下部template閉じタグとペア --}}
        <x-slot:TemplateTag>
            <!-- 2. データが存在する場合のダッシュボード表示 -->
            <template x-if="allCropSeasons.length > 0">
        </x-slot>

            <x-slot:content>
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    <template x-for="crop in allCropSeasons" :key="crop.id">
                        {{-- <div class="py-1 flex items-center justify-between"> --}}
                        <div class="py-1 grid grid-cols-3">
                            {{-- 作物の名称、関連する情報 --}}
                            <div>
                                <a href="" x-text="`${crop.crop_name} ${crop.year}`"  class="text-base font-medium text-gray-900 hover:underline" ></a>
                            </div>
                            <div class="px-1 py-0.5 rounded-xs font-medium bg-gray-100 text-xs text-gray-800">
                                品種名：<span x-text="crop.variety" ></span>
                            </div>
                            <div class="px-1 py-0.5 rounded-xs font-medium bg-gray-100 text-xs text-gray-800">
                                圃場：<span x-text="crop.fields.name" ></span>
                            </div>

                        </div>
                    </template>
                    <x-ui.button variant="primary" href="" class="mt-3 justify-self-end">
                        作付けマスターを追加する
                    </x-ui.button>
                </div>
        {{-- コンポーネント呼び出しタグのペア --}}
        </template>
        </x-slot>

    </x-dashboard.right-hand>
</div>
