{{-- resources/views/dashboard.blade.php --}}
<x-layouts.layout title="ダッシュボード - 農作業日誌">
    <x-slot:header>
        ダッシュボード
    </x-slot:header>

    <!-- 開発時用のクイックナビゲーションカード -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- 作業登録へ -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 mb-2">📝 作業の登録</h2>
            <p class="text-sm text-gray-600 mb-4">本日の作業内容や使用資材を記録します。</p>
            <a href="{{ route('create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                作業登録画面を開く &rarr;
            </a>
        </div>

        <!-- 作付別閲覧へ（今後の開発対象） -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 opacity-75">
            <h2 class="text-lg font-bold text-gray-800 mb-2">📊 作付別日誌閲覧（開発予定）</h2>
            <p class="text-sm text-gray-600 mb-4">作物ごとの作業履歴や年次ガントチャートを確認します。</p>
            <button disabled class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded text-sm cursor-not-allowed">
                順次開発中...
            </button>
        </div>

    </div>
</x-layouts.layout>
