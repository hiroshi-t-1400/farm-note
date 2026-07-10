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

    {{-- <div class="main-part">
        @yield('content')

    </div> --}}

    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 place-content-center  ">

        <div class="title-wrapper">

            <h2 class="font-bold text-3x1">作業登録</h2>
        </div>

        <div class="input-form-wrapper">

            <form action="" method="post">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">作業名称</label><br>
                    <input type="text" name="title" placeholder="（例）防除１回目">
                </div>

                <div class="mb-3">
                    <label for="crop_season_id" class="form-label">対象の作物</label>
                    <select name="crop_season_id" id="crop_season_id">
                        <option value="">作物を選択してください。</option>
                        <option value="1">トマト2026年</option>
                        <option value="2">ナス2026年</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="work_date" class="form-label">作業日</label>
                    <input type="date" name="work_date" >
                    {{-- 完了した作業を登録する場合は予定日のチェックオフ、今後の予定を登録する場合はチェックオン、投稿が下書きになった場合は上書きしてチェックオフ、現在より過去か未来かで自動的に値を決定する？>>するつもりだった作業を登録する場合を考慮する？ --}}
                    <label for="status" class="form-label sub-checkbox">予定日</label>
                    <input type="checkbox" name="status" id="status">
                </div>

                <div class="mb-3">
                    <label for="performed_by" class="form-label">作業実施者</label>
                    <select name="performed_by" id="performed_by">
                        <option value="">作業実施者</option>
                        <option value="1">田中太郎</option>
                        <option value="2">佐藤花子</option>
                        {{-- 登録者作業者のidをデフォルトで選択させる --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">作業名称</label><br>
                    <textarea type="text" name="content" rows="5" cols="50" placeholder="作業した内容を記入してください。"></textarea>
                    {{-- 内容のテンプレートを作成する？ --}}
                </div>

                <div class="mb-3 rounded-md bg-gray-100 dark:bg-gray-100 pt-4 m-2 overflow-hidden">
                    <h3>＋仕様資材を追加</h3>
                </div>

                <button type="submit">保存</button>
                <div class="rounded-md">キャンセル</div>
                <div class="rounded-md">下書き保存</div>
            </form>
        </div>
        <div>

        </div>
    </div>

</body>
</html>
