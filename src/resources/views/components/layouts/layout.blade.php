{{-- resources/views/components/layout.blade.php --}}

@props(['title' => '農作業日誌アプリ'])

<x-layouts.app>
    <x-slot:content>

        <div x-data x-cloak>
            <template x-if="$store.auth.loading">
                <div>読み込み中...</div>
            </template>

            <template x-if="!$store.auth.loading">

                <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">

                    <!-- ヘッダー（上部ナビゲーションバー） -->
                    <!-- z-50 -->
                    <header class="bg-white border-b border-gray-200 sticky top-0 h-16 z-50">
                        <div class="px-4 h-full sm:w-fit flex gap-x-4 items-center justify-between">
                        {{-- <div class="grid sm:grid-cols-[20rem_20rem] px-4 h-full items-center"> --}}
                            <div class="flex items-center space-x-4">

                                <!-- ハンバーガーメニューボタン（常に同じ位置で機能する） -->
                                <button
                                    @click="sidebarOpen = !sidebarOpen"
                                    class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 relative z-50"
                                    aria-label="メニューを開閉する"
                                >

                                    <!-- 三本線（サイドバーが閉じている時に表示） -->
                                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>

                                    <!-- Xマーク（サイドバーが開いている時に表示） -->
                                    <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <!-- アプリロゴ・タイトル -->
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-green-700 hover:text-green-800">
                                    🌱 農作業日誌
                                </a>
                            </div>

                            <!-- ユーザー表示 -->
                            <div class="flex flex-col p-0.5 px-2 rounded text-gray-700 font-semibold hover:bg-gray-100 hover:shadow relative">
                                <span class="text-sm">ログイン中</span>
                                <a href="{{ route('users.show', Auth::user()) }}"
                                    class="absolute inset-0"
                                ></a>
                                <span x-text="$store.auth.user.name"
                                    class="px-3 text-base text-gray-700 font-semibold"
                                ></span>
                            </div>

                            <!-- 右側（クイックアクション等） -->
                            <div class="hidden sm:flex items-center gap-x-10">
                                <div>
                                    <a href="{{ route('create') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded shadow">
                                        + 日誌登録
                                    </a>
                                </div>

                                <div>
                                    <button
                                        x-data="tryAuthForm()"
                                        @click="submitLogout()"
                                        class="border-1 border-red-500 bg-red-200 hover:bg-red-500 text-gray-700 hover:text-white text-sm font-bold py-2 px-4 rounded shadow">
                                        ログアウト
                                    </button>
                                </div>
                            </div>

                        </div>
                    </header>

                    <!-- スライドインするサイドバー -->
                    <!-- z-40 -->
                    <aside>
                        <div x-show="sidebarOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="-translate-x-full"
                            x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="-translate-x-full"
                            x-cloak
                            @click.outside="sidebarOpen = false"
                            class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-40 pt-16 flex flex-col"
                            >

                            <!-- 💡 pt-16 (上部パディング) を入れることで、ヘッダーの高さ(h-16)を避けて、コンテンツを綺麗に表示させます -->
                            <nav class="flex-grow p-4 space-y-2 overflow-y-auto flex flex-col">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md bg-green-50 text-green-800 font-semibold text-sm">
                                    ダッシュボード
                                </a>
                                <a href="{{ route('work-logs.indexSimple') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ (request()->is('work-logs/index*') || request()->is('work-logs/show*')) ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                                    日誌閲覧
                                </a>
                                <a href="{{ route('create') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('create') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                                    作業登録
                                </a>

                                {{-- 管理情報 --}}
                                @can('admin-menu.show')
                                    {{-- 管理者・オーナー用メニュー --}}
                                    <x-layouts.admin-sidebar />
                                @else
                                    {{-- 一般ユーザー用 自分のアカウント情報へのリンク --}}
                                    <hr>
                                    <a href="{{ route('users.show', Auth::user()) }}"
                                        class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.show') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                                        あなたの登録情報
                                    </a>
                                @endcan

                            </nav>
                        </div>
                    </aside>
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
                </div>
            </template>
        </div>

    </x-slot>
</x-layouts.app>
