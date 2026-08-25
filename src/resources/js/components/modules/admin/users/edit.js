// /var/www/src/resources/js/components/modules/admin/users/users

import { tsToDate } from "../../dashboard/utils";

export default (config) => {

    const roleLabel = {
        owner: 'オーナー',
        manager: '管理者',
        worker: '一般ユーザー'
    };

    let {payload, id, created_at, rejection_reason} = config?.initialModel || '';

    payload = {
            username: payload.name,
            loginId: payload.login_id,
            email: payload.email,
            password: '',
            role: payload.role
        };

    let old = {...payload};
    old.roleLabel = roleLabel[old.role];

    const createdAt = tsToDate(created_at);

    const backUrl = `${location.origin}/admin/users`; // 戻る遷移先はindexページ

    return {
        targetId: id,

        payload,
        old,
        createdAt,

        rejection_reason,

        errors: {},

        resultData: '',
        backUrl,

        getRoleLabel(role) {
            return roleLabel[role];
        },

        async submitUpdate() {
            this.errors = {};

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const response = await window.http.patch(`/admin/users/${this.targetId}/update`, {
                    name: this.username,
                    email: this.email,
                    password: this.password,
                    loginId: this.loginId
                });

                // ----------------------------------------------------
                // 認証成功（200 OK系）
                // ----------------------------------------------------
                // 申請したユーザーデータを返す。申請画面に通信リザルトとしてレンダする
                this.resultData = response.data;
                alert('申請内容を変更しました。');

            } catch (e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. バリデーションエラー（422）
                    // ----------------------------------------------------
                    if (response.status === 422) {
                        this.errors = data.errors || {};
                        alert('申請内容の変更に失敗しました。 : ' + (response.data.message || '入力内容を確認してください。'));
                        return;
                    }

                    // ----------------------------------------------------
                    // 2. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (response.status === 429) {
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
                if (error.code === 'ECONNABORTED') {
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

