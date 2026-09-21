// /var/www/src/resources/js/components/modules/admin/requests/users/createRequest.js
import { ROLES } from "../../../../../constants/roles";
import submitService from "./submitService";


function buildPayload (formData) {
    return {
        name: formData.username,
        email: formData.email,
        password: formData.password,
        loginId: formData.loginId,
        role: formData.role,
    }
}

export async function submitCreate(
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.createRequest(
            payload
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

export async function submitUpdate(
    targetUserId,
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.updateRequest(
            targetUserId,
            payload
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

export async function submitDisable(targetUserId) {
    try {
        const response = await submitService.destroyRequest(
            targetUserId
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

/**
 * 申請内容の編集
 */
export async function submitUpdateRequestData(
    requestDataId,
    targetUserId,
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.updateRequestData(
            requestDataId,
            targetUserId,
            payload
        );
        return response;
    } catch (e) {
        throw normalizeRequestError(e);
    }
}

/**
 * 申請の削除・取り下げ
 */
export async function submitDeleteRequestData(
    requestDataId
) {
    const response = await submitService.deleteRequestData(
        requestDataId
    );
    return response;
}

/**
 * 却下された申請を確認した処理
 */
export async function submitAcknowledgeRequestData(
    requestDataId
) {
    const response = await submitService.acknowledgeRequestData(
        requestDataId
    );
    return response;
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

export function loadUser(targetUser = null) {
    const formData = {
        email: targetUser?.email || '',
        password: '',
        loginId: targetUser?.login_id || '',
        username: targetUser?.name || '',
        role: targetUser?.roles?.[0]?.['name'] || 'worker',
    };

    const old = buildOld(formData);
// console.log(formData);
    return {
        formData,
        old,
    }
};

function buildOld(formData) {
    const get = formData == {} ? {} : {...formData};
    get.roleLabel = ROLES[get.role];
    return get;
};
