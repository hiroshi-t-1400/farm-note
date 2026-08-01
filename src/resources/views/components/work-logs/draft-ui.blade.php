<div x-show="hasDraft" class="grid grid-cols-1 mb-2">
        <p>保存されていない下書きがあります。</p>
        <p x-show="!isOnline">ネットワークがある場所で送信と保存を完了させてください。</p>
    <div>

        <x-ui.select
            x-model="selectedDraftUuid"
            name="draft_select"
            class="max-w-lg">
            <x-slot>
                <option value="">-- 選択して下書きを確認してください。（<span x-text="draftWorkLog.length"></span>件） --</option>
                <template x-for="(draft, index) in draftWorkLog" :key="draft.draft_uuid">
                    <option :value="draft.draft_uuid" x-text="`作業日: ${draft.formData.work_date} | 作物名: ${draft.formData.crop_name || '未選択'} | 作業名: ${draft.formData.title || '未記入'}`"></option>
                </template>
            </x-slot>
        </x-ui.select>
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
</div>
