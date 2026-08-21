{{-- resources/views/components/layout.blade.php --}}
@props(['title' => '農作業日誌アプリ'])

<x-layouts.app>
    <x-slot:content>
        <!-- 共通ナビゲーションバー -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

                {{-- 管理用サイドバー引き出しハンバーガーボタン --}}
                @can('admin-menu.show')

                    <div x-data="{ sideOpen: false }">

                        <button
                            type="button"
                            @click="sideOpen = !sideOpen"
                            class="dads-hamburger-menu-button">
                            <svg class="dads-hamburger-menu-button__icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 18V16H21V18H3ZM3 13V11H21V13H3ZM3 8V6H21V8H3Z" fill="currentcolor"/>
                            </svg>
                            メニュー
                        </button>

                        <div
                            x-show="sideOpen"
                            x-transition
                            @click.outside="sideOpen = false"
                            class="fixed left-0 top-0 bottom-0 w-[240px] z-100"
                        >
                            {{-- <x-layouts.admin-sidebar activeMenu=""> --}}
                                <x-layouts.admin-sidebar />
                        </div>
                    </div>
                @endcan


                <!-- アプリロゴ・タイトル -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-green-700 hover:text-green-800">
                        🌱 農作業日誌
                    </a>

                    <!-- ユーザー表示 -->
                    <div x-data class="grid grid-cols-1 text-gray-700 font-semibold">
                        <span class="text-sm">ログイン中</span>
                        <template x-if="!$store.auth.loading" >
                            <a class="px-3 rounded-md text-sm font-medium ">
                                <span x-text="$store.auth.user.name"
                                class="text-base text-gray-700 font-semibold"
                                ></span>
                            </a>
                        </template>
                    </div>

                    <!-- ナビゲーションリンク -->
                    <nav class="hidden md:flex space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            ダッシュボード
                        </a>
                        {{-- <a href="{{ route('create') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('create') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            作業登録
                        </a> --}}
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
                    <button
                        x-data="tryAuthForm()"
                        @click="submitLogout()"
                        class="border-1 border-red-500 bg-red-200 hover:bg-red-500 text-gray-700 hover:text-white text-sm font-bold py-2 px-4 rounded shadow">
                        ログアウト
                    </button>
                </div>

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
