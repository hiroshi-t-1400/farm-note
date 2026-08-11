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

                <div x-data="{ popoverOpen: false }" class="relative w-full">
                    <x-ui.button
                        type="button"
                        @click="popoverOpen = true"
                        variant="danger"
                        >
                        削除
                    </x-ui.button>

                    <div
                        x-show="popoverOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        @click.outside="popoverOpen = false"
                        class="absolute -top-full left-50% mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg p-3 z-10"
                    >
                        <p class="text-xs text-gray-600 mb-2">この記事を削除しますか？</p>

                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                @click="popoverOpen = false"
                                class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded"
                            >
                                キャンセル
                            </button>
                            <button
                                type="button"
                                @click="deleteLog(); popoverOpen = false"
                                class = 'px-2 py-1 text-xs bg-red-600 text-white hover:bg-red-700 rounded',>
                                削除する
                            </button>
                        </div>
                    </div>
                </div>

                <x-ui.button
                    type="href"
                    ::href="prevUrl"
                    variant="secondary">キャンセル</x-ui.button>

            </div>
        </form>
    </div>

