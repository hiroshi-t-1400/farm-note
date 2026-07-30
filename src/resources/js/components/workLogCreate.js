export default (config) => {

        const DRAFT_LOG = 'farm-note:work-logs:draft-work-log';

        const warnedKeys = new Set();

        return {
            formData: {},

            allMaterials: config.initialMaterials,
            types: config.initialTypes,
            allCropSeasons: config.initialCropSeasons,
            allCrops: config.initialCrops,
            allUsers: config.initialUsers,
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
                // formDataからmetaデータとpayloadを分離し構造化したrecordを受け取る
                let record = {};
                try{
                    record = this.buildRecord(this.formData);
                } catch(e) {
                    console.error(e.message);
                    alert('送信データに異常があります。ブラウザを閉じてやり直してください。連続してエラーが発生する場合は管理者に連絡してください。');

                    resetFormData();
                    initDraftWorkLog();
                    return;
                }

                // オフラインならLocalstorageに保存して退避
                if (!this.isOnline) {
                    this.saveToLocalStorage(record);
                    return;
                }

                // オンライン時処理
                let timeoutId = null;

                try {
                    const controller = new AbortController();
                    timeoutId = setTimeout(() => controller.abort(), 5000); // 5000ms to timeout

                    // オンライン時の処理、fetch()でJSONを送信
                    const response = await fetch('/work-logs/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(record.formData),
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

                        // throw new Error('Server Error: ' + response.status);
                        return;
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

                    if (timeoutId) clearTimeout(timeoutId); // 念のためにタイマーを解除

                    // デバッグ用にエラーの詳細を出力
                    if (error.name === 'AbortError') {
                        console.error('通信エラー：　タイムアウト（５秒）が発生しました。', error);
                    } else {
                        console.error('通信に失敗たため、ローカル保存にフォールバックします。', error);
                    }

                    // 通信エラー、タイムアウトなど通信によるエラーの場合はLocalStorageに退避
                    this.saveToLocalStorage(record);
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
            getError(field, uuid = '') {
                if (uuid === '') {
                    return this.mappedErrors?.[field] || null;
                } else {
                    return this.mappedErrors?.[uuid]?.[field] || null;
                }
            },

            ////////
            // ----------------------------------------------------
            // 下書き機能
            // ----------------------------------------------------
            hasDraft() {

                return this.draftWorkLog.length || null;
            },

            // コンポーネントがもつ下書きデータdraftWorkLogを初期化し、localStorageのデータを格納する
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
                this.draftWorkLog = [...parsedRawDrafts];
            },

            /**
             *
             */
            _parseRawDrafts(rawDrafts) {
                const newConstructDrafts = [];

                try {
                    const newParsedDrafts = JSON.parse(rawDrafts);
                    for (let {draft_uuid, saved_at, meta, formData} of newParsedDrafts ) {
                        // draft_uuidが存在しない場合は即座に例外を発生させて処理を打ち切る
                        if (!draft_uuid) {
                            // throw new Error('UUIDが存在しない要素が含まれています');
                            throw {name: 'Exist broken Draft', message:'UUIDが存在しない要素が含まれています'};
                        }

                        // 下書きをフォームに流し込むまでmetaデータを上層に配置、非正規化
                        newConstructDrafts.push({
                            saved_at: saved_at,
                            draft_uuid: draft_uuid,
                            crop_name: meta?.crop_name || '未選択',

                            formData: {
                                ...formData,
                                saved_at: saved_at,
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
                return JSON.parse(rawData);
            },

            // Localstorage保存ロジック
            saveToLocalStorage(newRecord) {
                const tempRecords = [];
                try {

                    const oldRecords = this.draftWorkLog || [];

                    for (const old of oldRecords) {
                        tempRecords.push(this.buildRecord(old.formData));
                    };
                    // 上書き処理 or push
                    const deleteIndex = tempRecords.findIndex(p => p.draft_uuid == newRecord.draft_uuid);
                    if (deleteIndex !== -1) {
                        tempRecords[deleteIndex] = newRecord;
                        tempRecords[deleteIndex].saved_at = new Date().toISOString;
                    } else {
                        tempRecords.push(JSON.parse(JSON.stringify(newRecord)));
                    }
                    localStorage.setItem('DRAFT_LOG', JSON.stringify(tempRecords));

                    alert('オフラインのためブラウザに一時保存しました。(localStorage)');
                    this.resetFormData();

                } catch(e) {
                    console.group('[saveToLocalStorage] 下書きの保存に失敗しました。');
                        console.info('実行メソッド: JSON.stringify(newRecord)');
                        console.error(`'${e.name}: ${e.message}\n保存予定のデータ'`, { newRecord: newRecord });
                        console.error('コンポーネント上の下書きデータ', { 'oldRecords = this.draftWorkLog': this.draftWorkLog });
                    console.groupEnd();
                    // ユーザにリトライさせてなお失敗するのであれば、端末やブラウザの種類・バージョンによる違い？
                    alert('エラー：下書きに失敗しました。ブラウザを閉じてからやり直してください。\n※連続してエラーが発生する場合は管理者に連絡してください。');
                    return;
                } finally {
                    // 下書きを読み込んで初期化
                    this.initDraftWorkLog()
                }
            },

            // 保存用にpayloadとmetaデータなどに構造分解する
            // 新規１件および下書きストックがある場合はそれらのformDataもビルドする
            /**
             *
             * @param {Object}
             */
            buildRecord(formData) {
                // オブジェクトの型によるバリデーション
                if (!formData || typeof formData !== 'object') {
                    throw new Error('formDataの型が一致しませんでした。');
                }

                let { formData_uuid, draft_uuid, saved_at, crop_name, crop_season_nameYear, ...payload } = formData;
                return {
                    draft_uuid: draft_uuid || crypto.randomUUID(),
                    saved_at: saved_at || new Date().toISOString(),
                    meta:{
                        crop_name: crop_name,
                        crop_season_nameYear: crop_season_nameYear
                    },
                    formData: { ...payload , crop_name }
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
        }
    }
