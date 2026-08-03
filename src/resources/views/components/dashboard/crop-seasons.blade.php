@props([
    'crop_seasons' => '',
])

<div x-data="indexLog({
        initialCropSeasons: @js($crop_seasons)
    })"
    class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 opacity-75"
>
    <x-dashboard.card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-800">作物の一覧</h3>
        </x-slot>

        {{-- 1. データが存在しない場合のダッシュボード表示 --}}
        <div x-show="allCropSeasons.length === 0">

            <x-dashboard.empty-state>
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
            </x-dashboard.empty-state>

        </div>

        {{-- 2. データが存在する場合のダッシュボード表示 --}}
        <div x-show="allCropSeasons.length > 0">

                <template x-for="crop in allCropSeasons" :key="crop.id">
                    <x-dashboard.list>
                        <x-slot:title>
                                <a
                                    href="" x-text="`${crop.crop_name} ${crop.year}`"
                                    class="text-base font-medium text-gray-900 hover:underline" >
                                </a>
                        </x-slot>

                        <x-slot:info>
                            <x-dashboard.list-info>
                                    品種名：<span x-text="crop.variety" ></span>
                            </x-dashboard.list-info>
                            <x-dashboard.list-info>
                                    圃場：<span x-text="crop.fields.name" ></span>
                            </x-dashboard.list-info>
                        </x-slot>
                    </x-dashboard.list>


                </template>
                <x-ui.button variant="primary" href="" class="mt-3 justify-self-end">
                    作付けマスターを追加する
                </x-ui.button>

        </div>
    </x-dashboard.card>
</div>
