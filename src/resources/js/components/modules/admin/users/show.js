// /var/www/src/resources/js/components/modules/admin/users/users
import { tsToDate } from "../../dashboard/utils";

import { ROLES } from "../../../../constants/roles";
import { USER_STATUS } from "../../../../constants/userStatus";

export default (config) => {

    let {id: userId, name: username, login_id: loginId, email, created_at, updated_at, roles, status} = config?.initialModels || '';

    const createdAt = tsToDate(created_at);
    const updatedAt = tsToDate(updated_at);

    const roleLabel = ROLES[roles[0]?.['name']];
    const statusLabel = USER_STATUS[status];
    const isActive = checkStatus();
    let statusClass = '';

    let backUrl = checkBack();

    function checkBack() {
        return document.referrer !== location.href
            ? document.referrer
            : `${location.origin}/users/index`;
    };

    function checkStatus() {
        if (status !== 'active') {
            statusClass = 'text-red-500';
            return false;
        }
        return true;
    };

    // const editUrl =`${location.href}/admin/requests/users/${userId}`

    return {
        userId,
        username,
        loginId,
        email,
        createdAt,
        updatedAt,
        roleLabel,
        statusLabel,

        isActive,
        statusClass,

        backUrl,
        // editUrl,

        errors: {},

        async submitDelete() {
            if (!confirm('ユーザー情報の削除を申請してよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                const response = await window.http.patch(`/admin/requests/users/${this.userId}/destroy`);

                window.location.replace(this.backUrl);
            } catch(e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. ビジネスロジックエラーのハンドリング
                    // ----------------------------------------------------
                    if (status === 422) {
                        this.error = data.errors || {};
                        alert(data.message || '申請を実行できませんでした。');
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
    }
}

