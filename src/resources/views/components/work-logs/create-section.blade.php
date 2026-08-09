{{-- resources/views/components/work-logs/application/create.blade.php --}}

@props([
    'models' => '',
    ])

    <h2 class="text-lg font-bold text-gray-800 mb-2">📝 作業の登録</h2>

    <div class="input-form-wrapper">

        <form
            x-data="createWorkLog({
                initialModels: @js($models)
            })"
            @submit.prevent="submitForm"
        >

            {{-- デバッグ用のネットワーク状態インジケータ --}}
            <x-debug.network />

            {{-- 操作しているユーザー情報 --}}
            <div class="block text-sm font-medium text-gray-700 mb-2" >
                作業登録者：　<span x-text="allUsers[0].name"></span>
            </div>

            {{-- 下書き用のUI・アラート --}}
            <x-work-logs.draft-ui />

            <div class="input-form-inner ">

                <x-work-logs.basic-form-section />

                <x-work-logs.material-logs />

            </div>

            {{-- 下部ボタンエリア --}}
            <div class="submit-button grid grid-cols-3 gap-2  sm:max-w-1/2 ">
                <x-ui.button type="submit" variant="primary" >
                    保存
                </x-ui.button>
                <x-ui.button href="/work-logs/create" variant="secondary">キャンセル</x-ui.button>
                {{-- <div class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書き保存</div> --}}
                <x-ui.button
                    type="button"
                    x-show="formData.draftUuid"
                    variant="alert-ghost"
                >
                    下書きを中止し<br>新規として保存
                </x-ui.button>
            </div>
        </form>
    </div>



