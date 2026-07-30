
{{-- 作物選択 --}}
<div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2" >
    <label for="crop_season_id" class="form-label sm:col-span-2 font-semibold text-lg">作業した作物</label>

    {{-- x-if で --}}

    {{ $formBody }}

    {{-- 作付マスターに遷移 --}}
    {{-- ユーザーが管理者のとき有効にする --}}
    <a href="" class="mx-5 text-bold">＋作付けを新規に追加する</a>

</div>
