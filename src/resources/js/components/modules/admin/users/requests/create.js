// /var/www/src/resources/js/components/modules/admin/users/users
import { ROLES } from "../../../../../constants/roles";

export default (config) => {

    const actionType = config?.initialModel?.['actionType'];
    const targetUser = config?.initialModel?.['targetUser'] || '';
    const targetUserId = targetUser.id || '';

    const backUrl = `${location.origin}/dashboard`; // 戻る遷移先はdashboard

    const formData = loadUser();

    const resultData = {};

    function loadUser() {
        return {
            email: targetUser?.email || '',
            password: '',
            loginId: targetUser?.login_id || '',
            username: targetUser?.name || '',
            role: targetUser?.roles?.[0]?.['name'] || 'worker',
        }
    };

    let old = buildOld();
    const isUpdate = actionType === 'update' ? true : false;

    function buildOld() {
        const get = formData == {} ? {} : {...formData};
        get.roleLabel = ROLES[get.role];
        return get;
    };

        console.log({'old': old});
    return {
        formData,
        old, // for update
        isUpdate,
        passwordMessage: ' ＊変更しない場合は空欄',
        errors: {},

        resultData,

        backUrl,

        buildPayload () {
            return {
                name: this.formData.username,
                email: this.formData.email,
                password: this.formData.password,
                loginId: this.formData.loginId,
                role: this.formData.role,
            }
        },

        async submitStore() {
            this.errors = {};
            const requestData = this.buildPayload ();

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const response = await window.http.post(`/admin/requests/users/${actionType}/${targetUserId}`,
                    requestData);

                // ----------------------------------------------------
                // 認証成功（200 OK系）
                // ----------------------------------------------------
                // 申請したユーザーデータを返す。申請画面に通信リザルトとしてレンダする
                this.resultData = requestData;
                this.formData = {};

                alert('アカウント登録申請が完了しました。');

            } catch (e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. バリデーションエラー（422）
                    // ----------------------------------------------------
                    if (data.status === 422) {
                        this.errors = data.errors || {};
                        alert('アカウント登録に失敗しました。 : ' + (data.message || '入力内容を確認してください。'));
                        return;
                    }

                    // ----------------------------------------------------
                    // 2. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (data.status === 429) {
                        this.errors = data.errors || {};
                        alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
                        return;
                    }

                    // ----------------------------------------------------
                    // 3. その他のサーバーエラー（500系や404など
                    // ----------------------------------------------------
                    // 個別ハンドリング以外
                    console.error('サーバーエラーが発生しました。', status, data);
                    alert('サーバーエラーが発生しました。時間をおいて再度お試しください。');
                    return;
                }

                // axiosのタイムアウトエラーハンドリング
                if (e.code === 'ECONNABORTED') {
                    console.error('通信エラー： タイムアウトが発生しました。', e);
                    alert('通信タイムアウトしました。接続状態をご確認の上、再度お試しください。');
                } else {
                    console.error('不明な通信エラー:', e.message);
                    alert('通信エラーが発生しました。');
                }
            }
        },

        // バリデーションエラーメッセージを返す
        getError(field) {
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

