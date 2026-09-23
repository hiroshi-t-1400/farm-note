// src/resources/js/components/modules/admin/approvals/approve.js

import { tsToDate } from "../../../../utils";
import { getBackUrl } from "../../../../utils";

import { ACTION_LABELS } from "../../../../constants/actions";
import { ROLES } from "../../../../constants/roles";
import { loadUser, submitApproveApplication, submitRejectApplication } from "./approvalLogic";
import { REQUEST_STATUS } from "../../../../constants/requestStatus";

export default (config) => {
    let {
        action_type: actionType,
        payload,
        id: targetId,
        created_at,
        updated_at,
        parent_application_id: parentApplicationId,
        status,
        rejection_reason: rejectedReason,
        target_user_id: targetUserId = '',
        target_user: targetUser = '',
        requester,
    } = config?.initialModels || '';

    const userData = loadUser(payload ?? targetUser);

    const createdAt = tsToDate(created_at);
    const updatedAt = tsToDate(updated_at);
    const actionLabel = ACTION_LABELS[actionType];

    const applicationStatus = getStatus();

    const backUrl = getBackUrl(`${location.origin}/admin/approvals/users`);

    function getStatus() {
        let temp = REQUEST_STATUS[status];
        if(!parentApplicationId) return temp;
        return `再申請 ${temp}`;
    };

    return {
        targetId,

        ...userData,

        createdAt,
        updatedAt,
        applicationStatus,
        actionLabel,
        roleLabel: ROLES[userData.role],
        rejectedReason,

        requester,

        rejectionReason: '',

        errors: {},

        resultData: '',
        backUrl,

        async submitApprove() {
            if (!confirm('申請を承認し、ユーザーの登録を行ってよろしいですか？')) {
                return;
            }

            this.errors = {};
console.log({'targetId':targetId});
            try {
                const response = await submitApproveApplication(
                    targetId
                );
                // 成功処理
                // 一覧画面へ移動する
                window.location.replace(this.backUrl);
            } catch(e) {
console.log({'submitのなかの e':e});

                this.handleRequestError(e);
            }
        },

        async submitReject() {
            if (!confirm('申請を却下してよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                const response = await submitRejectApplication(
                    targetId,
                    this.rejectionReason,
                );
                // 成功処理
                // 一覧画面へ移動する
                window.location.replace(this.backUrl);
            } catch(e) {
                this.handleRequestError(e);
            }
        },

        handleRequestError(error) {
            console.log({ 'error': error });
            if (error.type === 'validation') {
                this.errors = error.errors;
                alert(error.message);
                return;
            }
            alert(error.message);
        },

        // バリデーションエラーメッセージを返す
        getError(field) {
            return this.errors?.[field] || null;
        },


        // async submitApprove() {
        //     if (!confirm('申請を承認し、ユーザーの登録を行ってよろしいですか？')) {
        //         return;
        //     }

        //     this.errors = {};

        //     try {
        //         const response = await window.http.patch(`/admin/approvals/users/${this.targetId}/approve`);

        //         // ----------------------------------------------------
        //         // 認証成功（200 OK系）
        //         // ----------------------------------------------------

        //         // 一覧画面へ移動する
        //         window.location.replace(this.backUrl);

        //     } catch (e) {
        //         if (e.response) {
        //             const status = e.response.status;
        //             const data = e.response.data;

        //             // ----------------------------------------------------
        //             // 1. ビジネスロジックエラーのハンドリング（ここにバリデーションは無い
        //             // ----------------------------------------------------
        //             // コントローラーで受け取ったエラー情報を扱う
        //             if (status === 422) {
        //                 this.error = data.errors || {};
        //                 alert(data.message || '処理を実行できませんでした。');
        //                 return;
        //             }

        //             // ----------------------------------------------------
        //             // 2. 連続送信（429）のハンドリング
        //             // ----------------------------------------------------
        //             if (status === 429) {
        //                 this.errors = data.errors || {};
        //                 alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
        //                 return;
        //             }

        //             // ----------------------------------------------------
        //             // 3. その他のサーバーエラー（500系や404など
        //             // ----------------------------------------------------
        //             // 個別ハンドリング以外
        //             console.error('サーバーエラーが発生しました。', status, data);
        //             alert('サーバーエラーが発生しました。時間をおいて再度お試しください。');
        //             return;
        //         }

        //         // axiosのタイムアウトエラーハンドリング
        //         if (e.code === 'ECONNABORTED') {
        //             console.error('通信エラー： タイムアウトが発生しました。', e);
        //             alert('通信タイムアウトしました。接続状態をご確認の上、再度お試しください。');
        //         } else {
        //             console.error('不明な通信エラー:', e.message);
        //             alert('通信エラーが発生しました。');
        //         }
        //     }
        // },

        // async submitReject() {
        //     if (!confirm('申請を却下してよろしいですか？')) {
        //         return;
        //     }

        //     this.errors = {};

        //     try {

        //         const response = await window.http.patch(`/admin/approvals/users/${this.targetId}/reject`, {
        //             'rejection_reason': this.rejection_reason
        //         });

        //         // ----------------------------------------------------
        //         // 認証成功（200 OK系）
        //         // ----------------------------------------------------

        //         // 一覧画面へ移動する
        //         window.location.replace(backUrl);

        //     } catch (e) {
        //         if (e.response) {
        //             const status = e.response.status;
        //             const data = e.response.data;

        //             // ----------------------------------------------------
        //             // 1. 連続送信（429）のハンドリング
        //             // ----------------------------------------------------
        //             if (status === 422) {
        //                 this.error = data.errors || {};
        //                 alert(data.message || '処理を実行できませんでした。');
        //                 return;
        //             }

        //             // ----------------------------------------------------
        //             // 2. 連続送信（429）のハンドリング
        //             // ----------------------------------------------------
        //             if (status === 429) {
        //                 this.errors = data.errors || {};
        //                 alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
        //                 return;
        //             }

        //             // ----------------------------------------------------
        //             // 3. その他のサーバーエラー（500系や404など
        //             // ----------------------------------------------------
        //             // 個別ハンドリング以外
        //             console.error('サーバーエラーが発生しました。', status, data);
        //             alert('サーバーエラーが発生しました。時間をおいて再度お試しください。');
        //             return;
        //         }

        //         // axiosのタイムアウトエラーハンドリング
        //         if (e.code === 'ECONNABORTED') {
        //             console.error('通信エラー： タイムアウトが発生しました。', e);
        //             alert('通信タイムアウトしました。接続状態をご確認の上、再度お試しください。');
        //         } else {
        //             console.error('不明な通信エラー:', e.message);
        //             alert('通信エラーが発生しました。');
        //         }
        //     }
        // },

        // // バリデーションエラーメッセージを返す
        // getError(field) {
        //     return this.errors?.[field] || null;
        // },
    }
}

