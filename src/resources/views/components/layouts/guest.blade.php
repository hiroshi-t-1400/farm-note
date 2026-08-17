{{-- src/resources/views/components/login/index.blade.php --}}

@props([
    'header' => '',
    'models' => '',
    ])

{{-- ゲスト用のナビゲーションバーの無い画面 --}}

<x-layouts.app>
    <x-slot:content>

        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="flex flex-col p-10 rounded-md bg-white w-md h-sm justify-self-center justify-center">
                <div class="mb-6">
                    <h1 class="py-5 text-2xl font-bold text-gray-900">
                        農作業日誌 - Farm Note -
                    </h1>

                    <h2 class="p-2 text-xl font-bold text-gray-900">
                        {{ $header }}
                    </h2>
                </div>

                {{-- 認証フォーム等ゲスト用コンテンツ --}}
                {{ $slot }}

            </div>

        </main>

        <!-- 共通フッター -->
        <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} 農作業管理システム
            </div>
        </footer>

    </x-slot>
</x-layouts.app>

