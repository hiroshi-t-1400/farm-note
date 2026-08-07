@props([
    'recent' => [],
])

<div x-data="recentLog({
        initialRecent: @js($recent)
    })"
    class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 opacity-75"
>
    <x-dashboard.card>
        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-800">最近の新規日誌</h3>
            {{-- 必要に応じて一覧へのリンクなど --}}
            <a href="" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                すべて見る &rarr;
            </a>
        </x-slot>

            {{-- 直近５件にレコードが無い場合 --}}
        <div x-show="allRecent.length === 0">
            <x-dashboard.empty-state>
                    <x-slot:alertMessage>
                        最近記録された日誌はありません。
                    </x-slot>

                    <x-slot:alertButton>
                        <x-ui.button variant="alert" href="/master/crop-seasons/create" >
                            作業登録画面を開く
                        </x-ui.button>
                    </x-slot>
            </x-dashboard.empty-state>

        </div>

        {{-- 2. データが存在する場合のダッシュボード表示 --}}
        <div x-show="allRecent.length > 0">

            <template x-for="log in allRecent" :key="log.id">
                <x-dashboard.list>
                    {{-- 日付やタイトル --}}
                    <x-slot:title>
                        <p x-text="log.createdAt" class="text-xs text-gray-500"></p>
                        <a
                            :href="`{{ url('/work-logs/show') }}/${log.id}`"
                            x-text="log.title ?? '無題の日誌'"
                            class="text-sm font-medium text-gray-900 hover:underline"
                        ></a>
                    </x-slot>

                    {{-- 関連情報 --}}
                    <x-slot:info>
                        <x-dashboard.list-info>
                            作業実施：<span x-text="log.performedBy[0].name ?? '作業者不明'">
                            </span>
                        </x-dashboard.list-info>
                        <x-dashboard.list-info>
                            記録：<span x-text="log.createdByName ?? '記録者不明'">
                            </span>
                        </x-dashboard.list-info>
                    </x-slot>

                </x-dashboard.list>
            </template>
        </div>

    </x-dashboard.card>
</div>
