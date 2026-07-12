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

                        <div x-data="{
                            allMaterials: {{ $materials->toJson() }},
                            types: {{ $types->toJson() }},

                            selectedType: '',
                            searchKeyword: '',
                            selectedMaterialId: '',

                            {{-- 選択された種別や入力フォームのあいまい検索から資材選択を助ける --}}
                            get filteredMaterials() {
                                return this.allMaterials.filter(material => {
                                    const matchType = this.selectedType === '' || material.type_id == this.selectedType;
                                    const matchKeyword = material.name.toLowerCase().includes(this.searchKeyword.toLowerCase());

                                    return matchType && matchKeyword;
                                });
                            },

                            {{-- 選択された資材の情報を取得する --}}
                            get selectedMaterial() {
                                return this.allMaterials.find(material => material.id == this.selectedMaterialId) || null;
                            },
                        }" class="max-w-md mx-auto p-6 bg-white rounded-lg shadow">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">種別で絞り込み</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type_filter" value="" x-model="selectedType" class="form-radio text-blue-600">
                                        <span class="ml-1 text-sm">すべて</span>
                                    </label>
                                    <template x-for="type in types" :key="type.id">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type_filter" :value="type.id" x-model="selectedType" class="form-radio text-blue-600">
                                            <span class="ml-1 text-sm" x-text="type.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="material_search" class="block text-sm font-medium text-gray-700 mb-1">登録する資材</label>

                                <input type="text" id="material_search" x-model="searchKeyword" placeholder="資材名を入力して絞り込み..."
                                    class="w-full border border-gray-300 rounded-md p-2 mb-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <select name="material_id" x-model="selectedMaterialId" size="5"
                                        class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- 資材を選択してください（<span x-text="filteredMaterials.length"></span>件該当） --</option>
                                    <template x-for="material in filteredMaterials" :key="material.id">
                                        <option :value="material.id" x-text="material.name" ></option>
                                    </template>
                                </select>
                            </div>

                            <div class="text-xs text-gray-400 mt-2">
                                選択中の資材ID: <span x-text="selectedMaterialId || '未選択'"></span>
                            </div>

                            <div x-show="selectedMaterial" x-transition class="mt-4 p-4 bg-gray-50 rounded-md border border-gray-200 text-sm">
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="text-gray-500">メーカー名：</div>
                                    <div class="font-medium text-gray-900" x-text="selectedMaterial?.manufacturer || '未登録'"></div>

                                </div>
                            </div>
                        </div>

                            {{-- <div class="grid grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
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

                        </div> --}}

                        {{-- <div x-data="{
                            ingredients: [{ name: '', amount: '' }]
                        }" class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">

                            <template x-for="(ingredient, index) in ingredients" :key="index">
                                <div>
                                    <div class="flex-1">
                                        <input
                                            type="text"
                                            :name="`ingredients[${index}][name]`"
                                            x-model="ingredient.name"
                                            placeholder="例：農薬"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            required
                                        >
                                    </div>

                                    <div class="w-32">
                                        <input
                                            type="text"
                                            :name="`ingredients[${index}][amount]`"
                                            x-model="ingredient.amount"
                                            placeholder="例：20ml"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            required
                                        >
                                    </div>

                                    <button
                                        type="button"
                                        @click="ingredients.splice(index, 1)"
                                        x-show="ingredients.length > 1"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-md transition"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <button
                                type="button"
                                @click="ingredients.push({ name: '', amount: '' })"
                                class="mt-2 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                材料を追加する
                            </button>
                        </div> --}}

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
