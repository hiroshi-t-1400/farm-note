// /var/www/src/resources/js/components/modules/admin/requests/users/edit.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";
import { REQUEST_STATUS } from "../../../../../constants/requestStatus";

import { loadUser, submitUpdateRequestData, submitDeleteRequestData, submitAcknowledgeRequestData } from "./requestLogic";
import handleRequestError from "./error";

export default (config) => {
    let {
        payload,
        id,
        created_at,
        rejection_reason: rejectionReason,
        target_user_id: targetUserId,
        status: requestStatus,
    } = config?.initialModel || '';
    const targetId = id || ','

    let {formData, old} = loadUser(payload);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ

    // 主にアクションボタンの隠蔽
    const DENY_STATUS = ['rejected', 'approved'];
    function canEdit() {
        return !DENY_STATUS.includes(requestStatus);
    };

    function canAcknowledge() {
        return requestStatus === 'rejected';
    };

    return {
        targetId,

        formData,
        old,
        createdAt,

        rejectionReason,
        canEdit: canEdit(),
        canAcknowledge: canAcknowledge(),
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
                handleRequestError(e);
            }
        },

        async submitDelete() {
            try {
                const response = await submitDeleteRequestData(targetId);

                alert(response.data.message);
                window.location.replace(backUrl);
            } catch(e) {
                handleRequestError(e);
            }
        },

        async submitAcknowledge() {
            try {
                const response = await submitAcknowledgeRequestData(targetId);

                alert(response.data.message);
            } catch(e) {
                handleRequestError(e);
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

