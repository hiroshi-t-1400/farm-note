{{-- /var/www/src/resources/views/admin/users/index.blade.php --}}

<x-layouts.layout title="承認一覧 - 農作業日誌">
    <x-slot:header>
        送信した承認の一覧
    </x-slot>

    <div x-data="indexUserChangeRequest({
        'initialModels': @js($changeRequests)
        })"
        x-cloak
    >


        <div>
            <template x-if="hasRejected()">
                <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-r-lg shadow-sm my-4 text-base font-bold text-amber-800">
                    却下された申請を処理してください。
                </div>
            </template>
        </div>

        <div class="flex flex-col ">

            <div class="hidden sm:grid sm:grid-cols-[10rem_10rem_8rem_minmax(3rem,_auto)_minmax(3rem,_1fr)] gap-x-4 items-center pb-2 mb-2 border-b-2 border-gray-200 text-xs font-bold text-gray-500 tracking-wider">
                <div>対象者氏名</div>
                <div>申請者名</div>
                <div>申請日</div>
                <div>ステータス</div>
                <div>却下理由</div>
            </div>

            <template x-for="data in indexData" :key="data.id">
                <div class="w-fit">
                    <template x-if="$store.auth.can('update', data)">
                        <div class="grid grid-cols-1 sm:grid-cols-[10rem_10rem_8rem_minmax(3rem,_auto)_minmax(0,_auto)] gap-x-4 gap-y-2 items-center py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">
                            <a :href="data.showUrl"
                                class="absolute inset-0 z-10"
                                :aria-label="`${data.username}さんの申請詳細を確認する`"
                            ></a>

                            <span class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="data.username"></span>
                            <span x-text="data.requesterName" class="min-w-0 truncate font-semibold text-gray-700"></span>
                            <span x-text="data.createdAt" class="min-w-0 text-gray-500 text-sm"></span>
                            <span
                                x-text="data.statusLabel"
                                :class="`min-w-0 ${data.statusCss}`"
                            ></span>
                            <span class="min-w-0 truncate text-gray-500 text-sm"
                                x-text="data.rejectionReason"
                                :title="data.rejectionReason">
                            </span>
                        </div>
                    </template>

                    <template x-if="!$store.auth.can('update', data)">
                        <div class="grid grid-cols-1 sm:grid-cols-[10rem_10rem_8rem_minmax(3rem,_auto)_minmax(0,_auto)] gap-x-4 gap-y-2 items-center py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">

                            <span class="min-w-0 truncate font-semibold text-gray-800 " x-text="data.username"></span>
                            <span x-text="data.requesterName" class="min-w-0 truncate text-gray-700"></span>
                            <span x-text="data.createdAt" class="min-w-0 text-gray-500 text-sm"></span>
                            <span
                                x-text="data.statusLabel"
                                :class="`min-w-0 ${data.statusCss}`"
                            ></span>
                            <span class="min-w-0 truncate text-gray-500 text-sm"
                                x-text="data.rejectionReason"
                                :title="data.rejectionReason">
                            </span>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <x-ui.pagenation />

    </div>

</x-layouts.layout>
