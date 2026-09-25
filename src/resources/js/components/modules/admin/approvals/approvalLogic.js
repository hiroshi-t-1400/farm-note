// /var/www/src/resources/js/components/modules/admin/approvals/approvalLogic.js

import submitService from "./submitService";


// 申請の承認操作を送信
export async function submitApproveApplication(
    applicationId
) {
    try {
        const response = await submitService.approveApplication(
            applicationId
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

// 申請の却下操作を送信
export async function submitRejectApplication(
    applicationId,
    rejectionReason
) {
    try {
        const response = await submitService.rejectApplication(
            applicationId,
            rejectionReason
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

function normalizeRequestError(e) {
    if (e.response) {
        const {status, data} = e.response;

        // ----------------------------------------------------
        // 1. バリデーションエラー（422）
        // ----------------------------------------------------
        if (status === 422) {
            return {
                type: 'validation',
                status,
                errors: data.errors || {},
                message: data.message || '内容を確認してください。',
            };
        }

        // ----------------------------------------------------
        // 2. 連続送信（429）のハンドリング
        // ----------------------------------------------------
        if (status === 429) {
            return {
                type: 'too_many_requests',
                status,
                errors: data.errors || {},
                message: '送信操作が多すぎます。',
            };
        }

        // ----------------------------------------------------
        // 3. 認可エラー（403）のハンドリング
        // ----------------------------------------------------
        if (status === 403) {
            return {
                type: 'forbidden',
                status,
                errors: data.errors || {},
                message: 'この操作は認可されていません。',
            };
        }

        // ----------------------------------------------------
        // 4. その他のサーバーエラー（500系や404など
        // ----------------------------------------------------
        return {
            type: 'server',
            status,
            errors: data.errors || {},
            message: 'サーバーエラーが発生しました。',
        };
    }

    // axiosのタイムアウトエラーハンドリング
    if (e.code === 'ECONNABORTED') {
        return {
            type: 'timeout',
            message: 'タイムアウトが発生しました。',
        };
    }

    return {
        type: 'network',
        message: '通信エラーが発生しました。',
    };
}

export function loadUser(targetUser) {

    let role = targetUser?.role ?? targetUser?.roles?.[0]?.['name'];

    const formData = {
        email: targetUser?.email || '',
        password: '',
        loginId: targetUser?.login_id || '',
        username: targetUser?.name || '',
        role: role || 'worker',
    };

    return formData;
};
