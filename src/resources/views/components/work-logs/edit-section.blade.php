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

            {{-- 下部アクションボタン --}}
            <x-work-logs.action-buttons >
                <x-ui.button type="submit" variant="primary" >
                    保存
                </x-ui.button>

                <x-work-logs.window-del-popover confirmEvent="deleteLog()" >
                    <x-ui.button
                        type="button"
                        variant="danger"
                        class="w-full">
                        削除
                    </x-ui.button>
                </x-work-logs.window-del-popover>

                <x-ui.button
                    type="href"
                    ::href="prevUrl"
                    variant="secondary">キャンセル
                </x-ui.button>
            </x-work-logs.action-buttons>

        </form>
    </div>

