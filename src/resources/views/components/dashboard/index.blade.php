{{-- src/resources/views/components/dashboard/index.blade.php --}}

@props([
    'crop_seasons' => '',
    'models' => '',
    'latest_work_logs' => ''
    ])

    <!-- 開発時用のクイックナビゲーションカード -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ダッシュボード半面コンテナ１ -左 --}}
        <!-- 作業登録へ -->
        <div class="lg:col-span-7 p-6 rounded-lg shadow-sm bg-white border border-gray-200">
            <x-work-logs.create-section :models="$models" />


        </div>

        {{-- ダッシュボード半面コンテナ２ -右 --}}
        <div class="lg:col-span-5">

            <x-dashboard.crop-seasons :crop_seasons="$crop_seasons" />

            <x-dashboard.recent-logs :recent="$latest_work_logs" :workLogs="$latest_work_logs" />


        </div>
    </div>
