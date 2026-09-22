// /var/www/src/resources/js/components/modules/admin/requests/users/edit.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";
import { REQUEST_STATUS } from "../../../../../constants/requestStatus";

import { loadUser, submitUpdateRequestData, submitDeleteRequestData, submitCreate, submitAcknowledgeRequestData } from "./requestLogic";

export default (config) => {
    let {
        payload,
        id,
        created_at,
        target_user_id: targetUserId = '',
        status: requestStatus,
    } = config?.initialModel || '';
    const targetId = id || ','

    let {formData, old} = loadUser(payload);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ

    const statusLabel = REQUEST_STATUS[requestStatus];
    const statusClass = {
        default: 'text-gray-500 text-sm',
        rejected: 'font-bold text-amber-800',
        pending: 'font-bold text-blue-500',
    };

    // 新規申請の申請内容を編集できるか
    function canEdit() {
        if(requestStatus === 'pending') return true;
    };

    return {
        targetId,

        formData,
        old,
        createdAt,

        canEdit: canEdit() || '',
        statusLabel: statusLabel,
        statusClass: statusClass[requestStatus],
        errors: {},

        resultData: '',
        backUrl,

        async submitUpdate() {
            try {
                const response = await submitUpdateRequestData(targetId, targetUserId, this.formData);
                // 成功処理
                alert(response.data.message);
                window.location.replace(backUrl);
            } catch (e) {
                this.handleRequestError(e);
            }
        },

        async submitDelete() {
            try {
                const response = await submitDeleteRequestData(targetId);

                alert(response.data.message);
                window.location.replace(backUrl);
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
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

