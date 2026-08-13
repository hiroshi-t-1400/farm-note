{{-- src/resources/views/login.blade.php --}}

<x-layouts.guest>

    <x-slot:header>
        アカウントの登録
    </x-slot>


    <form
        x-data="tryAuthForm()"
        @submit.prevent="submitRegister()">

        <x-ui.form-group class="flex flex-col gap-4">
            @csrf
            <div class="flex flex-col">
                <x-ui.form-label for="username">
                    お名前
                </x-ui.form-label>
                <x-ui.input type="text"
                    x-model="username"
                    type="text" name="username" placeholder="例：アグリ 太郎" required />
            </div>

            <div class="flex flex-col">
                <x-ui.form-label for="loginId" >
                    ログインID
                </x-ui.form-label>
                <x-ui.input type="text"
                    x-model="loginId"
                    type="text" name="loginId" placeholder="例：nihon_taro" required />
            </div>

            <div class="flex flex-col">
                <x-ui.form-label for="email">
                    E-Mailアドレス
                </x-ui.form-label>
                <x-ui.input type="email"
                    x-model="email"
                    type="text" name="email" placeholder="例：farm_taro@example.org" required />
            </div>

            <div class="flex flex-col">
                <x-ui.form-label for="password">
                    パスワード
                </x-ui.form-label>
                <x-ui.input type="password"
                    x-model="password"
                    type="password" name="password" placeholder="パスワード" required />
            </div>

            <div class="flex flex-col">
                <x-ui.form-label for="passwordConfirmed">
                    パスワードの確認
                </x-ui.form-label>
                <x-ui.input name='passwordConfirmed'
                    x-model="passwordConfirmed"
                    type="password" name="passwordConfirmed" placeholder="同じパスワード" required />
            </div>


            <div class="flex py-5 justify-center">
                <x-ui.button
                    class="w-[10rem]">
                    登録
                </x-ui.button>
            </div>

        </x-ui.form-group>
    </form>

</x-layouts.guest>
