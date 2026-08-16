{{-- src/resources/views/login.blade.php --}}

<x-layouts.guest>

    <x-slot:header>
        アカウントの登録
    </x-slot>

    <form
        x-data="tryAuthForm({
            initialData: $user
        })"
        @submit.prevent="submitVerifyEmail (user)">

        <div class="mb-2">
            <h3 class="text-base font-semibold">
                アカウントの登録はまだ完了しておりません。
            </h3>
        </div>

        <x-dashboard.empty-state>
            <x-slot:alertMessage>
                <div class="grid grid-cols-1 gap-2 py-2">
                    <template x-if="sentStatus === 'verification-link-sent'">
                        <div>
                            登録したメールアドレスに確認用メールを送信しました。
                        </div>
                    </template>

                    <p>
                        メールを確認して登録を完了してください。
                    </p>
                </div>
            </x-slot>

            <x-slot:alertGuide>
                <div class="grid grid-cols-1 gap-2 py-2">
                    <p>メールの到着には時間がかかることがございます。</p>
                    <div>
                        しばらく待ってもメールが届かない場合は、再度メールの送信を行ってください。
                    </div>

                    <div>
                        「<span class="font-semibold">farm-note-dummy@example.org</span>」を受信設定にしてください。
                    </div>
                </div>
            </x-slot>

            <x-slot:alertButton>
                <x-ui.button variant="alert-ghost">
                    確認メールを送信
                </x-ui.button>

            </x-slot>
        </x-dashborad.empty-state>

    </form>

</x-layouts.guest>

