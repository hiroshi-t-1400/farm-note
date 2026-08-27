{{-- /var/www/src/resources/views/users/index.blade.php --}}

<x-layouts.layout title="ユーザー一覧 - 農作業日誌">
    <x-slot:header>
        登録ユーザーの一覧
    </x-slot>

    <div x-data="indexUser({
        'initialModels': @js($users)
        })"
        x-cloak
    >

        <x-presentation.index-item>

            <x-slot:label>
                <div>氏名</div>
                <div>管理権限</div>
                <div>登録・承認日</div>
                <div>ステータス</div>
            </x-slot>

            <x-slot:show_link>
                <a :href="data.showUrl"
                    class="absolute inset-0 z-10"
                    :aria-label="`${data.username}さんの申請詳細を確認する`"
                ></a>
            </x-slot>

            <x-slot:large_values>
                <span x-text="data.username" class="min-w-0 truncate font-semibold text-gray-700 group-hover:text-blue-600 transition-colors"></span>
                <span x-text="data.roleLabel" class="min-w-0 truncate font-semibold text-gray-700"></span>
                <span x-text="data.createdAt" class="min-w-0 text-gray-700 text-sm"></span>
                <span x-text="data.statusLabel" ></span>
            </x-slot>

            <x-slot:small_values>
                <div class="grid grid-cols-[5rem_1fr]">
                    <span class="text-xs text-gray-500 font-bold tracking-wider">
                        名前：
                    </span>
                    <span x-text="data.username" class="min-w-0 truncate font-semibold text-gray-700 group-hover:text-blue-600 transition-colors"></span>
                </div>

                <div class="grid grid-cols-[5rem_1fr]">
                    <span class="text-xs text-gray-500 font-bold tracking-wider ">
                        管理権限：
                    </span>
                    <span x-text="data.roleLabel" class="min-w-0 truncate font-semibold text-gray-700"></span>
                </div>

                <div class="grid grid-cols-[5rem_1fr]">
                    <span class="text-xs text-gray-500 font-bold tracking-wider">
                        登録日：
                    </span>
                    <span x-text="data.createdAt" class="min-w-0 text-gray-700 text-sm"></span>
                </div>

                <div class="grid grid-cols-[5rem_1fr]">
                    <span class="text-xs text-gray-500 font-bold tracking-wider">
                        ステータス：
                    </span>
                    <span x-text="data.statusLabel" class="min-w-0 text-gray-700"></span>
                </div>
            </x-slot>

        </x-presentation.index-item>

    </div>

</x-layouts.layout>
