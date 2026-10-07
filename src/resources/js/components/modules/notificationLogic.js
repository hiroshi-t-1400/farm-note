// /var/www/src/resources/js/components/modules/notificationLogic.js

import submitService from "./submitService";


// 通知１件をread
export async function submitMarkAsRead(
    notificationId
) {
    try {
        const response = await submitService.readNotification(
            notificationId
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

/**
 * 複数の通知を既読化
 * @param {Array} notificationIds
 */
export async function submitMarkAsReadSome(
    notificationIds
) {
    try {
        const response = await submitService.readNotifications(
            notificationIds
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

// 全ての通知を既読化
export async function submitMarkAsReadAll() {
    try {
        const response = await submitService.readAllNotifications();
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

function normalizeApplicationError(e) {
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
