{{-- エリア１非動的フォーム部 --}}


{{-- 作物選択 --}}
<div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2" >
    <x-ui.form-label for="crop_season_id" class="col-span-2" >作業した作物</x-work-logs.tabel>
    <select x-model="formData.crop_season_id" @change="changeCropSeasons()" name="crop_season_id" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="crop_season_id">
        <option value="">作物を選択</option>
        <template x-for="cropSeason in allCropSeasons" :key="cropSeason.id">
            <option :value="cropSeason.id" x-text="cropSeason.crop_season_nameYear"></option>
        </template>
    </select>
    {{-- 作付マスターに遷移 --}}
    <a href="" class="mx-5 text-bold">＋作付けを新規に追加する</a>
    {{-- バリデーションメッセージ --}}
    <x-common.form.error field='crop_season_id' />
</div>

{{-- 作業名称 --}}
<div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
    <x-ui.form-label for="title" class="col-span-2">作業名称</x-ui.form-label>
    <x-ui.input type="text" x-model="formData.title" name="title" placeholder="（例）防除１回目" />
    {{-- バリデーションメッセージ --}}
    <x-common.form.error field='title' />
</div>

{{-- 作業日 --}}
<div class="bg-white mb-1 px-1 py-2">
    <x-ui.form-label for="work_date" class="col-span-2">作業日</x-ui.form-label>
    {{-- <div class="sm:col-start-1"> --}}
    <input type="date" x-model="formData.work_date" name="work_date" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg max-w-40">
    <div class="inline-block">
        {{-- 完了した作業を登録する場合は予定日のチェックオフ、今後の予定を登録する場合はチェックオン、投稿が下書きになった場合は上書きしてチェックオフ、現在より過去か未来かで自動的に値を決定する？>>するつもりだった作業を登録する場合を考慮する？ --}}
        <input type="checkbox" x-model="formData.status" name="status" id="status" class="ms-2" >
        <label for="status" class="font-semibold text-base text-gray-700">予定</label>
    </div>
    {{-- バリデーションメッセージ --}}
    <x-common.form.error field='status' />
    <x-common.form.error field='work_date' />
</div>


{{-- 作業実施者 --}}
<div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
    <x-ui.form-label for="performed_by" class="sm:col-span-2">作業実施者</x-ui.form-label>

    <select x-model="formData.performed_by" name="performed_by" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="performed_by">
        <option value="">作業実施者</option>
        <template x-for="user in allUsers">
            <option :value="user.id" x-text="user.name"></option>
        </template>
    </select>
    {{-- ユーザ登録に遷移 --}}
    <a href="" class="mx-5 text-bold">＋作業者を新規に追加する</a>
    {{-- バリデーションメッセージ --}}
    <x-common.form.error field="performed_by" />
</div>


{{-- 作業内容 --}}
<div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
    <x-ui.form-label for="content" class="sm:col-span-2">作業内容</x-ui.form-label>
    <textarea x-model="formData.content" name="content" id="content" class="block rounded-md outline outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="作業した内容を記入してください。"></textarea>
    {{-- 内容のテンプレートを作成する？ --}}
    {{-- バリデーションメッセージ --}}
    <x-common.form.error field="content" />
</div>


