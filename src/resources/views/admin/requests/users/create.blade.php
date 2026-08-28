{{-- /var/www/src/resources/views/admin/users/register.blade.php --}}

<x-layouts.layout title="ユーザー管理の申請 - 農作業日誌">

    <x-slot:header>
        ユーザー新規登録の申請
    </x-slot>


    <form
        x-data="createUserChangeRequest({
            initialModel: @js($requestData)
        })"
        @submit.prevent="submitStore()"
        x-cloak
    >

            <x-admin.requests.users.create>

            </x-admin.requests.users.create>

            <x-slot:submitButton>
                申請する
            </x-slot>
    </form>

</x-layouts.layout>
