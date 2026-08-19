// /var/www/src/resources/js/components/modules/admin/users/users

export default (config) => {


    return {
        userData, // 申請待ちのユーザーデータ

        email: '',
        password: '',
        loginId: '',
        username: '',
        errors: {},

        resultData: '',

        async submitApprove(targetId) {
            if (!confirm('申請を承認し、ユーザーの登録を行ってよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const response = await window.http.patch(`/admin/approvals/${targetId}/users`);

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
                    // 1. 連続送信（429）のハンドリング
                    // ----------------------------------------------------
                    if (response.status === 429) {
                        this.errors = data.errors || {};
                        alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
                        return;
                    }

                    // ----------------------------------------------------
                    // 2. その他のサーバーエラー（500系や404など
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
    }
}

