{{-- resources/views/components/work-logs/application/create.blade.php --}}

@props([
    'models' => '',
    ])

{{-- <h2 class="text-lg font-bold text-gray-800 mb-2">📝 日誌の編集</h2> --}}
    <div class="input-form-wrapper">
        <form
            x-data="editWorkLog({
                initialModels: @js($models)
            })"
            @submit.prevent="submitForm"
        >
            @csrf
            {{-- デバッグ用のネットワーク状態インジケータ --}}
            <x-debug.network />

            {{-- 操作しているユーザー情報 --}}
            <div class="block text-sm font-medium text-gray-700 mb-2" >
                作業登録者：　<span x-text="allUsers[0].name"></span>
            </div>


            <div class="input-form-inner ">

                <x-work-logs.basic-form-section />

                <x-work-logs.material-logs />

            </div>

                {{-- 下部ボタンエリア --}}
            <div class="grid sm:grid-cols-3 grid-cols-1 sm:gap-x-10 gap-y-2 pt-10 mb-4 sm:justity-center place-content-start">
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
                <x-ui.button
                    type="href"
                    ::href="prevUrl"
                    variant="secondary">キャンセル</x-ui.button>

            </div>
        </form>
    </div>

