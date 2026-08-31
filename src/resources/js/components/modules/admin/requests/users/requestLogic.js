// /var/www/src/resources/js/components/modules/admin/requests/users/createRequest.js
import { ROLES } from "../../../../../constants/roles";

export function buildPayload (formData) {
    return {
        name: formData.username,
        email: formData.email,
        password: formData.password,
        loginId: formData.loginId,
        role: formData.role,
    }
}


export async function submit(submitRoute, payload) {
    try {
        await window.http.get('/sanctum/csrf-cookie');

        const response = await window.http.post(
            submitRoute,
            payload
        );

        // ----------------------------------------------------
        // 成功処理（200 OK系）
        // ----------------------------------------------------

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
        // 3. その他のサーバーエラー（500系や404など
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

// バリデーションエラーメッセージを返す
export function getError(errors) {
    // errors.map(e => ({

    // }))
    if (field === 'username') return errors?.['name'] || null;
    return errors?.[field] || null;
}

export function loadUser(targetUser) {
    const formData = {
        email: targetUser?.email || '',
        password: '',
        loginId: targetUser?.login_id || '',
        username: targetUser?.name || '',
        role: targetUser?.roles?.[0]?.['name'] || 'worker',
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
