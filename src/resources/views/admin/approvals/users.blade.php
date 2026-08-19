{{-- /var/www/src/resources/views/admin/users/approve.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        ユーザー登録の承認
    </x-slot>


    <form
        x-data=""
        {{-- @submit.prevent="submitApprove()" --}}
    >

        @csrf

        <div class="result-area py-2 px-4 sm:w-[20rem] sm:y-[7rem] mb-4 border-1 border-gray-500 rounded-lg">
            <h3 class="text-base text-gray-700 font-semibold ">申請内容</h3>
            <dl class="px-1">
                <div class="py-0.5 flex flex-wrap justify-between">
                    <dt class="text-base font-medium text-gray-600">氏名：</dt>
                    <dd class="text-base text-gray-800 sm:mt-0 sm:col-span-1">トマト 太郎</dd>
                </div>
                <div class="py-0.5 flex flex-wrap justify-between">
                    <dt class="text-base font-medium text-gray-600">メールアドレス：</dt>
                    <dd class="text-base text-gray-800 sm:mt-0 sm:col-span-1">tomato@example.org</dd>
                </div>
                <div class="py-0.5 flex flex-wrap justify-between">
                    <dt class="text-base font-medium text-gray-600">ログインID：</dt>
                    <dd class="text-base text-gray-800 sm:mt-0 sm:col-span-1">tomatotaro</dd>
                </div>
                <div class="py-0.5 flex flex-wrap justify-between">
                    <dt class="text-base font-medium text-gray-600">権限細目：</dt>
                    <dd class="text-base text-gray-800 sm:mt-0 sm:col-span-1">一般ユーザー</dd>
                </div>
            </dl>
        </div>

        <div class="flex py-5 justify-center">
            <x-ui.button name="submit" dusk="submit-button"
                class="w-[10rem]">
                登録
            </x-ui.button>
        </div>

        <x-ui.form-group
            name="rejection_reason"
            label="棄却理由"
        >
            <x-ui.textarea
                name="rejection_reason"
            />

        </x-ui.form-group>


        <div class="flex py-5 justify-center">
            <x-ui.button name="submit" dusk="submit-button"
                class="w-[10rem]">
                登録
            </x-ui.button>
        </div>

    </form>

</x-layouts.layout>
