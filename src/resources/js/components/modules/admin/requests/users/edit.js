// /var/www/src/resources/js/components/modules/admin/requests/users/edit.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";

import { buildPayload, loadUser } from "./requestLogic";

export default (config) => {
    console.log(config?.initialModel);
    let {payload, id, created_at, rejection_reason: rejectionReason, actionType, target_user_id: targetUserId} = config?.initialModel || '';

    let {formData, old} = loadUser(payload);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ
    const submitRoute = getSubmitRoute();
console.log(submitRoute);
    function getSubmitRoute() {
        const addPath = actionType === 'create'
            ? 'update'
            : `update/${targetUserId}`;

            return `${window.location.origin}/admin/requests/users/record/${id}/${addPath}`;
    }

    return {
        targetId: id,

        formData,
        old,
        createdAt,

        rejectionReason,

        errors: {},

        resultData: '',
        backUrl,


        async submitUpdate() {
            this.errors = {};

            try {
                await window.http.get('/sanctum/csrf-cookie');

                const payload = buildPayload(this.formData);
                console.log(payload);

                const response = await window.http.patch(
                    submitRoute,
                    payload
                );

                // ----------------------------------------------------
                // 成功（200 OK系）
                // ----------------------------------------------------
                alert(response.data.message);
                window.location.replace(backUrl);

            } catch (e) {
                if (e.response) {
                    const status = e.response.status;
                    const data = e.response.data;

                    // ----------------------------------------------------
                    // 1. バリデーションエラー（422）
                    // ----------------------------------------------------
                    if (status === 422) {
                        this.errors = data.errors || {};
                        alert('申請内容の変更に失敗しました。 : ' + (data.message || '入力内容を確認してください。'));
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
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

