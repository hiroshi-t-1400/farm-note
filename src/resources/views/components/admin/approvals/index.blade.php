{{-- src/resources/views/components/admin/approvals/index.blade.php --}}

@props([
    'changeRequests'
])

<div
    x-data="indexApprovals({
        initialModels: @js($changeRequests)
    })"
    x-cloak
>

    <div class="hidden sm:grid sm:grid-cols-[10rem_10rem_8rem_auto_1fr] gap-x-4 items-center pb-2 mb-2 border-b-2 border-gray-200 text-xs font-bold text-gray-500 tracking-wider">
        <div>対象者氏名</div>
        <div>申請者名</div>
        <div>申請日</div>
        <div>ステータス</div>
        <div>却下理由</div>
    </div>

    <template x-for="data in indexData" :key="data.id">
        <div class="grid grid-cols-1 sm:grid-cols-[10rem_10rem_8rem_auto_1fr] gap-x-4 gap-y-2 items-center py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">

            <a :href="data.showUrl"
                class="absolute inset-0 z-10"
                :aria-label="`${data.username}さんの申請詳細を確認する`"
            ></a>

            <span class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="data.username"></span>
            <span x-text="data.requesterName" class="min-w-0 truncate text-gray-700"></span>
            <span x-text="data.createdAt" class="min-w-0 text-gray-500 text-sm"></span>
            <div class="flex items-center">
                <template x-if="data.rejectionReason">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 whitespace-nowrap">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        再申請
                    </span>
                </template>
            </div>
            <span class="min-w-0 truncate text-gray-500 text-sm"
                x-text="data.rejectionReason"
                :title="data.rejectionReason">
            </span>
        </div>

    </template>

</div>
