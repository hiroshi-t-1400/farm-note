// src/resources/js/components/modules/work-logs/form-logic.js

import { generateUUID, tsToDate } from "./utils";


export function storeFormLogic (initialData = {}) {

    const {
        cropSeasons: allCropSeasons = {},
        users: allUsers = {},
        materials: allMaterials = {},
        matTypes: matTypes = {},
        workLog: workLog = {}
    } = initialData || {};

    const DRAFT_LOG = 'farm-note:work-logs:draft-work-log';

    const warnedKeys = new Set();



        return {

            allUsers,
            allMaterials,
            allCropSeasons,
            matTypes,
            createdBy: '',

            formData: {},

            selectedType: '',
            selectedMaterialId: '',

            // リリース時にアクティブ、submitのオンライン判定
            // isOnline: window.navigator.onLine,
            // isOnline: false,

            allDrafts: '',
            selectedDraftUuid: '',

            errors: {},
            mappedErrors: {},

            draftWorkLog: [],

            // 呼び出し元が編集ページか
            isEdit: workLog?.id > -1 ? true : false,

            // レスポンシブ対応のため
            windowWidth: window.innerWidth,
            isMobile() {return this.windowWidth < 768;},

            init() {
                if (!this.isEdit) this.resetFormData();
                //debug
                this.getOnlineStatus;
                this.initDraftWorkLog();
            },

            resetFormData() {
                const newFormData = this.getDefaultFormData();
                this.formData = newFormData;
            },

            getDefaultFormData() {
                return {
                    // formData_uuid: crypto.randomUUID(),
                    formDataUuid: generateUUID(),
                    draftUuid: '',
                    cropName: '不明',

                    cropSeasonId: '',
                    cropSeasonsNameYear: '不明',
                    createdBy: '',
                    performedBy: [{}], // 暫定措置
                    workDate: tsToDate(Date()),
                    status: false,
                    title: '',
                    content: '',
                    updatedBy: '',
                    materialLogs: []
                };
            },

            // ----------------------------------------------------
            // ここまで初期化
            // ----------------------------------------------------

            async getAuthor() {
                try {
                    const response = await window.http.get('/user');
                    this.setUser(response.data);
                    // console.log({'受け取ったuser': this.user});
                } catch (error) {
                    // 401未認証などの場合は null をセット
                    this.setUser(null);
                } finally {
                    this.loading = false;
                }
            },

            changeCropSeasons() {
                const id = this.formData.cropSeasonId;
                this.formData.cropSeasonsNameYear = this.allCropSeasons?.find(cs => cs.id == id).cropSeasonsNameYear;
                this.formData.cropName = this.allCropSeasons?.find(cs => cs.id == id).cropName;

            },

            // 選択された種別から資材選択を助ける
            filteredMaterials() {
                return this.allMaterials.filter(m => {
                    const matchType = this.selectedType === '' || m.typeId == this.selectedType;
                    return matchType;
                });
            },

            // 選択された資材の情報を取得する
            selectedMaterial() {
                return this.allMaterials.find(m => m.id == this.selectedMaterialId) || null;
            },

            // 材料の追加ロジック
            addMaterialLogs() {
                if (!this.selectedMaterial()) return;
                // 追加フォームの初期化
                const newMaterialLog = this.initAddForm(this.selectedMaterial());

                this.formData.materialLogs.push(newMaterialLog);
                // console.info('pushのあと', {materialLogs: this.formData.materialLogs, selectMaterial: this.selectedMaterial});
                // 追加したら選択欄をリセット
                this.selectedMaterialId = '';
            },

            // 資材追加フォームの初期化メソッド
            initAddForm(master) {
                return {
                    // addFormUuid: crypto.randomUUID(),
                    addFormUuid: generateUUID(),
                    typeLabel: master.typeLabel,

                    materialId: master.id,
                    name: master.name,
                    typeId: master.typeId,
                    dilutionRate: master.defaultDilutionRate,
                    quantity: master.standardSprayVolume,
                    materialAmount: '',
                    manufacturer: master.manufacturer
                };
            },

            // 資材フォームの削除ロジック
            removeMaterialLog(uuid) {
                this.formData.materialLogs = this.formData.materialLogs.filter(
                    log => log.addFormUuid !== uuid
                );

                // 対応するエラー・メッセージを持っていたら削除
                if (!mappedErrors[uuid]) {
                    delete this.mappedErrors[uuid];
                }
            },

            // 登録資材重複の確認
            isDuplicated(materialId) {
                // メインロジック 重複の確認
                if (this.formData.materialLogs?.some(mlog => mlog.materialId == materialId)) {
                    return '** 登録済 **';
                }
                return '';
            },

            /////
            // fetch()送信

            // @submit.preventで呼び出し
            async submitForm() {
                // オフラインならLocalstorageに保存して退避
                // if (!this.isOnline) {
                if (!this.$store.network.isOnline) {
                    this.saveToLocalStorage();
                    alert('通信オフラインのためブラウザに一時保存しました。(localStorage)');
                    return;
                }

                // オンライン時処理
                let timeoutId = null;

                try {
                    // post送信用のpayloadを生成
                    const payload = this.buildPostPayload();
                // console.log('payloadのなかみ', {payload});

                    const controller = new AbortController();
                    timeoutId = setTimeout(() => controller.abort(), 5000); // 5000ms to timeout

                    let response;

                    if (this.isEdit) {
                        response = await fetch(`/work-logs/edit/${workLog.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload),
                            signal: controller.signal // controle timeout
                        });
                    } else {
                        response = await fetch('/work-logs/create', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload),
                            signal: controller.signal // controle timeout
                        });
                    }

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

                        return;
                    }

                    // ----------------------------------------------------
                    // 3. 保存成功処理 (200 OK系)
                    // ----------------------------------------------------
                    const data = await response.json(); // 成功レスポンスのJSONを解析

                    // 呼び出し元が編集ページなら現在の画面の再レンダリングをスキップして遷移
                    if (this.isEdit) return window.location.href = data.redirect_url;

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
                        console.error('通信に失敗したため、ローカル保存にフォールバックします。', {error});
                    }

                    // 通信エラー、タイムアウトなど通信によるエラーの場合はLocalStorageに退避
                    this.saveToLocalStorage();
                    alert('通信エラー\nブラウザに一時保存しました。(localStorage)');

                }
            },

            /**
             * postするpayloadを構築する。snake caseへリネーム。
             */
            buildPostPayload() {
                // オブジェクトの型によるバリデーション
                if (!this.formData || typeof this.formData !== 'object') {
                    throw new Error('formDataの型が一致しませんでした。');
                }

                const {
                    cropSeasonId,
                    createdBy,
                    performedBy,
                    workDate,
                    status,
                    title,
                    content,
                    updatedBy,
                    materialLogs
                } = this.formData;


                const material_logs = materialLogs?.map(ml => {
                    return {
                        dilution_rate: ml.dilutionRate,
                        material_amount: ml.materialAmount,
                        material_id: ml.materialId,
                        quantity: ml.quantity,
                    }
                });

                const performed_by = performedBy?.map(user => {
                    return {
                        id: user.id
                    }
                });

                return {
                    crop_season_id: cropSeasonId,
                    created_by: createdBy,
                    performed_by,
                    work_date: workDate,
                    status: status,
                    title: title,
                    content: content,
                    updated_by: updatedBy,
                    material_logs
                };
            },

            // バックエンドから返ってくるerrorなので命名がsnake caseなので注意
            // errorもフロントエンドに返すときCamelに変換する？
            insertUuidToErrors(rawErrors) {
                this.mappedErrors = {};

                Object.keys(rawErrors).forEach(key => {
                    // 動的フォーム配列materialLogsを持つキーからindex数字とフィールド名を抽出
                    const match = key.match(/^materialLogs\.(\d+)\.(.+)$/);
                    // materialLogsに関するエラーがあるか
                    if (match) {

                        const index = parseInt(match[1]); // マッチグループ2行目
                        const fieldName = match[2];

                        if (this.formData.materialLogs && this.formData.materialLogs[index]) {
                            const rowId = this.formData.materialLogs[index].addFormUuid;

                            // 初期化
                            if (!this.mappedErrors[rowId]) {
                                this.mappedErrors[rowId] = {};
                            }
                            // UUIDに対応してエラーメッセージを管理
                            this.mappedErrors[rowId][fieldName] = rawErrors[key][0];
                        }
                    } else {
                        // materialLogs以外の通常属性のエラーもそのまま保持
                        this.mappedErrors[key] = rawErrors[key][0];
                    }
                });

                // デバッグ用バリデーションエラー全件表示
                console.info({'[バリデーションエラー]this.mappedErrors':this.mappedErrors});
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
            showDraftInfo() {
                const id = this.selectedDraftUuid;
                const draft = this.draftWorkLog.find(log => log.draftUuid == id);
                return {
                    draft,
                    cropName: `作物名: ${draft?.cropName || '未選択'}`,
                    title: `作業名: ${draft?.title || '未記入'}`
                }
            },

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
                    console.info('[_getStrageDRAFT_LOG] LocalStorageにDRAFT_LOGがありませんでした。(no issue)', { 'rawDrafts': rawDrafts });
                    return;
                }

                const parsedRawDrafts = this._parseRawDrafts(rawDrafts);
                this.draftWorkLog = [...parsedRawDrafts];
            },

            /**
             *
             * payloadを生成
             * localStrage：Camel caseのまま　==> 呼び出してフォームにそのまま流し込むため
             * submit post：Snake caseへ変換　==> DBへ登録するため
             *
             */
            _parseRawDrafts(rawDrafts) {
                let newParsedDrafts = [];
                try {
                    newParsedDrafts = JSON.parse(rawDrafts);
                    for (const draft of newParsedDrafts) {
                        if (!draft.draftUuid){
                            throw {name: 'Exist broken Draft', message:'UUIDが存在しない記録が含まれています'};
                        }
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
                        // console.error(`'[_parseRawDrafts] Exist broken Draft. ${e.message}'`, { 'newParsedDrafts': newParsedDrafts });
                        console.error(`'[_parseRawDrafts] Exist broken Draft. ${e.message}'`);
                        return;
                    }
                }
                return newParsedDrafts;
            },

            // Localstorage保存ロジック
            saveToLocalStorage() {
                const newRecord = this.buildDraftRecord(this.formData);

                try {

                    const tempRecords = this.draftWorkLog || [];

                    // for (const old of tempRecords) {
                    //     tempRecords.push(this.buildDraftRecord(old.formData));
                    // };
                    // 上書き処理 or push
                    // const deleteIndex = tempRecords.findIndex(p => p.draftUuid == newRecord?.draftUuid);
                    const deleteIndex = tempRecords.findIndex(p => p.draftUuid == newRecord?.draftUuid);
                    if (deleteIndex !== -1) {
                        tempRecords[deleteIndex] = JSON.parse(JSON.stringify(newRecord));
                        tempRecords[deleteIndex].savedAt = new Date().toISOString;
                    } else {
                        tempRecords.push(JSON.parse(JSON.stringify(newRecord)));
                    }
                    localStorage.setItem('DRAFT_LOG', JSON.stringify(tempRecords));

                    // 呼び出し元が編集ページなら下書きに保存したら遷移元のページに遷移
                    if (this.isEdit) {
                        return window.location.replace(document.referrer || '/dashboard');
                    }
                    this.resetFormData();

                } catch(e) {
                    console.group('[saveToLocalStorage] 下書きの保存に失敗しました。');
                        console.info('実行メソッド: JSON.stringify(newRecord)');
                        console.error(`'${e.name}: ${e.message}\n保存予定のデータ'`, { newRecord: newRecord });
                        console.error('コンポーネント上の下書きデータ', { 'tempRecords = this.draftWorkLog': this.draftWorkLog });
                    console.groupEnd();
                    // ユーザにリトライさせてなお失敗するのであれば、端末やブラウザの種類・バージョンによる違い？
                    alert('エラー：下書きに失敗しました。ブラウザを閉じてからやり直してください。\n※連続してエラーが発生する場合は管理者に連絡してください。');
                    return;
                } finally {
                    // 下書きを読み込んで初期化
                    this.initDraftWorkLog()
                }
            },

            /**
             *
             */
            buildDraftRecord(formData) {
                // オブジェクトの型によるバリデーション
                if (!formData || typeof formData !== 'object') {
                    throw new Error('formDataの型が一致しませんでした。');
                }

                const {draftUuid = '', savedAt = '', ...rest} = formData;
                return {
                    ...rest,
                    draftUuid: draftUuid || generateUUID(),
                    savedAt: savedAt || new Date().toISOString(),
                };
            },


            // ----------------------------------------------------
            // 下書きの削除
            // ----------------------------------------------------
            // 選択中の１件をそのまま削除ボタン
            // ----------------------------------------------------
            deleteSelectedDraft() {
                if (!this.selectedDraftUuid) return;

                // コア削除処理を呼び出す
                this._deleteDraftByUuid(this.selectedDraftUuid);

                this.selectedDraftUuid = '';
                this.formData.draftUuid = '';
                // this.updateData('selectedDraftUuid', '');
            },

            // ----------------------------------------------------
            // 読み込んだ下書きのポストが成功した際に該当の下書きを１件削除
            // ----------------------------------------------------
            clearSubmittedDraft() {
                // formDataが下書きである場合にのみ実行
                if (this.formData.draftUuid) {
                    // コア削除処理を呼び出す
                    this._deleteDraftByUuid(this.formData.draftUuid);


                    // this.updateData('formData.draftUuid', null);
                    this.formData.draftUuid = '';
                }
            },

            // ----------------------------------------------------
            // コア関数 UUIDを受け取り localStorage/配列から削除するだけ
            // ----------------------------------------------------
            _deleteDraftByUuid(uuid) {
                this.draftWorkLog = this.draftWorkLog.filter(log => log.draftUuid !== uuid);
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
                const selectedDraft = this.draftWorkLog.find(draft => draft.draftUuid === this.selectedDraftUuid);
                // const draftFormData = { ...selectedDraft };
                if (!selectedDraft) {
                    console.error(`[fillWithDraft] 下書きデータが破損しています。`, { draftWorkLog:this.draftWorkLog, selectedDraftUuid: this.selectedDraftUuid });

                    alert('下書きの読み込みに失敗しました。ブラウザを閉じてからやり直してください。');
                    return;
                }

                this.formData = {...selectedDraft};
            },

            // 下書きの途中で新規の作業入力に切り替えてしまったとき
            async skipDraft() {
                await this.submitForm();
                this.formData.draftUuid = '';
            },

        }
    }
