{{-- src/resources/views/dashboard.blade.php --}}

<x-layouts.layout title="ダッシュボード - 農作業日誌">
    <x-slot:header>
        ダッシュボード
    </x-slot:header>

    <!-- 開発時用のクイックナビゲーションカード -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ダッシュボード半面コンテナ１ -左 --}}
        <!-- 作業登録へ -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <x-work-logs.application.create />

        </div>

        {{-- ダッシュボード半面コンテナ２ -右 --}}
        <div>


            <x-dashboard.crop-seasons :crop_seasons="$crop_seasons" />

            <x-dashboard.recent-logs :recent="$latest_work_logs" :workLogs="$latest_work_logs" />


        </div>
    </div>
</x-layouts.layout>
