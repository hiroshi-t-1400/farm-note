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

                <div class="grid grid-cols-3">
                    <div class="col-start-3 border border-md border-blue-800">
                        <span>現在のネットワーク：</span><span x-text="showOnlineStatus"></span>
                        <button type="button" @click="toggleOnline()" class="rounded-md border border-md bg-gray-600 text-white block">切り替え</button>
                    </div>
                </div>

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
                        <option value="">-- 選択して下書きを確認してください。（<span x-text="draftWorkLog.length"></span>件） --</option>
                        <template x-for="(draft, index) in draftWorkLog" :key="draft.draft_uuid">
                            <option :value="draft.draft_uuid" x-text="`作業日: ${draft.formData.work_date} | 作物名: ${draft.formData.crop_name || '未選択'} | 作業名: ${draft.formData.title || '未記入'}`"></option>
                        </template>
                    </select>
                    <button
                        type="button"
                        @click="fillWithDraft()"
                        class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-blue-300 items-center text-sm font-medium text-white"
                        >
                        下書きを読み込む
                    </button>
                    <button
                        type="button"
                        @click="deleteSelectedDraft()"
                        :disabled="!selectedDraftUuid"
                        :class="{ 'opacity-50 cursor-not-allowed': !selectedDraftUuid }"
                        class="my-1 px-2 py-1 rounded-md border border-gray-500 bg-red-300 items-center text-sm font-medium text-white"
                        >
                        選択した下書きを削除する
                    </button>
                </div>

                <div class="input-form-inner ">
                    {{-- 作物選択 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1 bg-white mb-1 px-1 py-2" >
                        <label for="crop_season_id" class="form-label sm:col-span-2 font-semibold text-lg">作業した作物</label>
                        <select x-model="formData.crop_season_id" @change="changeCropSeasons()" name="crop_season_id" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="crop_season_id">
                            <option value="">作物を選択</option>
                            <template x-for="cropSeason in allCropSeasons" :key="cropSeason.id">
                                <option :value="cropSeason.id" x-text="cropSeason.crop_season_nameYear"></option>
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

                            {{-- <template>

                            </template> --}}
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
                                @click="addMaterialLogs()"
                                class="mt-1 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                資材を追加する
                            </button>

                            {{-- 動的フォーム --}}
                            <template x-for="(material_log, index) in formData.material_logs" :key="material_log.addForm_uuid">
                                <div class="grid sm:grid-cols-2 rounded-md border border-gray-200 text-sm">
                                    <div class="">
                                        <span class="" x-text="'資材' + (index + 1)"></span>
                                        <button
                                            type="button"
                                            @click="removeMaterial_log(addForm_uuid)"
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
            Alpine.data('postForm', (config) => {

                // 警告のIDやメッセージを記録する集合
                /**
                 * updateメソッドの呼び出し
                 * １，ユーザーが画面上で操作を行ったとき（イベントハンドラ）
                 * ２，API通信で新しいデータを取得・送信した直後
                 * ３，Alpine.data内の初期化処理
                 */
                let lastVersion = null;
                const warnedKeys = new Set();
                // let isDraft = false;

                // if (lastVersion !== this.version) {
                //     warnedKeys.clear();
                //     lastVersion = this.version;
                // }


                const DRAFT_LOG = 'farm-note:work-logs:draft-work-log';

                return {
                    // 状態変化カウント
                    version: 1,

                    formData: {},

                    allMaterials: config.initialMaterials,
                    types: config.initialTypes,
                    allCropSeasons: config.initialCropSeasons,
                    allCrops: config.initialCrops,
                    selectedType: '',
                    selectedMaterialId: '',

                    // isOnline: window.navigator.onLine,
                    // isOnline: false,
                    isOnline: '',

                    allDrafts: '',
                    selectedDraftUuid: '',

                    errors: {},
                    mappedErrors: {},

                    draftWorkLog: [],

                    init() {
                        this.formData.work_date = this.getToday;
                        this.resetFormData();
                        this.remapCropSeasons();
                        this.initDraftWorkLog();

                        //debug
                        this.getOnlineStatus;
                    },

                    getDefaultFormData() {
                        return {
                            formData_uuid: crypto.randomUUID(),
                            draft_uuid: '',
                            crop_name: '不明',

                            crop_season_id: '',
                            crop_season_nameYear: '不明',
                            created_by: 1,
                            performed_by: '',
                            work_date: this.getToday,
                            status: false,
                            title: '',
                            content: '',
                            updated_by: '',
                            material_logs: []
                        };
                    },

                    resetFormData() {
                        const newFormData = this.getDefaultFormData();

                        this.formData = newFormData;
                    },

                    // 楽観ロックによるバージョン管理、不要そうになったので保留
                    // updateData(key, value) {
                    //     this[key] = value;
                    //     this.version++;
                    // },

                    get getToday() {
                        const today = new Date();

                        const yyyy = today.getFullYear();
                        const mm = String(today.getMonth() + 1).padStart(2, '0');
                        const dd = String(today.getDate()).padStart(2, '0');
                        return `${yyyy}-${mm}-${dd}`;
                    },

                    // init():作物の名称をオブジェクトの上の階層に挿入して配列を使いやすくする
                    remapCropSeasons() {
                        const remapArray = this.allCropSeasons.map((season, index) => ({
                            ...season,
                            id: index + 1,
                            crop_name: season.crops.name,
                            crop_season_nameYear: season.crops.name + season.year,
                        }));
                        this.allCropSeasons = remapArray;
                    },

                    // ----------------------------------------------------
                    // ここまで初期化
                    // ----------------------------------------------------

                    // 作物表示用metaデータの生成と格納
                    changeCropSeasons() {
                        const targetId = this.formData.crop_season_id;

                        const newCropSeason = this.getCropSeasonByCropSeasonId(targetId);
                        if (!newCropSeason) {
                            console.trace('[changeCropSeasons] 取得したオブジェクトが不正です。', { newCropSeason: newCropSeason, 'targetId':targetId })
                            return;
                        }

                        const getName = newCropSeason.crop_name;
                        const getNameYear = newCropSeason.crop_season_nameYear;

                        this.formData.crop_name = newCropSeason.crop_name;
                        this.formData.crop_season_nameYear = newCropSeason.crop_season_nameYear;
                    },

                    // crop_season_idから作物名を取得する
                    getCropSeasonByCropSeasonId(targetId) {
                        targetId = parseInt(targetId) || '';
                        if (!targetId || targetId <= 0 ) {
                            console.error('[getCropsName] targetIdが不正な値です。', { targetId: targetId })
                            return null;
                        }
                        const foundObject = this.allCropSeasons.find(({ id }) => id == targetId);
                        return foundObject;
                    },

                    // crop_season_idから作物名を取得する
                    getCropNameByCropSeasonId(targetId) {
                        targetId = parseInt(targetId) || '';
                        if (!targetId || targetId <= 0 ) {
                            console.error('[getCropsName] targetIdが不正な値です。', { targetId: targetId })
                            return null;
                        }
                        const foundObject = this.allCropSeasons.find(({ id }) => id == targetId);
                        return foundObject.crop_name;
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
                        return this.allMaterials.find(m => m.id == this.selectedMaterialId) || null;
                    },

                    // 材料の追加ロジック
                    addMaterialLogs() {
                        if (!this.selectedMaterial) return;
                        // 追加フォームの初期化
                        const newMaterialLog = this.initAddForm(this.selectedMaterial);

                        this.formData.material_logs.push(newMaterialLog);
                        console.warn('pushのあと', {material_logs: this.formData.material_logs, selectMaterial: this.selectedMaterial});
                        // 追加したら選択欄をリセット
                        this.selectedMaterialId = '';
                    },

                    // 資材追加フォームの初期化メソッド
                    initAddForm(master) {
                        return {
                            addForm_uuid: crypto.randomUUID(),
                            type_label: master.material_categories.label,

                            material_id: master.id,
                            name: master.name,
                            type_id: master.type_id,
                            dilution_rate: master.default_dilution_rate,
                            quantity: master.standard_spray_volume,
                            material_amount: '',
                            manufacturer: master.manufacturer
                        };
                    },

                    // 資材フォームの削除ロジック
                    removeMaterial_log(uuid) {
                        this.formData.material_logs = this.formData.material_logs.filter(
                            log => log.addForm_uuid !== uuid
                        );

                        // 対応するエラー・メッセージを持っていたら削除
                        if (!mappedErrors[uuid]) {
                            delete this.mappedErrors[uuid];
                        }
                    },

                    // 登録資材重複の確認
                    isDuplicated(materialId) {
                        // エラーチェック
                        if (this.formData.material_logs?.length == 0) {
                            if (!warnedKeys.has('isDuplicated_no_material_warn')) {
                                console.info(`[in isDuplicated()] 資材の入力が０件です(no issue)`, { materialId, material_logs: this.formData.material_logs });
                                warnedKeys.add('isDuplicated_no_material_warn');
                            }
                            return '';
                        } else if (!Array.isArray(this.formData.material_logs)) {
                            if (!warnedKeys.has('isDuplicated_reference_isArray_error')) {
                                console.error(`[in isDuplicated()] this.formData.material_logsの参照に失敗しました。`, { materialId, material_logs: this.formData.material_logs });
                                warnedKeys.add('isDuplicated_reference_isArray_error');
                            }
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

                    // @submit.preventで呼び出し
                    async submitForm() {
                        // post送信および下書き保存用にパッケージされたformDataを取得
                        const buildWorkLog = this.buildWorkLogPayload();

                        if (!this.isOnline) {
                            this.saveToLocalStorage(buildWorkLog);
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
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                // body: JSON.stringify(this.formData),
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

                            this.resetFormData();
                            alert(data.message || '保存しました。');

                            // 下書きの続きならlocalstorageの該当の記録を削除
                            this.clearSubmittedDraft();

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
                            this.saveToLocalStorage(packedWorkLog);
                        }
                    },

                    insertUuidToErrors(rawErrors) {
                        this.mappedErrors = {};

                        Object.keys(rawErrors).forEach(key => {
                            // 動的フォーム配列material_logsを持つキーからindex数字とフィールド名を抽出
                            const match = key.match(/^material_logs\.(\d+)\.(.+)$/);
                            // material_logsに関するエラーがあるか
                            if (match) {

                                const index = parseInt(match[1]); // マッチグループ2行目
                                const fieldName = match[2];

                                if (this.formData.material_logs && this.formData.material_logs[index]) {
                                    const rowId = this.formData.material_logs[index].addForm_uuid;

                                    // 初期化
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
                    hasDraft() {
                        console.log('changeCrop 配列の数', { 'draftWorkLog.length': this.draftWorkLog.length });

                        return this.draftWorkLog.length || null;
                    },

                    // コンポーネントもつ下書きデータdraftWorkLogを初期化し、localStorageのデータを格納する
                    initDraftWorkLog() {
                        this.draftWorkLog = [];
                        this.loadDrafts();
                    },

                    /**
                     * localStorageの下書きを読み込み、変形、変数へ出力する
                     * 初期化作業
                     */
                    loadDrafts() {
                        const rawDrafts = localStorage.getItem('DRAFT_LOG'); // キーが無くてもreturn null
                        if (!rawDrafts) {
                            console.info('[_getStrageDRAFT_LOG] LocalStorageにDRAFT_LOGがありませんでした。(no issue)', { rawDrafts: rawDrafts });
                            return;
                        }

                        const parsedRawDrafts = this._parseRawDrafts(rawDrafts);

                        this.draftWorkLog = [ ...parsedRawDrafts ];
                    },

                    /**
                     *
                     */
                    _parseRawDrafts(rawDrafts) {
                        const newConstructDrafts = [];

                        try {
                            const newParsedDrafts = JSON.parse(rawDrafts);

                            for (const { draft_uuid, saved_at, meta, formData } of newParsedDrafts ) {
                                // draft_uuidが存在しない場合は即座に例外を発生させて処理を打ち切る
                                if (!draft_uuid) {
                                    // throw new Error('UUIDが存在しない要素が含まれています');
                                    throw {name: 'Exist broken Draft', message:'UUIDが存在しない要素が含まれています'};
                                }

                                // 下書きをフォームに流し込むまでmetaデータを上層に配置、非正規化
                                newConstructDrafts.push({
                                    saved_at: saved_at,
                                    draft_uuid: draft_uuid,
                                    formData: {
                                        ...formData,
                                        draft_uuid: draft_uuid,
                                        crop_name: meta?.crop_name || '未選択',
                                        crop_season_nameYear: meta?.crop_season_nameYear
                                    }
                                });
                            }
                        } catch (e) {
                            // ほぼ確実にLocalStorageの下書きが破損しているため、LocalStorageの下書きを削除
                            // これはリリース前のデバッグ用、キャンセルでLocalStorageの内容を取得するため
                            if (confirm('エラー：ブラウザの下書きデータが破損しています。下書きデータをすべて削除して初期化しますか？。\n※削除しても連続してエラーが発生する場合は管理者に連絡してください。\n＊＊＊＊\nキャンセルを選択しても下書きデータの読み込みはできません。\n削除するまで下書きデータの保存はできません。\n＊＊＊＊')) {
                                this.removeDraftAll();
                            }
                            // これが本番用、
                            // this.removeDraftAll();
                            // alert('下書きデータが破損しています。下書きデータを削除しました。');
                            // alert('エラー：ブラウザの下書きデータが破損しています。\n＊＊＊＊\nキャンセルを選択しても下書きデータの読み込みはできません。\n削除するまで下書きデータの保存はできません。\n※削除しても連続してエラーが発生する場合は管理者に連絡してください。＊＊＊＊');

                            if (e instanceof SyntaxError) {
                                console.error(`'[_parseRawDrafts JSON.parse(rawDrafts)] Exist broken Draft. SyntaxError ${e.message}'`, { rawDrafts: rawDrafts });
                                return;
                            } else {
                                console.error(`'[_parseRawDrafts] Exist broken Draft. ${e.message}'`, { newConstructDrafts: newConstructDrafts });
                                return;
                            }
                        }
                        return newConstructDrafts;
                    },

                    /**
                     *
                     * @function
                     * @param void
                     * @returns {Array | null} rawData
                     *
                     */
                    get _getStorageDRAFT_LOG() {
                        const rawData = localStorage.getItem('DRAFT_LOG'); // キーが無くてもreturn null
                        if (!rawData) {
                            console.info('[_getStrageDRAFT_LOG] LocalStorageにDRAFT_LOGがありませんでした。(no issue)', { rawData: rawData });
                            return null;
                        }
                        console.log('下書き生データ', JSON.parse(rawData));
                        return JSON.parse(rawData);
                    },

                    // Localstorage保存ロジック
                    saveToLocalStorage(getPayload) {
                        try {
                            let tempPayloads = this.draftWorkLog || [];

                            // push予定の持つdraft_uuidと同じdraft_uuidをstateに持つコンポーネント上の下書きデータストックから排除する
                            if (tempPayloads.length > 0) {
                                tempPayloads = tempPayloads.filter(p => p.draft_uuid !== getPayload.draft_uuid);
                            }
                            tempPayloads.push(JSON.parse(JSON.stringify(getPayload)));
                            localStorage.setItem('DRAFT_LOG', JSON.stringify(tempPayloads));
                        } catch(e) {
                            console.group('[saveToLocalStorage] 下書きの保存に失敗しました。');
                                console.info('実行メソッド: JSON.stringify(getPayload)');
                                console.error(`'${e.name}: ${e.message}\n保存予定のデータ'`, { getPayload: getPayload });
                            console.groupEnd();
                            // ユーザにリトライさせてなお失敗するのであればLocalStrageの下書きを削除して
                            alert('エラー：下書きに失敗しました。ブラウザを閉じてからやり直してください。\n※連続してエラーが発生する場合は管理者に連絡してください。');
                            return;
                        }

                        alert('オフラインのためブラウザに一時保存しました。(localStorage)');

                        this.resetFormData();
                    },

                    // 保存用にpayloadとmetaデータなどに構造分解する
                    buildWorkLogPayload() {
                        const { formData_uuid, draft_uuid, crop_name, crop_season_nameYear, ...payload } = this.formData;
                        return {
                            draft_uuid: this.formData.draft_uuid || crypto.randomUUID(),
                            saved_at: new Date().toISOString(),
                            meta:{
                                crop_name: crop_name,
                                crop_season_nameYear: crop_season_nameYear
                            },
                            formData: { ...payload , crop_name}
                        };
                    },

                    // ----------------------------------------------------
                    // 下書きの削除
                    // ----------------------------------------------------
                    // 選択中の１件をそのまま削除ボタン
                    // ----------------------------------------------------
                    deleteSelectedDraft() {
                        if (!this.selectedDraftUuid) return;

                        if (confirm('選択した下書きを削除してもよろしいですか？')) {
                            // コア削除処理を呼び出す
                            this._deleteDraftByUuid(this.selectedDraftUuid);

                            this.selectedDraftUuid = '';
                            // this.updateData('selectedDraftUuid', '');
                        }
                    },

                    // ----------------------------------------------------
                    // 読み込んだ下書きのポストが成功した際に該当の下書きを１件削除
                    // ----------------------------------------------------
                    clearSubmittedDraft() {
                        // formDataが下書きである場合にのみ実行
                        if (this.formData.draft_uuid) {
                            // コア削除処理を呼び出す
                            this._deleteDraftByUuid(this.formData.draft_uuid);


                            // this.updateData('formData.draft_uuid', null);
                            this.formData.draft_uuid = '';
                        }
                    },

                    // ----------------------------------------------------
                    // コア関数 UUIDを受け取り localStorage/配列から削除するだけ
                    // ----------------------------------------------------
                    _deleteDraftByUuid(uuid) {
                        this.draftWorkLog = this.draftWorkLog.filter(log => log.draft_uuid !== uuid);
                        localStorage.setItem('DRAFT_LOG', JSON.stringify(this.draftWorkLog));

                        this.initDraftWorkLog();
                    },

                    // localStorageの下書きを全件削除
                    removeDraftAll() {
                        // if (confirm('＊＊全件削除＊＊ 全ての下書きを削除してもよろしいですか？')){

                            localStorage.removeItem('DRAFT_LOG');
                            this.initDraftWorkLog();
                            // alert('オフライン下書きをすべて削除しました。');
                        // }
                    },

                    /**
                     * 読み込むボタンから呼び出し
                     */
                    fillWithDraft() {
                        // hasDraftがfalseならボタンも表示されないのであり得ないアクセス
                        if (!this.hasDraft) {
                            console.error(`[fillWithDraft] 不正なアクセス`);
                            return;
                        }
                        // 選択した下書きデータ１件を取得
                        const selectedDraft = this.draftWorkLog.find(draft => draft.draft_uuid === this.selectedDraftUuid);
                        const draftFormData = { ...selectedDraft?.formData };
                        if (!draftFormData) {
                            console.error(`[fillWithDraft] 下書きデータが破損しています。`, { draftWorkLog:this.draftWorkLog, selectedDraftUuid: this.selectedDraftUuid });

                            alert('下書きの読み込みに失敗しました。ブラウザを閉じてからやり直してください。');
                            return;
                        }

                        this.formData = draftFormData;
                    },


                    // 下書きの上書き例外処理
                    //
                    // isOnline: false かつ


                    // // 下書きの途中で新規の作業入力に切り替えてしまったとき
                    // skipDraft() {
                    //     this.isDraft = false;
                    // }



                // debug
                    toggleOnline() {
                        if (this.isOnline) {
                            this.isOnline = false;
                            return this.saveOnlineFlag();
                        }
                        this.isOnline = true;
                        return this.saveOnlineFlag();
                    },

                    saveOnlineFlag() {
                        let flag = this.isOnline;
                        localStorage.setItem('ONLINE_FLAG', JSON.stringify(flag));
                    },

                    get getOnlineStatus() {
                        const savedFlag = JSON.parse(localStorage.getItem('ONLINE_FLAG'));
                        console.warn('[getOnlineStatus] savedFlag', {savedFlag:savedFlag});
                        if (savedFlag === null) {
                        console.warn('[getOnlineStatus] savedFlag===null', {savedFlag:savedFlag});

                            return this.isOnline = window.navigator.onLine;
                        }
                        return this.isOnline = savedFlag;

                    },

                    get showOnlineStatus() {
                        if (this.isOnline) return 'オン';
                        return 'オフ';
                    },
                };
            });
        });

    </script>
</body>
</html>
