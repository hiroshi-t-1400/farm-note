{{-- resources/views/components/layout.blade.php --}}
@props(['title' => '農作業日誌アプリ'])

<x-layouts.app>
    <x-slot:content>
        <!-- 共通ナビゲーションバー -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- アプリロゴ・タイトル -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-green-700 hover:text-green-800">
                        🌱 農作業日誌
                    </a>

                    <!-- ナビゲーションリンク -->
                    <nav class="hidden md:flex space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            ダッシュボード
                        </a>
                        <a href="{{ route('create') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('create') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            作業登録
                        </a>
                        <a href="{{ route('work-logs.indexSimpleAll') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ (request()->is('work-logs/index*') || request()->is('work-logs/show*')) ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            日誌閲覧
                        </a>

                        <!-- 今後作成する作付別閲覧ページ用 -->
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100">
                            作付別日誌 (作成予定)
                        </a>
                    </nav>
                </div>

                <!-- 右側（クイックアクション等） -->
                <div>
                    <a href="{{ route('create') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded shadow">
                        + 新規登録
                    </a>
                </div>
            </div>
        </header>

        <!-- ページコンテンツ領域 -->
        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- ページ見出し（スロット指定があれば表示） -->
            @if (isset($header))
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $header }}</h1>
                </div>
            @endif

            <!-- メインコンテンツ -->
            {{ $slot }}
        </main>

        <!-- 共通フッター -->
        <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} 農作業管理システム
            </div>
        </footer>
    </x-slot>
</x-layouts.app>
