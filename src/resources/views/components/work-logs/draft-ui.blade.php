<div x-show="hasDraft" class="grid grid-cols-1 mb-2 max-w-lg">
        <p>保存されていない下書きがあります。</p>
        <p x-show="!isOnline">ネットワークがある場所で送信と保存を完了させてください。</p>

    <div>
        <x-ui.select
            x-model="selectedDraftUuid"
            @change="showDraftInfo()"
            name="draft_select"
            >
            <x-slot>
                <option value="">-- 選択して下書きを確認してください。（<span x-text="draftWorkLog.length"></span>件） --</option>
                <template x-for="(draft, index) in draftWorkLog" :key="draft.draftUuid">

                    <option :value="draft.draftUuid" x-text="`作業日: ${draft.workDate}`"></option>
                </template>
            </x-slot>
        </x-ui.select>
        <div x-show="showDraftInfo().draft" class="p-2 bg-gray-200 ">
            <span x-text="showDraftInfo().cropName"></span>
            <span x-text="showDraftInfo().title"></span>
        </div>
    </div>
</div>

<div>
    <x-ui.button
        variant="primary-ghost"
        type="button"
        @click="fillWithDraft()"
        {{-- class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-blue-300 items-center text-sm font-medium text-white" --}}
        >
        下書きを読み込む
    </x-ui.button>

    <x-ui.button
        variant="alert-ghost"
        type="button"
        @click="deleteSelectedDraft()"
        ::disabled="!selectedDraftUuid"
        >
        選択した下書きを削除する
    </x-ui.button>
</div>
