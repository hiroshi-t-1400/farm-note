
@props([
])

        <div class="flex py-5 justify-center">
            <div class="flex flex-wrap gap-x-4 gap-y-2">
                <x-ui.button
                    type="href"
                    ::href="`${editUrl}`"
                    name="edit" dusk="submit-edit"
                    class="w-[10rem]"
                >
                    編集する
                </x-ui.button>

                <x-ui.button
                    type="button"
                    variant="danger"
                    {{-- @click="submitDelete()" --}}
                    name="delete" dusk="submit-delete"
                    class="w-[10rem]"
                >
                    削除する
                </x-ui.button>

                <x-ui.button
                    type="href"
                    variant="secondary-ghost"
                    ::href="`${backUrl}`"
                    name="cancel"
                    class="w-[10rem]"
                >
                    キャンセル
                </x-ui.button>
            </div>
        </div>
