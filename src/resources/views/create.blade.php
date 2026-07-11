<!DOCTYPE html>
<html lang="{{ str_replace('_', '_', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name='csrf-token' content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Farm Note') }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 px-2 place-content-center bg-green-50 ">

        <div class="title-wrapper py-5 my-5 text-center">
            <h2 class="font-bold text-3xl">作業登録</h2>
        </div>

        <div class="input-form-wrapper">

            <form action="" method="post">
                @csrf
                <div class="input-form-inner ">
                    {{-- 作物選択 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="crop_season_id" class="form-label sm:col-span-2 font-semibold text-lg">作業した作物</label>
                        <select name="crop_season_id" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="crop_season_id">
                            <option value="">作物を選択</option>
                            @foreach ($crop_seasons as $crops)
                            <option value="{{ $crops->id }}">{{ $crops->crops->name }}</option>
                            @endforeach
                        </select>
                        {{-- 作付マスターに遷移 --}}
                        <a href="" class="mx-5 text-bold">＋作付けを新規に追加する</a>
                    </div>

                    {{-- 作業名称 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="title" class="form-label sm:col-span-2 font-semibold text-lg">作業名称</label>
                        <input type="text" name="title" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="（例）防除１回目">
                    </div>

                    {{-- 作業日 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="work_date" class="form-label font-semibold text-lg">作業日</label>
                        <div>
                            <input type="date" name="work_date" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg max-w-40" >
                            {{-- 完了した作業を登録する場合は予定日のチェックオフ、今後の予定を登録する場合はチェックオン、投稿が下書きになった場合は上書きしてチェックオフ、現在より過去か未来かで自動的に値を決定する？>>するつもりだった作業を登録する場合を考慮する？ --}}
                                <input type="checkbox" name="status" id="status" class="ms-2" >
                                <label for="status" class="form-label font-semibold text-lg sub-checkbox">予定</label>
                        </div>
                    </div>

                    {{-- 作業実施者 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="performed_by" class="form-label sm:col-span-2 font-semibold text-lg">作業実施者</label>
                        <select name="performed_by" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="performed_by">
                            <option value="">作業実施者</option>
                            @foreach ($users as $user)

                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                            {{-- 登録者作業者のidをデフォルトで選択させる --}}
                        </select>
                        {{-- ユーザ登録に遷移 --}}
                        <a href="" class="mx-5 text-bold">＋作業者を新規に追加する</a>
                    </div>

                    {{-- 作業内容 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="content" class="form-label font-semibold text-lg">作業内容</label>
                        <textarea type="text" name="content" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="作業した内容を記入してください。"></textarea>
                        {{-- 内容のテンプレートを作成する？ --}}
                    </div>

                    {{-- 使用資材記録 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="" class="form-label mb-1 font-semibold text-lg">資材の記録</label>

                        <div class="grid sm:grid-cols-2 grid-cols-1 gap-x-6 mx-2 mb-1">
                            <h4 class="mb-1 sm:col-span-2 font-bold text-md ">資材１</h4>
                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                <label for="material_type" >区分・種別</label>

                                <select name="material_type" id="material_type" class="rounded-md outline-2 outline-gray-600 px-2 m-0.5 ">
                                    <option value="pesticide">農薬</option>
                                    <option value="fertilizer">肥料</option>
                                    <option value="pot">ポット・鉢</option>
                                    <option value="mulch">マルチ</option>
                                    <option value="prop">支柱・鉄筋</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                <label for="material_name" >名称</label>
                                <select name="material_name" id="material_name" class="rounded-md outline-2 outline-gray-600 px-2 m-0.5">
                                    @foreach ($materials as $material)

                                        <option value="{{ $material->id }}">{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">

                                <span class="">メーカー名</span>
                                <span class=""></span>
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                <label for="quantity" >使用量</label>
                                <input type="text" name="quantity" class="rounded-md outline-2 outline-gray-600 px-2 m-0.5" placeholder="使用量">
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                <label for="dilution_rate" >希釈倍率</label>
                                <input type="text" name="dilution_rate" class="rounded-md outline-2 outline-gray-600 px-2 m-0.5" placeholder="希釈倍率" >
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                <label for="material_amount" >原液量</label>
                                <input type="text" name="material_amount" class="rounded-md outline-2 outline-gray-600 px-2 m-0.5" placeholder="原液量">
                            </div>

                        </div>

                        <div class="mb-3">
                                <h3>＋資材を新規に追加</h3>
                        </div>
                    </div>


                    {{-- 下部ボタンエリア --}}
                    <div class="submit-button grid grid-cols-3 gap-2  sm:max-w-1/2 ">
                        <button type="submit" class="px-4 py-1 rounded-md bg-blue-500 text-bold text-white">保存</button>
                        <div class="grid place-content-center rounded-md text-bold ">キャンセル</div>
                        <div class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書き保存</div>
                    </div>


                </div>
            </form>

        </div>
    </div>

</body>
</html>
