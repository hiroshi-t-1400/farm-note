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

        <div class="flex flex-col ">

            <div class="hidden sm:grid sm:grid-cols-[10rem_10rem_8rem_4rem] gap-x-4 items-center pb-2 mb-2 border-b-2 border-gray-200 text-xs font-bold text-gray-500 tracking-wider">
                <div>氏名</div>
                <div>管理権限</div>
                <div>登録・承認日</div>
                <div>ステータス</div>
            </div>

            <template x-for="data in indexData" :key="data.userId">
                <div class="grid grid-cols-1 sm:grid-cols-[10rem_10rem_8rem_4rem] gap-x-4 gap-y-2 items-center py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">
                    <a :href="data.showUrl"
                        class="absolute inset-0 z-10"
                        :aria-label="`${data.username}さんの申請詳細を確認する`"
                    ></a>

                    <span x-text="data.username" class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors"></span>
                    <span x-text="data.roleLabel" class="min-w-0 truncate font-semibold text-gray-700"></span>
                    <span x-text="data.createdAt" class="min-w-0 text-gray-500 text-sm"></span>
                    <span x-text="data.statusLabel" ></span>
                </div>
            </template>
        </div>

        <x-ui.pagenation />

    </div>

</x-layouts.layout>
