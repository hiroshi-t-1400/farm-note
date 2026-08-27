{{-- /var/www/src/resources/views/admin/users/register.blade.php --}}

<x-layouts.layout title="ユーザー登録 - 農作業日誌">

    <x-slot:header>
        ユーザーの登録
    </x-slot>


    <form
        x-data="createUserChangeRequest({
            initialModel: @js($requestData)
        })"
        @submit.prevent="submitStore()"
        x-cloak
    >

        @if ($requestData['actionType'] === 'create')
            <x-admin.requests.users.create>

            </x-admin.requests.users.create>
        @elseif ($actionType === 'update')

        @elseif ($actionType === 'delete')

        @endif

        <div class="flex py-5 justify-center gap-x-4">
            <x-ui.button name="submit" dusk="submit-button"
                class="w-[10rem]">
                登録
            </x-ui.button>
            <x-ui.button
                type="href"
                name="cancel"
                ::href="backUrl"
                variant="secondary-ghost"
                dusk="cancel-button"
                class="w-[10rem]">
                キャンセル
            </x-ui.button>
        </div>

    </form>

</x-layouts.layout>
