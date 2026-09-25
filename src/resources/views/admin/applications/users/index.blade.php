{{-- /var/www/src/resources/views/admin/users/index.blade.php --}}

<x-layouts.layout title="承認一覧 - 農作業日誌">
    <x-slot:header>
        送信した承認の一覧
    </x-slot>

    <div x-data="indexUserChangeApplication({
        'initialModels': @js($changeApplications)
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

            <div class="hidden sm:grid sm:grid-cols-[10rem_5rem_10rem_8rem_minmax(6rem,_auto)_minmax(3rem,_1fr)] gap-x-4 items-center pb-2 mb-2 border-b-2 border-gray-200 text-xs font-bold text-gray-500 tracking-wider">
                <div>対象者氏名</div>
                <div>申請種別</div>
                <div>申請者名</div>
                <div>申請日</div>
                <div>ステータス</div>
                <div>却下理由</div>
            </div>

            <template x-for="data in indexData" :key="data.id">
                <div class="w-fit">
                    <div class="grid grid-cols-1 sm:grid-cols-[10rem_5rem_10rem_8rem_minmax(6rem,_auto)_minmax(0,_auto)] gap-x-4 gap-y-2 items-center py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">
                        {{-- 対象の申請内容に閲覧・編集の認可がある表示 --}}
                        <a
                            x-show="$store.auth.can('update', data)"
                            :href="data.showUrl"
                            class="absolute inset-0 z-10"
                            :aria-label="`${data.username}さんの申請詳細を確認する`"
                        ></a>
                        <span
                            x-show="$store.auth.can('update', data)"
                            class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="data.username">
                        </span>
                        {{-- 申請種別 *CSSは認可によらない --}}
                        <span
                            x-show="$store.auth.can('update', data)"
                            class="min-w-0 truncate font-semibold text-gray-800" x-text="data.actionLabel">
                        </span>

                        <span
                            x-show="$store.auth.can('update', data)"
                            x-text="data.requesterName" class="min-w-0 truncate font-semibold text-gray-700">
                        </span>

                        {{-- 対象の申請内容に閲覧・編集の認可がない表示 --}}
                        <span
                            x-show="!$store.auth.can('update', data)"
                            class="min-w-0 truncate font-semibold text-gray-800 " x-text="data.username">
                        </span>
                        {{-- 申請種別 *CSSは認可によらない --}}
                        <span
                            x-show="!$store.auth.can('update', data)"
                            class="min-w-0 truncate font-semibold text-gray-800" x-text="data.actionLabel">
                        </span>
                        <span
                            x-show="!$store.auth.can('update', data)"
                            x-text="data.requesterName" class="min-w-0 truncate text-gray-700">
                        </span>

                        {{-- 以下、認可共通 --}}
                        <span x-text="data.createdAt" class="min-w-0 text-gray-500 text-sm"></span>
                        <div>
                            <span
                                x-text="data.statusLabel"
                                :class="`min-w-0 ${data.statusCss}`"
                            ></span>
                            <span
                                x-text="data.reviewStatus"
                                :class="`min-w-0 text-xs ${data.reviewCss}`"
                            ></span>
                        </div>
                        <span class="min-w-0 truncate text-gray-500 text-sm"
                            x-text="data.rejectionReason"
                            :title="data.rejectionReason">
                        </span>
                    </div>
                </div>
            </template>
        </div>

        <x-ui.pagenation />

    </div>

</x-layouts.layout>
