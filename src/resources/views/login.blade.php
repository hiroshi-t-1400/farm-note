{{-- src/resources/views/login.blade.php --}}

<x-layouts.app>
    <x-slot:content>
        <mainclass="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="flex flex-col p-10 rounded-md bg-white w-md h-sm justify-self-center justify-center">

                <div class="mb-6">
                    <h1 class="py-5 text-2xl font-bold text-gray-900">
                        農作業日誌 - Farm Note -
                    </h1>
                    <h2 class="p-2 text-xl font-bold text-gray-900">
                        ログイン
                    </h2>
                </div>


                <form x-data="tryAuthForm"
                    @submit.prevent="submitLogin()">

                    <x-ui.form-group class="flex flex-col gap-5">

                        <x-ui.input
                            x-model="email"
                            type="text" name="loginId" placeholder="ログインID" required />

                        <x-ui.input
                            x-model="password"
                            type="password" name="loginId" placeholder="パスワード" required />

                        <a href="" class="mx-5 pb-0.5 text-xs font-semibold text-gray-500">パスワードのリセット</a>

                        <x-ui.button
                            class="w-[10rem] justify-self-center">
                            ログイン
                        </x-ui.button>

                    </x-ui.form-group>
                </form>

            </div>

        </main>

        <!-- 共通フッター -->
        <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} 農作業管理システム
            </div>
        </footer>

    </x-slot>
</x-layouts.app>

