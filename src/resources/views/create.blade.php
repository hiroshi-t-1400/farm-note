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

    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 px-2 place-content-center bg-green-50 min-h-screen ">

        <div class="title-wrapper py-5 my-5 text-center">
            <h2 class="font-bold text-3xl">作業登録</h2>
        </div>

        <div class="input-form-wrapper">

            <form
                x-data="postForm({
                    initialMaterials: @js($materials),
                    initialTypes: @js($types),
                    initialCropSeasons: @js($crop_seasons),
                })"
                @submit.prevent="submitForm"
                action="{{ route('store') }}"
                method="post"
            >
                @csrf

                <div class="block text-sm font-medium text-gray-700 mb-2" >
                    作業登録者：　{{ $users[0]->name }}
                    <input type="hidden" x-model="formData.created_by">
                </div>

                <div x-show="hasDraft" class="mb-2">
                    <label  for="draft_select" class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2">
                        <p>保存されていない下書きがあります。</p>
                        <p x-show="!isOnline">ネットワークがある場所で送信と保存を完了させてください。</p>

                    </label>
                    <select x-model="selectedDraftId"
                            name="draft_select"
                            class="w-full border border-gray-300 rounded-md bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- 選択して下書きを確認してください。（<span x-text="hasDraft.length"></span>件） --</option>
                        <template x-for="(draft, index) in hasDraft" :key="index">
                            <option :value="index" x-text="draft.work_date + ' | ' + draft.crop + ' | ' + draft.title"></option>
                        </template>
                    </select>
                    <button
                        type="button"
                        @click="fillWithDraft()"
                        class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-blue-300 items-center text-sm font-medium text-white"
                        >
                        下書きを読み込む
                    </button>
                    <span class="text-sm font-medium text-black">入力欄に下書きが再入力されます</span>

                </div>

                <div class="input-form-inner ">
                    {{-- 作物選択 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2" >
                        <label for="crop_season_id" class="form-label sm:col-span-2 font-semibold text-lg">作業した作物</label>
                        <select x-model="formData.crop_season_id" name="crop_season_id" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="crop_season_id">
                            <option value="">作物を選択</option>
                            <template x-for="cropSeason in showCropSeason" :key="cropSeason.id">
                                <option :value="cropSeason.id" x-text="cropSeason.name"></option>
                            </template>
                        </select>
                        {{-- 作付マスターに遷移 --}}
                        <a href="" class="mx-5 text-bold">＋作付けを新規に追加する</a>
                        {{-- バリデーションメッセージ --}}
                        <span x-show="getError('crop_season_id')"
                            x-text="getError('crop_season_id')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>

                    </div>

                    {{-- 作業名称 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="title" class="form-label sm:col-span-2 font-semibold text-lg">作業名称</label>
                        <input type="text" x-model="formData.title" name="title" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="（例）防除１回目">
                        {{-- バリデーションメッセージ --}}
                        <span
                            x-text="getError('title')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>
                    </div>

                    {{-- 作業日 --}}
                    <div class="bg-white mb-1 px-1 py-2">
                        <label for="work_date" class="form-label block font-semibold text-lg">作業日</label>
                        {{-- <div class="sm:col-start-1"> --}}
                        <input type="date" x-model="formData.work_date" name="work_date" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg max-w-40">
                        <div class="inline-block">
                            {{-- 完了した作業を登録する場合は予定日のチェックオフ、今後の予定を登録する場合はチェックオン、投稿が下書きになった場合は上書きしてチェックオフ、現在より過去か未来かで自動的に値を決定する？>>するつもりだった作業を登録する場合を考慮する？ --}}
                            <input type="checkbox" x-model="formData.status" name="status" id="status" class="ms-2" >
                            <label for="status" class="form-label font-semibold text-lg sub-checkbox">予定</label>
                        </div>
                        {{-- バリデーションメッセージ --}}
                        <span
                            x-text="getError('work_date')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>
                    </div>

                    {{-- 作業実施者 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="performed_by" class="form-label sm:col-span-2 font-semibold text-lg">作業実施者</label>
                        <select x-model="formData.performed_by" name="performed_by" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="performed_by">
                            <option value="">作業実施者</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                            {{-- 登録者作業者のidをデフォルトで選択させる --}}
                        </select>
                        {{-- ユーザ登録に遷移 --}}
                        <a href="" class="mx-5 text-bold">＋作業者を新規に追加する</a>
                        @error('performed_by')
                            <span class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 作業内容 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="content" class="form-label font-semibold text-lg">作業内容</label>
                        <textarea type="text" x-model="formData.content" name="content" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="作業した内容を記入してください。">防除１１回目　ストロビーフロアブル
                        </textarea>
                        {{-- 内容のテンプレートを作成する？ --}}
                        @error('content')
                            <span class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 使用資材記録 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <div class="form-label mb-1 font-semibold text-lg">資材の記録</div>

                        {{--  --}}
                        <div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">種別で絞り込み</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type_filter" value='' x-model="selectedType" class="form-radio text-blue-600">
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

                            <div class="mb-2">
                                <select x-model="selectedMaterialId"
                                        class="w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- 資材を選択してください（<span x-text="filteredMaterials.length"></span>件該当） --</option>
                                    <template x-for="material in filteredMaterials" :key="material.id">
                                        <option :value="material.id" x-text="isDuplicated(material.id) + material.name + ' | メーカー名：' + (material?.manufacturer || '未登録') ">
                                        </option>
                                    </template>
                                </select>

                            <button
                                type="button"
                                :disabled="selectedMaterialId === ''"
                                @click="addMaterial_log()"
                                {{-- @change="getMaterialProperty(selectedMaterialId)" --}}
                                class="mt-1 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                資材を追加する
                            </button>

                            {{-- 動的フォーム --}}
                            <template x-for="(material_log, index) in formData.material_logs" :key="index">
                                <div class="grid sm:grid-cols-2 rounded-md border border-gray-200 text-sm">
                                    <div class="">
                                        <span class="" x-text="'資材' + (index + 1)"></span>
                                        <button
                                            type="button"
                                            @click="removeMaterial_log(index)"
                                            x-show="formData.material_logs.length > 1"
                                            class="px-1 py-1 text-red-600 hover:bg-red-50 rounded-md transition"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <span class="text-xs text-gray-600">種別：</span>
                                        <span class="font-medium text-gray-900" x-text="material_log.type_label"></span>
                                    </div>

                                    <div>
                                        <span class="text-xs text-gray-600">名称：</span>
                                        <span class="font-medium text-gray-900" x-text="material_log.name"></span>
                                    </div>

                                    <div>
                                        <span class="text-xs text-gray-600">メーカー名：</span>
                                        <span class="font-medium text-gray-900" x-text="material_log.manufacturer"></span>
                                    </div>

                                    <div class="grid sm:grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                        <label :for="`formData.material_logs[${index}][quantity]`" >使用量</label>
                                        <input type="text"
                                            :name="`formData.material_logs[${index}][quantity]`"
                                            x-model="material_log.quantity"
                                            class="rounded-md outline-2 outline-gray-600 px-2 m-0.5"
                                            placeholder="例：10本 300L"
                                        >
                                        <span x-show="getError('quantity', index)"
                                            x-text="getError('quantity', index)"
                                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
                                            role="alert"
                                        ></span>
                                    </div>

                                    <div
                                        x-show="material_log.type_id == 1 || material_log.type_id == 2"
                                        class="grid sm:grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                                        <label :for="`formData.material_logs[${index}][dilution_rate]`" >希釈倍率</label>
                                        <input
                                            type="text"
                                            :name="`formData.material_logs[${index}][dilution_rate]`"
                                            x-model="material_log.dilution_rate"
                                            class="rounded-md outline-2 outline-gray-600 px-2 m-0.5"
                                            placeholder="例：150"
                                        >
                                        <span x-show="getError('dilution_rate', index)"
                                            x-text="getError('dilution_rate', index)"
                                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
                                            role="alert"
                                        ></span>
                                    </div>

                                    <div
                                        x-show="material_log.type_id == 1 || material_log.type_id == 2"
                                        class="grid sm:grid-cols-[auto_1fr] sm:col-start-2 gap-x-4 px-2 m-0.5 ">
                                        <label :for="`formData.material_logs[${index}][material_amount]`" >原液量</label>
                                        <input
                                            type="text"
                                            :name="`formData.material_logs[${index}][material_amount]`"
                                            x-model="material_log.material_amount"
                                            class="rounded-md outline-2 outline-gray-600 px-2 m-0.5"
                                            placeholder="例：150"
                                        >
                                        <span x-show="getError('material_amount', index)"
                                            x-text="getError('material_amount', index)"
                                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
                                            role="alert"
                                        ></span>
                                    </div>
                                </div>
                            </template>

                        </div>

                    </div>



                    {{-- 下部ボタンエリア --}}
                    <div class="submit-button grid grid-cols-3 gap-2  sm:max-w-1/2 ">
                        <button type="submit" class="px-4 py-1 rounded-md bg-blue-500 text-bold text-white">保存</button>
                        <div class="grid place-content-center rounded-md text-bold ">キャンセル</div>

                        <div class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書き保存</div>
                        {{-- <div x-show="isDraft" class="grid place-content-center rounded-md bg-gray-400 text-bold text-white ">下書きをやめて新しい記録として保存</div> --}}
                    </div>


                </div>
            </form>

        </div>
    </div>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('postForm', (config) => ({
                formData: {
                    crop_season_id: '',
                    created_by: 1,
                    performed_by: '',
                    work_date: '',
                    status: false,
                    title: '',
                    content: '',
                    updated_by: '',
                    material_logs: [],
                },

                allMaterials: config.initialMaterials,
                types: config.initialTypes,
                allCropSeasons: config.initialCropSeasons,
                allCrops: config.initialCrops,
                selectedType: '',
                selectedMaterialId: '',

                isOnline: window.navigator.onLine,

                selectedDraftId: '',
                isDraft: false,

                errors: {},

                init() {
                    this.formData.work_date = this.getToday;
                },

                // 登録対象の作物を選択、データベースから作物名+年次
                get showCropSeason() {
                    return  this.allCropSeasons.map((season, index) => ({
                                id: index + 1,
                                name: season.crops.name + season.year,
                            }));
                },

                get getToday() {
                    const today = new Date();

                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}`;
                },

                // 選択された種別から資材選択を助ける
                get filteredMaterials() {
                    return this.allMaterials.filter(material => {
                        const matchType = this.selectedType === '' || material.type_id == this.selectedType;

                        return matchType;
                    });
                },

                // 選択された資材の情報を取得する
                get selectedMaterial() {
                    return this.allMaterials.find(material => material.id == this.selectedMaterialId) || null;
                },

                // 材料の追加ロジック
                addMaterial_log() {
                    if (this.selectedMaterialId === '') return;
                    if (this.selectedMaterial) {
                        this.formData.material_logs.push({
                                                    type_label: '',
                                                    material_id: '',
                                                    name: '',
                                                    manufacturer: '',
                                                    dilution_rate: '',
                                                });
                    }

                    const index = this.formData.material_logs.length - 1;
                    const matId = this.selectedMaterialId - 1;
                    this.formData.material_logs[index].type_label = this.allMaterials[matId].type_label;
                    this.formData.material_logs[index].type_id = this.allMaterials[matId].type_id;
                    this.formData.material_logs[index].material_id = this.allMaterials[matId].id;
                    this.formData.material_logs[index].name = this.allMaterials[matId].name;
                    this.formData.material_logs[index].manufacturer = this.allMaterials[matId].manufacturer;
                    this.formData.material_logs[index].quantity = '';
                    this.formData.material_logs[index].dilution_rate = this.allMaterials[matId].default_dilution_rate;
                    this.formData.material_logs[index].material_amount = '';

                    // 追加したら選択欄をリセット
                    this.selectedMaterialId = '';
                },

                // 資材フォームの削除ロジック
                removeMaterial_log(index) {
                    this.formData.material_logs.splice(index, 1);
                },

                // バリデーションエラーメッセージを返す: null or String
                getError(field, index = null) {
                    if (index === null) {
                        field = `${field}`;
                    } else if(false) {
                        field = `material_logs.${index}.${field}`;
                    } else {
                        return;
                    }
                    return this.errors[field] ? this.errors[field][0] : null;
                },

                // 登録資材重複の確認
                isDuplicated(materialId) {
                    if (this.formData.material_logs.some(material => material.material_id == materialId)) {
                        return '** 登録済み **';
                    } else {
                        return '';
                    };
                },

                /////
                // fetch()送信
                //  主に下書き機能実装のため

                // localStorageに下書きが保存されている場合は取得 or []で初期化
                draft_work_log: JSON.parse(localStorage.getItem('draft_work_log') || '[]'),

                // post送信時に呼び出される
                // @submit.preventで呼び出し
                async submitForm() {
                    const saveToLocalStorage = () => {
                        this.draft_work_log.push(JSON.parse(JSON.stringify(this.formData)));
                        localStorage.setItem('draft_work_log', JSON.stringify(this.draft_work_log));
                        alert('オフラインのためブラウザに一時保存しました。(localStorage)');

                        this.formData = {
                            crop_season_id: '',
                            created_by: 0,
                            performed_by: '',
                            work_date: this.getToday,
                            status: '',
                            title: '',
                            content: '',
                            updated_by: '',
                            material_logs: config.oldmaterial_logs,
                        };
                    };

                    if (!this.isOnline) {
                        saveToLocalStorage();
                        return;
                    }

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 5000); // 5000ms to timeout

                        // オンライン時の処理、fetch()でJSONを送信
                        const response = await fetch('{{ route('store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(this.formData),
                            signal: controller.signal // controle timeout
                        });

                        clearTimeout(timeoutId); // 通信成功したらタイマーを解除

                        // responseのJSONを解析
                        const data = await response.json();

                        ////
                        // 保存失敗処理
                        // 422 バリデーションエラーを想定してアラートとメッセージを表示
                        if (response.status === 422) {
                            this.errors = data.errors || {};
                            alert('保存に失敗しました： ' + (data.message || '入力内容を確認してください。'));
                            return;
                        }

                        // その他のエラー
                        if (!response.ok) {
                            console.error('サーバーエラー', data);
                            alert('保存に失敗しました： ' + (data.message || 'エラーが発生しました。'));
                            return;
                        }

                        ////
                        // 保存成功
                        // 下書きだったとき
                        if (this.isDraft) {
                            this.draft_work_log.splice(this.selectedDraftId, 1);
                            localStorage.setItem('draft_work_log', JSON.stringify(this.draft_work_log));
                            this.isDraft = false;
                        }

                        alert(data.message || '保存しました。');

                        // コントローラから帰ってきたURLへリダイレクト
                        window.location.href = data.redirect_url;
                    } catch (error) {
                        console.error('通信に失敗たため、ローカル保存にフォールバックします。', error);

                        saveToLocalStorage();
                    }
                },

                // 下書き機能
                get hasDraft() {
                    // if((!this.draft_work_log[0]) && false) return false;
                    if(!this.draft_work_log[0]) return false;
                    const remapDraft = this.draft_work_log.map((log, index) => ({
                        work_date: log.work_date,
                        crop: this.allCropSeasons[log.crop_season_id - 1].crops.name,
                        title: log.title,
                        formData: log,
                    }));
                    return remapDraft;
                },

                // 選択した下書きformDataを現在のフォームに流し込む
                fillWithDraft() {
                    this.formData = this.hasDraft[this.selectedDraftId].formData;
                    this.isDraft = true;
                },

                // 下書きの途中で新規の作業入力に切り替えてしまったとき
                skipDraft() {
                    this.isDraft = false;
                }
            }));
        });

    </script>
</body>
</html>
