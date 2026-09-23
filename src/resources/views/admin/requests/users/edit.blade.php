{{-- /var/www/src/resources/views/admin/users/register.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        ユーザー登録の申請内容を編集
    </x-slot>


    <form
        x-data="editUserChangeRequest({
            'initialModel': @js($changeRequest)
        })"
        @submit.prevent="submitUpdate()"
    >

        <x-admin.requests.users.edit>

            <x-slot:bottom_button>

                {{-- bottom --}}
                <div class="flex py-5 justify-center gap-x-4">
                    <template x-if="canEdit()">
                        <x-ui.button name="submit" dusk="submit-button"
                            class="w-[10rem]">
                            申請内容を更新する
                        </x-ui.button>
                    </template>

                    <template x-if="canDelete()">
                        <x-ui.button
                            type="button"
                            variant="danger"
                            @click="submitDelete()"
                            name="delete" dusk="submit-delete"
                            class="w-[10rem]"
                        >
                            この申請を削除する
                        </x-ui.button>
                    </template>

                    <x-ui.button
                        type="href"
                        name="cancel"
                        ::href="backUrl"
                        variant="secondary-ghost"
                        dusk="cancel-button"
                        class="w-[10rem]">
                        戻る
                    </x-ui.button>
                </div>
            </x-slot>

        </x-admin.requests.users.edit>
    </form>

</x-layouts.layout>
