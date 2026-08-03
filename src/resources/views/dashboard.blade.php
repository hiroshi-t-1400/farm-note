<x-layouts.layout title="ダッシュボード - 農作業日誌">
    <x-slot:header>
        ダッシュボード
    </x-slot:header>

    <!-- 開発時用のクイックナビゲーションカード -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ダッシュボード半面コンテナ１ -左 --}}
        <!-- 作業登録へ -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 mb-2">📝 作業の登録</h2>
            <p class="text-sm text-gray-600 mb-4">本日の作業内容や使用資材を記録します。</p>
            <a href="{{ route('create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                作業登録画面を開く &rarr;
            </a>
        </div>

        {{-- ダッシュボード半面コンテナ２ -右 --}}
        <div>


            <x-dashboard.crop-seasons :crop_seasons="$crop_seasons" />

            <x-dashboard.recent-logs :recent="$latest_work_logs" :workLogs="$latest_work_logs" />


        </div>
    </div>
</x-layouts.layout>
