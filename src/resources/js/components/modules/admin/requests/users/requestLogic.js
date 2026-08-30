// /var/www/src/resources/js/components/modules/admin/requests/users/createRequest.js


export function buildPayload (formData) {
    return {
        name: formData.username,
        email: formData.email,
        password: formData.password,
        loginId: formData.loginId,
        role: formData.role,
    }
}


export async function submit(actionTypeUrl, payload) {
    try {
        await window.http.get('/sanctum/csrf-cookie');

        const response = await window.http.post(
            actionTypeUrl,
            payload
        );

        // ----------------------------------------------------
        // 認証成功（200 OK系）
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
export function getError(field, errors) {
    // bladeの属性はusernameとしているためここで変換する
    if (field === 'username') return errors?.['name'] || null;
    return errors?.[field] || null;
}

