{{-- /var/www/src/resources/views/admin/users/approve.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        ユーザー登録の承認
    </x-slot>


    <form
        x-data="approveUser({
            initialModels: @js($changeRequest)
        })"
    >

        @csrf

        <div class="result-area py-2 px-4 sm:w-[20rem] sm:y-[7rem] mb-4 border-1 border-gray-500 rounded-lg">
            <h3 class="text-base text-gray-700 font-semibold ">申請内容</h3>
            <dl class="px-1">
                <x-ui.description-request label="氏名：">
                    <span x-text="payload['name']"></span>
                </x-ui.description-request>

                <x-ui.description-request label="メールアドレス：">
                    <span x-text="payload['email']"></span>
                </x-ui.description-request>

                <x-ui.description-request label="ログインID：">
                    <span x-text="payload.loginId"></span>
                </x-ui.description-request>

                <x-ui.description-request label="役職：">
                    <span x-text="getRoleLabel(payload.role)"></span>
                </x-ui.description-request>


            </dl>
        </div>

        <div class="flex py-5 justify-center">
            <div class="flex gap-x-4 gap-y-2">
                <x-ui.button
                    type="button"
                    @click="submitApprove()"
                    name="submit" dusk="submit-button"
                    class="w-[10rem]"
                >
                    承認する
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

        <x-ui.form-group
            name="rejection_reason"
            label="棄却理由"
        >
            <x-ui.textarea
                x-model="rejection_reason"
                name="rejection_reason"
            />

        </x-ui.form-group>


        <div class="flex py-5 justify-center">
            <x-ui.button
                type="button"
                variant="danger"
                @click="submitReject()"
                name="submit" dusk="submit-button"
                class="w-[10rem]">
                却下する
            </x-ui.button>
        </div>

    </form>

</x-layouts.layout>
