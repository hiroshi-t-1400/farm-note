// /var/www/src/resources/js/components/modules/admin/Applications/users/createApplication.js
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

// ユーザーの新規登録申請post
export async function submitCreate(
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.createApplication(
            payload
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

// 既存ユーザー情報の更新申請post
export async function submitUpdate(
    targetUserId,
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.updateApplication(
            targetUserId,
            payload
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

// 既存ユーザーの削除申請post
export async function submitDisable(targetUserId) {
    try {
        const response = await submitService.destroyApplication(
            targetUserId
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

/**
 * 申請内容の編集
 */
export async function submitUpdateApplicationData(
    ApplicationDataId,
    targetUserId,
    formData
) {
    const payload = buildPayload(formData);
    try {
        const response = await submitService.updateApplicationData(
            ApplicationDataId,
            targetUserId,
            payload
        );
        return response;
    } catch (e) {
        throw normalizeApplicationError(e);
    }
}

/**
 * 申請の削除・取り下げ
 */
export async function submitDeleteApplicationData(
    ApplicationDataId
) {
    const response = await submitService.deleteApplicationData(
        ApplicationDataId
    );
    return response;
}

/**
 * 却下された申請を確認した処理
 */
export async function submitAcknowledgeApplicationData(
    ApplicationDataId
) {
    const response = await submitService.acknowledgeApplicationData(
        ApplicationDataId
    );
    return response;
}

/**
 * 却下された申請の再申請を行った履歴を記録
 */
export async function submitReapplyHistory(
    parentApplicationId,
    childApplicationId
) {
    const response = await submitService.reapplyHistory(
        parentApplicationId,
        childApplicationId
    );
    return response;
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
                type: 'too_many_Applications',
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

    const old = buildOld(formData);
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
