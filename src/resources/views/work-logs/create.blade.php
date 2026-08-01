<x-layouts.layout title="日誌記録画面 - 農作業日誌">
    <x-slot:header>
        日誌記録
    </x-slot>

    <x-work-logs.application.create>

        <div class="input-form-wrapper">
            <form
                x-data="postForm({
                    initialMaterials: @js($materials),
                    initialTypes: @js($types),
                    initialCropSeasons: @js($crop_seasons),
                    initialUsers: @js($users)
                })"
                @submit.prevent="submitForm"
                action="{{ route('store') }}"
                method="post"
            >
                @csrf

                {{-- デバッグ用のネットワーク状態インジケータ --}}
                <div class="grid grid-cols-3">
                    <div class="col-start-3 border border-md border-blue-800">
                        <p>デバッグツール</p>
                        <span>現在のネットワーク：</span><span x-text="showOnlineStatus"></span>
                        <button type="button" @click="toggleOnline()" class="rounded-md border border-md bg-gray-600 text-white block">切り替え</button>
                    </div>
                </div>

                <div class="block text-sm font-medium text-gray-700 mb-2" >
                    作業登録者：　<span x-text="allUsers[0].name"></span>
                </div>

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
                            x-show="formData.draft_uuid"
                            variant="alert-ghost"

                        >
                            下書きを中止し<br>新規として保存
                        </x-ui.button>
                        {{-- <div x-show="isDraft" class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書きをやめて新しい記録として保存</div> --}}
                    </div>
            </form>

        </div>

    </x-work-logs.application.create>
</x-layouts.layout>
