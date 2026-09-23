{{-- /var/www/src/resources/views/admin/users/approve.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        申請の処理画面
    </x-slot>

    <form
        x-data="approveUser({
            initialModels: @js($changeRequest)
        })"
    >

        <template x-if="$store.auth.loading">
            <div>読み込み中...</div>
        </template>

        <template x-if="!$store.auth.loading">
            <div>
                <dl class="mb-5">
                    <x-presentation.description-application label="申請種別">
                        <span x-text="actionLabel"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="申請状態">
                        <span x-text="applicationStatus"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="申請日">
                        <span x-text="updatedAt"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="申請者">
                        <span x-text="requester.name"></span>
                    </x-presentation.description-application>
                </dl>

                <h3 class="text-base font-semibold text-gray-800">
                    対象ユーザー
                </h3>
                <dl class="mb-5">
                    <x-presentation.description-application label="氏名">
                        <span x-text="username"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="ログインID">
                        <span x-text="loginId"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="メールアドレス">
                        <span x-text="email"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="アプリ権限">
                        <span x-text="roleLabel"></span>
                    </x-presentation.description-application>

                    <x-presentation.description-application label="却下理由">
                        <span x-text="rejectedReason"></span>
                    </x-presentation.description-application>
                </dl>

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
                    label="却下理由"
                >
                    <x-ui.textarea
                        x-model="rejectionReason"
                        name="rejectionReason"
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
            </div>
        </template>

    </form>

</x-layouts.layout>
