{{-- エリア１非動的フォーム部 --}}


{{-- 作物選択 --}}
<x-ui.form-group label="作業した作物" name="cropSeasonId" >
    <x-ui.select x-model="formData.cropSeasonId" @change="changeCropSeasons()" name="cropSeasonId" class="max-w-sm" >
        <option value="">作物を選択</option>
        <template x-for="cropSeason in allCropSeasons" :key="cropSeason.id">
            <option
                :value="cropSeason.id"
                x-text="cropSeason.cropSeasonsNameYear"
                :selected="cropSeason.id == formData?.cropSeasonId">
            </option>
        </template>
    </x-ui.select>
    {{-- 作付マスターに遷移 --}}
    <a href="" class="mx-5 text-bold">＋作付けを新規に追加する</a>
</x-ui.form-group>

{{-- 作業名称 --}}
<x-ui.form-group label="作業名称" name="title">
        <x-ui.input type="text" x-model="formData.title" name="title" class="max-w-sm" placeholder="（例）防除１回目" />
</x-ui.form-group>

{{-- 作業日 --}}
<x-ui.form-group label="作業日" name="workDate" >
    <div>
        <x-ui.input type="date" x-model="formData.workDate" name="workDate" class="sm:max-w-40 w-full max-w-full" />
        <div class="inline-block">
            {{-- 完了した作業を登録する場合は予定日のチェックオフ、今後の予定を登録する場合はチェックオン、投稿が下書きになった場合は上書きしてチェックオフ、現在より過去か未来かで自動的に値を決定する？>>するつもりだった作業を登録する場合を考慮する？ --}}
            <input type="checkbox" x-model="formData.status" name="status" id="status" class="ms-2" >
            <label for="status" class="font-semibold text-base text-gray-700">予定</label>
        </div>
    </div>
</x-ui.form-group>

{{-- 作業実施者 --}}
    <x-ui.form-group label="作業実施者" name="performedBy.0.id"> {{-- 暫定措置 --}}
        <x-ui.select x-model="formData.performedBy[0].id" name="performedBy" class="max-w-sm" >
            <option value="">作業実施者</option>
            <template x-for="user in allUsers">
                <option :value="user.id" x-text="user.name" :selected="user.id == formData?.performedBy?.[0]?.id">
                </option>
            </template>
        </x-ui.select>

        {{-- ユーザ登録に遷移 --}}
        <a href="" class="mx-5 text-bold">＋作業者を新規に追加する</a>
    </x-ui.form-group>

{{-- 作業内容 --}}
<x-ui.form-group label="作業内容" name="content">
    <x-ui.textarea x-model="formData.content" name="content" class="block " placeholder="作業した内容を記入してください。"></x-ui.textarea>
    {{-- 内容のテンプレートを作成する？ --}}
</x-ui.form-group>

