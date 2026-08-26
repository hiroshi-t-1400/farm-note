{{-- src/resources/views/login.blade.php --}}

<x-layouts.guest>

    <x-slot:header>
        ログイン
    </x-slot>

    <form
        x-data="tryAuthForm()"
        @submit.prevent="submitLogin()">

        @csrf {{-- あってもなくても --}}
        <x-ui.form-group class="flex flex-col gap-5">

            <x-ui.input name="email"
                x-model="email"
                type="text" name="email" placeholder="E-Mailアドレス" required />

            <x-ui.input name='password'
                x-model="password"
                type="password" name="password" placeholder="パスワード" required />

            {{-- <a href="" class="mx-5 pb-0.5 text-xs font-semibold text-gray-500">パスワードのリセット</a> --}}
            {{-- <a href="" class="mx-5 pb-0.5 text-base font-semibold text-gray-500">アカウントの登録</a> --}}

            <x-ui.button
                class="w-[10rem] justify-self-center">
                ログイン
            </x-ui.button>
        </x-ui.form-group>

        {{-- デバッグ用エリア --}}
        <div class="flex flex-wrap gap-1">

            <div class="bg-gray-300">
                <x-ui.button type="button" @click="loginAsOwner">
                    オーナー
                </x-ui.button>
            </div>
            <div class="bg-gray-300">
                <x-ui.button type="button" @click="loginAsManager">
                    管理者：田中 耕作
                </x-ui.button>
            </div>
            <div class="bg-gray-300">
                <x-ui.button type="button" @click="loginAsWorker">
                    一般ユーザー
                </x-ui.button>
            </div>
        </div>
    </form>

</x-layouts.guest>
