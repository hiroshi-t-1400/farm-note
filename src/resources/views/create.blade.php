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
                    <select x-model="selectedDraftUuid"
                            name="draft_select"
                            class="w-full border border-gray-300 rounded-md bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- 選択して下書きを確認してください。（<span x-text="hasDraft.length"></span>件） --</option>
                        <template x-for="(draft, index) in hasDraft" :key="draft.uuid">
                            <option :value="draft.uuid" x-text="`作業日: ${draft.work_date} | 作物名: ${draft.crop_name} | 作業名: ${draft.title || '未記入'}`"></option>
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
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2"
                            role="alert">
                        </span>

                    </div>

                    <div x-text="getError('crop_season_id')"></div>

                    {{-- 作業名称 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="title" class="form-label sm:col-span-2 font-semibold text-lg">作業名称</label>
                        <input type="text" x-model="formData.title" name="title" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="（例）防除１回目">
                        {{-- バリデーションメッセージ --}}
                        <span
                            x-text="getError('title')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>
                        <span x-text="`${formData.title}`"></span>
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
                        {{-- バリデーションメッセージ --}}
                        <span
                            x-text="getError('performed_by')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>
                    </div>

                    {{-- 作業内容 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <label for="content" class="form-label font-semibold text-lg">作業内容</label>
                        <textarea type="text" x-model="formData.content" name="content" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" placeholder="作業した内容を記入してください。">防除１１回目　ストロビーフロアブル
                        </textarea>
                        {{-- 内容のテンプレートを作成する？ --}}
                        {{-- バリデーションメッセージ --}}
                        <span x-show="getError('content')"
                            x-text="getError('content')"
                            class="alert alert-danger sm:col-span-2 text-sm text-red-500 font-semibold px-2" role="alert">
                        </span>
                    </div>

                    {{-- 使用資材記録 --}}
                    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
                        <div class="form-label mb-1 font-semibold text-lg">資材の記録</div>

                        <div class="material_logs_inner">
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
                                class="mt-1 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                資材を追加する
                            </button>

                            {{-- 動的フォーム --}}
                            <template x-for="(material_log, index) in formData.material_logs" :key="material_log.uuid">
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
                                        <span x-show="getError('quantity', material_log.uuid)"
                                            x-text="getError('quantity', material_log.uuid)"
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
                                        <span x-show="getError('dilution_rate', material_log.uuid)"
                                            x-text="getError('dilution_rate', material_log.uuid)"
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
                                        <span x-show="getError('material_amount', material_log.uuid)"
                                            x-text="getError('material_amount', material_log.uuid)"
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
                    uuid: crypto.randomUUID(),
                    draft_uuid: '',
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

                // isOnline: window.navigator.onLine,
                isOnline: false,

                selectedDraftUuid: '',

                errors: {},
                mappedErrors: {},

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
                            // id: 'uuid' + Date.now() + '-' + Math.random().toString(36),substr(2,9)
                            uuid: crypto.randomUUID(),
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
                    // 削除対象の行を取得
                    const targetMaterialLogs = this.material_logs[index];

                    // 該当の動的フォームの行を削除
                    this.formData.material_logs.splice(index, 1);

                    // 削除された行のUUIDに紐づくエラーを削除する
                    if (targetMaterialLogs && this.mappedErrors[targetMaterialLogs.uuid]) {
                        delete this.mappedErrors[targetMaterialLogs.uuid];
                    }
                },

                // 登録資材重複の確認
                isDuplicated(materialId) {
                    // エラーチェック
                    if (this.formData.material_logs?.length == 0) {
                        console.info(`[in isDuplicated()] 資材の入力が０件です(no issue)`, { materialId, material_logs: this.formData.material_logs });
                        return '';
                    } else if (!Array.isArray(this.formData.material_logs)) {
                        console.error(`[in isDuplicated()] this.formData.material_logsの参照に失敗しました。`, { materialId, material_logs: this.formData.material_logs });
                        return '';
                    }

                    // メインロジック 重複の確認
                    if (this.formData.material_logs?.some(material => material.material_id == materialId)) {
                        return '** 登録済みです **';
                    }
                    return '';
                },

                /////
                // fetch()送信
                //  主に下書き機能実装のため

                // localStorageに下書きが保存されている場合は取得 or []で初期化
                draft_work_log: JSON.parse(localStorage.getItem('draft_work_log') || '[]'),

                // post送信時に呼び出される
                // @submit.preventで呼び出し
                async submitForm() {

                    if (!this.isOnline) {
                        this.saveToLocalStorage();
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

                        // ----------------------------------------------------
                        // 1. バリデーションエラー (422) のハンドリング
                        // ----------------------------------------------------
                        if (response.status === 422) {
                            const data = await response.json();
                            // エラーを受け取りUUIDを付与
                            if (data.errors) {
                                this.insertUuidToErrors(data.errors);
                            }

                            alert('保存に失敗しました： ' + (data.message || '入力内容を確認してください。'));
                            return;
                        }
                        // ----------------------------------------------------
                        // 2. その他のサーバーエラー (500系や404など
                        // ----------------------------------------------------
                        if (!response.ok) {
                            console.error('サーバーエラーが発生しました。Status:', response.status);
                            alert('サーバーエラーが発生しました。（Status: ' + response.status + '）');

                            throw new Error('Server Error: ' + response.status);
                        }

                        // ----------------------------------------------------
                        // 3. 保存成功処理 (200 OK系)
                        // ----------------------------------------------------
                        const data = await response.json(); // 成功レスポンスのJSONを解析

                        alert(data.message || '保存しました。');

                        // 下書きの続きならlocalstorageの該当の記録を削除
                        this.removeDraftRow();

                        // コントローラから帰ってきたURLへリダイレクト
                        window.location.href = data.redirect_url;
                    } catch (error) {
                        clearTimeout(timeoutId); // 念のためにタイマーを解除

                        // デバッグ用にエラーの詳細を出力
                        if (error.name === 'AbortError') {
                            console.error('通信エラー：　タイムアウト（５秒）が発生しました。', error);
                        } else {
                            console.error('通信に失敗たため、ローカル保存にフォールバックします。', error);
                        }

                        // 通信エラー、タイムアウトなど通信によるエラーの場合はLocalStorageに退避
                        this.saveToLocalStorage();
                    }
                },

                insertUuidToErrors(rawErrors) {
                    this.mappedErrors = {};

                    Object.keys(rawErrors).forEach(key => {
                        // 動的フォーム配列material_logsを持つキーからindex数字とフィールド名を抽出
                        const match = key.match(/^material_logs\.(\d+)\.(.+)$/);
                        if (match) {

                            const index = parseInt(match[1]); // マッチグループ2行目
                            const fieldName = match[2];

                            if (this.formData.material_logs && this.formData.material_logs[index]) {
                                const rowId = this.formData.material_logs[index].uuid;

                                if (!this.mappedErrors[rowId]) {
                                    this.mappedErrors[rowId] = {};
                                }
                                // UUIDに対応してエラーメッセージを管理
                                this.mappedErrors[rowId][fieldName] = rawErrors[key][0];
                            }
                        } else {
                            // material_logs以外の通常属性のエラーもそのまま保持
                            this.mappedErrors[key] = rawErrors[key][0];
                        }
                    });
                },

                // バリデーションエラーメッセージを返す: null or String
                getError(field, rowId = null) {
                    if (rowId === null) {
                        return this.mappedErrors?.[field] || null;
                    } else {
                        return this.mappedErrors?.[rowId]?.[field] || null;
                    }
                },

                ////////
                // ----------------------------------------------------
                // 下書き機能
                // ----------------------------------------------------
                get hasDraft() {
                    const rawDraft = this.draft_work_log;
                    if(!rawDraft || rawDraft.length === 0) {
                        return false;
                    }

                    const remapDraft = rawDraft.map((log) => ({
                        ...log,
                        // bladeの下書きリストように作物名を取得
                        crop_name: (() => {
                            if (!Array.isArray(this.allCropSeasons)) {
                                console.error(`this.allCropSeasonsの参照に失敗しました。`, { crop_season_id: log.crop_season_id - 1, allCropSeasons: this.allCropSeasons });
                                return '（不明）';
                            }
                            if (!this.allCropSeasons?.[log.crop_season_id - 1]?.crops.name) {
                                console.warn(`[in hasDraft] log.crop_season_id - 1 の値が不正です。未選択が代入されます。`, { value: log.crop_season_id - 1, allCropSeasons: this.allCropSeasons });
                            }
                            return this.allCropSeasons?.[log.crop_season_id - 1]?.crops.name || '未選択';
                        })(),
                    }));
                    return remapDraft;
                },

                // mytest() {
                //     let myFav =[
                //         {name: 'リンゴ'},
                //         {name: 'バス'},
                //         {name: 'ライオン'},
                //     ];
                //     console.log(myFav);
                //     console.log(myFav[0].name);
                //     myFav = myFav.filter(item => item.name !== 'バス');
                //     console.log(myFav);
                // },

                // // Localstorage保存ロジック
                saveToLocalStorage() {
                    this.draft_work_log.push(JSON.parse(JSON.stringify(this.formData)));
                    localStorage.setItem('draft_work_log', JSON.stringify(this.draft_work_log));
                    alert('オフラインのためブラウザに一時保存しました。(localStorage)');

                    this.formData = {
                        uuid: '',
                        draft_uuid: '',
                        crop_season_id: '',
                        created_by: 0,
                        performed_by: '',
                        work_date: this.getToday,
                        status: '',
                        title: '',
                        content: '',
                        updated_by: '',
                        material_logs: [],
                    };
                },

                // 下書きLocalstorageの削除
                removeDraftRow(targetDraftUuid = this.formData?.draft_uuid) {
                    // post送信するformDataが下書きの続きだったかどうか
                    if (!!targetDraftUuid) {
                        console.error(`下書きのつづきではないか(no issue)、this.formData.draft_uuidの取得に失敗しました。`, { draft_uuid: targetDraftUuid });
                        return;
                    }
                    // 削除ロジック
                    const filteredDrafts = this.draft_work_log.filter(log => log.uuid !== targetDraftUuid);
                    // filter結果の例外処理:主にuuidの全件不一致
                    if (!filteredDrafts) {
                        console.error(`this.draft_work_log.filter()の処理に失敗しました`, { targetDraftUuid: this.formData.draft_uuid, draft_work_log: log });
                        return;
                    }
                    localStorage.setItem('draft_work_log', JSON.stringify(filteredDrafts));
                },
                removeDraftAll() {
                    localStorage.setItem('draft_work_log');
                    alert('オフライン下書きをすべて削除しました。');
                },


                // 選択した下書きformDataを現在のフォームに流し込む
                fillWithDraft() {
                    if (!Array.isArray(this.hasDraft)) {
                        console.error(`this.hasDraftの参照に失敗しました。`, { selectedDraftUuid: this.selectedDraftUuid, hasDraft: this.hasDraft });
                        return '';
                    }
                    // メインロジック レンダに使われているformDataに下書きを流し込む
                    this.formData = {
                        ...this.hasDraft.find(draft => draft.uuid === this.selectedDraftUuid),
                        draft_uuid: this.selectedDraftUuid,
                    };
                },



                // // 下書きの途中で新規の作業入力に切り替えてしまったとき
                // skipDraft() {
                //     this.isDraft = false;
                // }
            }));
        });

    </script>
</body>
</html>
