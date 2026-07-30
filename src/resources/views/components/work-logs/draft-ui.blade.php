

<div x-show="hasDraft" class="mb-2">
    <label  for="draft_select" class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2">
        <p>保存されていない下書きがあります。</p>
        <p x-show="!isOnline">ネットワークがある場所で送信と保存を完了させてください。</p>

    </label>
    <select x-model="selectedDraftUuid"
            name="draft_select"
            class="w-full border border-gray-300 rounded-md bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">-- 選択して下書きを確認してください。（<span x-text="draftWorkLog.length"></span>件） --</option>
        <template x-for="(draft, index) in draftWorkLog" :key="draft.draft_uuid">
            <option :value="draft.draft_uuid" x-text="`作業日: ${draft.formData.work_date} | 作物名: ${draft.formData.crop_name || '未選択'} | 作業名: ${draft.formData.title || '未記入'}`"></option>
        </template>
    </select>
    <button
        type="button"
        @click="fillWithDraft()"
        class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-blue-300 items-center text-sm font-medium text-white"
        >
        下書きを読み込む
    </button>
    <button
        type="button"
        @click="deleteSelectedDraft()"
        :disabled="!selectedDraftUuid"
        :class="{ 'opacity-50 cursor-not-allowed': !selectedDraftUuid }"
        class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-red-300 items-center text-sm font-medium text-white"
        >
        選択した下書きを削除する
    </button>
</div>
