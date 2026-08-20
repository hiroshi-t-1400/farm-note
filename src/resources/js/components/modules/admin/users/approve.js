// /var/www/src/resources/js/components/modules/admin/users/users

export default (config) => {

    let {payload, requester, id, ...rest} = config?.initialModels || '';

    const roleLabel = {
        owner: 'オーナー',
        manager: '管理者',
        worker: '一般ユーザー'
    };

    return {
        targetId: id,

        payload: {
            name: payload.name,
            loginId: payload.login_id,
            email: payload.email,
            password: payload.password,
            role: payload.role
        },

        requester: {
            id: requester.id,
            name: requester.name,
            role: requester?.role || 'worker'
        },

        ...rest,

        rejection_reason: '',

        errors: {},

        resultData: '',

        getRoleLabel(role) {
            return roleLabel[role];
        },

        async submitApprove() {
            if (!confirm('申請を承認し、ユーザーの登録を行ってよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const response = await window.http.patch(`/admin/approvals/users/${this.targetId}/approve`);

                // ----------------------------------------------------
                // 認証成功（200 OK系）
                // ----------------------------------------------------
                alert('アカウント登録申請が完了しました。');

                // 一覧画面へ移動する
                // return window.location.href() redirect?

            } catch (e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. ビジネスロジックエラーのハンドリング（ここにバリデーションは無い
                    // ----------------------------------------------------
                    // コントローラーで受け取ったエラー情報を扱う
                    if (status === 422) {
                        this.error = data.errors || {};
                        alert(data.message || '処理を実行できませんでした。');
                        return;
                    }

                    // ----------------------------------------------------
                    // 2. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (status === 429) {
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

        async submitReject() {
            if (!confirm('申請を却下してよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const response = await window.http.patch(`/admin/approvals/users/${this.targetId}/reject`, {
                    'rejection_reason': this.rejection_reason
                });

                // ----------------------------------------------------
                // 認証成功（200 OK系）
                // ----------------------------------------------------
                alert('アカウント登録申請を却下しました。');

                // 一覧画面へ移動する
                // return window.location.href() redirect?

            } catch (e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (status === 422) {
                        this.error = data.errors || {};
                        alert(data.message || '処理を実行できませんでした。');
                        return;
                    }

                    // ----------------------------------------------------
                    // 2. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (status === 429) {
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
            return this.errors?.[field] || null;
        },
    }
}

