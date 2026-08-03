{{-- デバッグ用のネットワーク状態インジケータ --}}
<div class="border border-md shadow-md border-blue-800 flex flex-col justify-self-end">
    <div class="text-xs">
        デバッグツール
    </div>
    <div class="text-xs">現在のネットワーク：
        <span x-text="showOnlineStatus" class="text-red-800 font-semibold text-sm"></span>
    </div>
    <button type="button" @click="toggleOnline()" class="rounded-md border border-md bg-gray-600 text-white block">切り替え</button>
</div>
