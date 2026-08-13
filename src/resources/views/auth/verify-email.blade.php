{{-- src/resources/views/login.blade.php --}}

<x-layouts.guest>

    <x-slot:header>
        アカウントの登録
    </x-slot>

    //
    <form
        x-data="tryAuthForm({
            initialData: $user
        })"
        @submit.prevent="submitVerifyEmail (user)">

        <div class="text-base font-semibold">
            アカウントの登録はまだ完了しておりません。
        </div>

        <template x-if="sentStatus === 'verification-link-sent'">
            <div>
                登録したメールアドレスに確認のメールを送信しました。
            </div>
        </template>

        <div>
            メールを確認して登録を完了してください。
        </div>

        <div>
            <p>メールの到着には時間がかかることがございます。</p>
            <div>
                しばらく待ってもメールが届かない場合は、メールフィルターの受信設定でfarm-note-dummy@example.orgが許可されているかをご確認の上で再度メールの送信を行ってください。</p>
            </div>
        </div>

        <x-ui.button variant="alert-ghost">
            確認メールを送信
        </x-ui.button>

        {{-- <x-ui.button type="href" variant="secondary" href="{{ url('/login') }}">
            アカウント登録を中断する
        </x-ui.button> --}}
    </form>

</x-layouts.guest>

