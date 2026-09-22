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

            <x-slot:isAcknowledge>
                <span x-show="isAcknowledged" class="font-bold text-amber-800">（確認済み）</span>
            </x-slot>

            <x-slot:reapplyStatus>
                <x-ui.form-group>
                    <span class="text-gray-800 text-base font-semibold">
                        再申請：
                            <span x-text="reapplyStatus" class="font-bold text-blue-500"></span>
                            <span x-show="!reapplyStatus"
                                class="font-bold text-blue-500">再申請されていません。</span>
                    </span>
                </x-ui.form-group>
            </x-slot>


            <x-slot:rejectionReason>
                <template x-if="rejectionReason">
                    <x-ui.textarea
                        name="rejectionReason"
                        x-model="rejectionReason"
                        variant="error"
                        disabled
                        class="w-full"
                    />
                </template>
            </x-slot>

            <x-slot:bottom_button>
                {{-- bottom --}}
                <div class="flex py-5 justify-center gap-x-4">
                    <template x-if="canReapply()">
                        <x-ui.button name="submit" dusk="submit-button"
                            class="w-[10rem]">
                            再申請を送信する
                        </x-ui.button>
                    </template>

                    @can('history', $changeRequest)
                        <template x-if="canAcknowledge()">
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
