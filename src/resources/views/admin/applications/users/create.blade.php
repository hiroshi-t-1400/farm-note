{{-- /var/www/src/resources/views/admin/users/register.blade.php --}}

<x-layouts.layout title="ユーザー管理の申請 - 農作業日誌">

    <x-slot:header>
        ユーザー新規登録の申請
    </x-slot>


    <form
        x-data="createUserChangeApplication({
            initialModel: @js($applicationData)
        })"
        @submit.prevent="submit()"
        x-cloak
    >

            <x-admin.applications.users.create />

    </form>

</x-layouts.layout>
