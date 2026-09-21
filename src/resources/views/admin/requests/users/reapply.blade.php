{{-- /var/www/src/resources/views/admin/users/register.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        ユーザー登録の申請内容を編集
    </x-slot>


    <form
        x-data="reapplyUserChangeApplication({
            'initialModel': @js($changeRequest)
        })"
        @submit.prevent="submit()"
    >

        <x-admin.requests.users.edit>

            <template x-if="rejectionReason">
                <x-ui.textarea
                    name="rejectionReason"
                    x-model="rejectionReason"
                    variant="error"
                    disabled
                    class="w-full"
                />
            </template>

            <x-slot:bottom_button>
                {{-- bottom --}}
                <div class="flex py-5 justify-center gap-x-4">
                    <template x-if="isAcknowledged">
                        <x-ui.button name="submit" dusk="submit-button"
                            class="w-[10rem]">
                            再申請を送信する
                        </x-ui.button>
                    </template>

                    @can('acknowledge', $changeRequest)
                        <template x-if="!isAcknowledged">
                            <x-ui.button
                                variant="danger"
                                type="button"
                                @click="submitAcknowledge()"
                                name="acknowledge" dusk="submit-acknowledge"
                                class="w-[10rem]"
                            >
                                内容を確認した
                            </x-ui.button>
                        </template>
                    @endcan

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
