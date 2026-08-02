@props([
    'recent' => [], // コレクションまたは配列を受け取る
])

<div x-data="indexLog({
        initialRecent: @js($recent)
    })"
    class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 opacity-75"
>

    <x-dashboard.right-hand>

        <x-slot:header>
            <h3 class="text-lg font-semibold text-gray-800">最近の新規日誌</h3>
            {{-- 必要に応じて一覧へのリンクなど --}}
            <a href="" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                すべて見る &rarr;
            </a>
        </x-slot>

        <x-slot:alertTemplateTag>
            {{-- 直近５件にレコードが無い場合 --}}
            <template x-if="recent.length === 0">
        </x-slot>


            <x-slot:alertMessage>
                最近記録された日誌はありません。
            </x-slot>

            <x-slot:alertGuide>

            </x-slot>

            <x-slot:alertButton>
                <x-ui.button variant="alert" href="/master/crop-seasons/create" >
                    作業登録画面を開く
                </x-ui.button>
            </x-slot>

        {{-- コンポーネントにtemplate閉じタグ --}}

        <x-slot:TemplateTag>
            <!-- 2. データが存在する場合のダッシュボード表示 -->
            <template x-if="allRecent.length > 0">
        </x-slot>

            <x-slot:content>
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    <template x-for="log in allRecent" :key="log.id">
                        <div class="py-1 grid grid-cols-3">
                            <div>
                                {{-- 日付やタイトル、関連する情報 --}}
                                <p x-text="tsToDate(log.created_at)" class="text-xs text-gray-500"></p>
                                <a
                                    href=""
                                    x-text="log.title ?? '無題の日誌'"
                                    class="text-sm font-medium text-gray-900 hover:underline"
                                ></a>
                            </div>
                            <div class="self-end px-1 py-0.5 rounded-xs font-medium bg-gray-100 text-xs text-gray-800">
                                {{-- ステータスやユーザー名など --}}
                                作業実施：<span x-text="log.performed_by?.[0]?.name ?? '作業者不明'">
                                </span>
                            </div>
                            <div class="self-end px-1 py-0.5 rounded-xs font-medium bg-gray-100 text-xs text-gray-800">
                                {{-- ステータスやユーザー名など --}}
                                記録：<span x-text="log.created_by?.name ?? '記録者不明'">
                                </span>
                            </div>

                        </div>

                    </template>
                </div>
        </template>
        </x-slot>

    </x-dashboard.right-hand>
</div>
