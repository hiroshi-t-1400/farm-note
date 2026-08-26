{{-- resources/views/components/work-logs/application/create.blade.php --}}

@props([
    'models' => '',
    ])

    <h2 class="text-lg font-bold text-gray-800 mb-2">📝 作業の登録</h2>

    <div class="input-form-wrapper">
{{-- <div x-data x-cloak> --}}

        {{-- <template x-if="$store.auth.loading">
            読み込み中...
        </template> --}}

        {{-- <template x-if="!$store.auth.loading"> --}}
        <form
            x-data="createWorkLog({
                initialModels: @js($models)
            })"
            @submit.prevent="submitForm"
            x-cloak
        >

            {{-- デバッグ用のネットワーク状態インジケータ --}}
            <x-debug.network />

            {{-- 下書き用のUI・アラート --}}
            <x-work-logs.draft-ui />

            <div class="input-form-inner ">

                <x-work-logs.basic-form-section />

                <x-work-logs.material-logs />

            </div>

            <x-work-logs.action-buttons >
                <x-ui.button type="submit" variant="primary" >
                    保存
                </x-ui.button>

                <x-ui.button
                    type="button"
                    @click="skipDraft()"
                    x-show="formData.draftUuid"
                    variant="alert-ghost"
                >
                    下書きを中止し<br>新規として保存
                </x-ui.button>
            </x-work-logs.action-buttons>

        </form>
        {{-- </template> --}}
{{-- </div> --}}

    </div>



