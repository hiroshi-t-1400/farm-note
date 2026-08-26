{{-- resources/views/components/layouts/admin-sidebar.blade.php --}}
@props([
    // 必要に応じて親からアクティブなメニュー名などを受け取る場合はここに定義します
    'activeMenu' => '',
    'requestCount' => '',
])

                @can('admin-menu.show')

<div class="rounded-md border border-slate-700">

            <div class="flex items-center px-2 py-3 mb-6 bg-slate-800 rounded-t-sm border border-slate-700">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <div class="ml-3">
                    <h2 class="text-sm font-bold text-slate-100 tracking-wider">管理システム</h2>
                    <p class="text-2xs text-slate-400">
                        {{-- <x-admin.users.role-badge ::role="$store.auth.user.roles" /> --}}
                    </p>
                </div>
            </div>

            <!-- メニューリスト -->
            <nav class="flex-1 space-y-4">

                <!-- 2. 【owner専用】承認待ち一覧 -->
                @can('user-change.approve')
                    <div class="space-y-1">
                        <p class="px-2 text-sm font-medium text-gray-600 uppercase tracking-wider">オーナー専用</p>
                        <a href="{{ route('admin.approvals.users.index') }}"
                            class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'approvals' ? 'bg-slate-800 text-amber-500' : '' }}"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>承認待ち一覧</span>
                            </div>
                            {{-- 未承認件数バッジ --}}
                            <x-admin.approvals.approval-badge />
                        </a>
                    </div>
                @endcan

                <!-- 3. ユーザー情報（ユーザー一覧、新規登録申請） -->
                <div class="space-y-1" x-data="{ open: @js($activeMenu === 'users' || $activeMenu === 'user-registration') }">
                    <p class="px-2 text-sm font-medium text-gray-600 uppercase tracking-wider">ユーザー管理</p>

                    <!-- アコーディオンのトリガーボタン (Alpine.js) -->
                    <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>ユーザー情報</span>
                        </div>
                        <!-- 回転するカスタムインジケーター -->
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- サブメニュー -->
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="#" {{-- {{   route('admin.users.index')  }} --}}
                        class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'users' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}">
                            ユーザー一覧
                        </a>

                        <!-- 【manager専用】ユーザー新規登録申請画面 -->
                        @can('user-change.request')
                            <a href="{{ route('admin.requests.users.create') }}"
                                class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'user-registration' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}"
                            >
                                新規登録申請
                            </a>
                            <a href="{{ route('admin.requests.users.index') }}"
                                class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'user-registration' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}"
                            >
                                申請一覧
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- 4. マスター管理（農作業日誌用各種マスター） -->
                <div class="space-y-1" x-data="{ open: @js(in_array($activeMenu, ['crops', 'crops-seasons', 'fields', 'materials'])) }">
                    <p class="px-2 text-sm font-medium text-gray-600 uppercase tracking-wider">マスターデータ管理</p>

                    <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span>マスター管理</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- 各種マスタテーブルCRUDサブメニュー -->
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="#" {{-- {{ route('admin.masters.crops-seasons.index') }} --}}
                        class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'crops-seasons' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}">
                            作付けマスター
                        </a>
                        <a href="#" {{-- {{ route('admin.masters.crops.index') }} --}}
                        class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'crops' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}">
                            作物マスター
                        </a>
                        <a href="#" {{-- {{ route('admin.masters.fields.index') }} --}}
                        class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'fields' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}">
                            圃場マスター
                        </a>
                        <a href="#" {{-- {{ route('admin.masters.materials.index') }} --}}
                        class="block px-3 py-1.5 text-xs rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'materials' ? 'text-amber-500 font-semibold' : 'text-slate-400' }}">
                            資材マスター
                        </a>
                    </div>
                </div>

                <!-- 5. その他アプリ全体の設定 -->
                <div class="space-y-1">
                    <p class="px-2 text-sm font-medium text-gray-600 uppercase tracking-wider">システム</p>
                    <a href="#" {{-- {{ route('admin.settings.index') }} --}}
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors hover:bg-slate-800 hover:text-slate-100 {{ $activeMenu === 'settings' ? 'bg-slate-800 text-amber-500' : '' }}">
                        <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>全体設定</span>
                    </a>
                </div>

            </nav>
</div>

@endcan

            <!-- footer -->
            <div class="sm:hidden flex flex-col items-center gap-y-4">
                <div>
                    <button
                        x-data="tryAuthForm()"
                        @click="submitLogout()"
                        class="border-1 border-red-500 bg-red-200 hover:bg-red-500 text-gray-700 hover:text-white text-sm font-bold py-2 px-4 rounded shadow">
                        ログアウト
                    </button>
                </div>
            </div>
