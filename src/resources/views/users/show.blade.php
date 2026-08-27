{{-- /var/www/src/resources/views/users/show.blade.php --}}

<x-layouts.layout title="ユーザー情報 - 農作業日誌">

    <x-slot:header>
        ユーザー情報
    </x-slot>


    <div
        x-data="showUser({
            'initialModels': @js($user)
        })"
        x-cloak
    >

        <dl class="sm:max-w-[20rem]">
            <x-presentation.description-item
                label="氏名："
            >
                <span x-text="username"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="状態："
            >
                <span x-text="statusLabel" :class="`${statusClass}`"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="ログインID"
            >
                <span x-text="loginId"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="メールアドレス"
            >
                <span x-text="email"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="管理者権限"
            >
                <span x-text="roleLabel"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="登録・承認日"
            >
                <span x-text="createdAt"></span>
            </x-presentation.description-item>

            <x-presentation.description-item
                label="最終更新日"
            >
                <span x-text="updatedAt"></span>
            </x-presentation.description-item>

        </dl>

        <div class="flex py-5 justify-center">
            <div class="flex flex-wrap gap-x-4 gap-y-2">
                <x-ui.button
                    type="href"
                    {{-- href="editUrl" --}}
                    name="edit" dusk="submit-edit"
                    class="w-[10rem]"
                >
                    編集する
                </x-ui.button>

                <x-ui.button
                    type="button"
                    variant="danger"
                    {{-- @click="submitDelete()" --}}
                    name="delete" dusk="submit-delete"
                    class="w-[10rem]"
                >
                    削除する
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



    </div>

</x-layouts.layout>
