<x-work-logs.application.create>


    <x-slot:title>
        <div class="title-wrapper py-5 my-5 text-center">
            <h2 class="font-bold text-3xl">作業登録</h2>
        </div>
    </x-slot>


    <x-slot:formHead>
        <div class="input-form-wrapper">

            <form
                x-data="postForm({
                    initialMaterials: @js($materials),
                    initialTypes: @js($types),
                    initialCropSeasons: @js($crop_seasons),
                    initialUsers: @js($users)
                })"
                @submit.prevent="submitForm"
                action="{{ route('store') }}"
                method="post"
            >

                @csrf
    </x-slot>
    <x-slot:header>

                {{-- デバッグ用のネットワーク状態インジケータ --}}
                <div class="grid grid-cols-3">
                    <div class="col-start-3 border border-md border-blue-800">
                        <p>デバッグツール</p>
                        <span>現在のネットワーク：</span><span x-text="showOnlineStatus"></span>
                        <button type="button" @click="toggleOnline()" class="rounded-md border border-md bg-gray-600 text-white block">切り替え</button>
                    </div>
                </div>


                <div class="block text-sm font-medium text-gray-700 mb-2" >
                    作業登録者：　<span x-text="allUsers[0].name"></span>
                </div>

    </x-slot>

                <x-slot:bottom>
                    {{-- 下部ボタンエリア --}}
                    <div class="submit-button grid grid-cols-3 gap-2  sm:max-w-1/2 ">
                        <button type="submit" class="px-4 py-1 rounded-md bg-blue-500 text-bold text-white">保存</button>
                        <div class="grid place-content-center rounded-md text-bold ">キャンセル</div>

                        <div class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書き保存</div>
                        {{-- <div x-show="isDraft" class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書きをやめて新しい記録として保存</div> --}}
                    </div>
                </x-slot>
            </div>

</x-application.work-logs.create>
