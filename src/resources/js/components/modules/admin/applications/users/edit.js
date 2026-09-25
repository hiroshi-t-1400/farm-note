// /var/www/src/resources/js/components/modules/admin/Applications/users/edit.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";
import { APPLICATION_STATUS } from "../../../../../constants/applicationStatus";

import { loadUser, submitUpdateApplicationData, submitDeleteApplicationData } from "./applicationLogic";
import { ACTION_LABELS } from "../../../../../constants/actions";

export default (config) => {
    let {
        action_type: actionType,
        payload = '',
        id,
        created_at,
        target_user_id: targetUserId = '',
        target_user: targetUser = '',
        status: applicationStatus,
    } = config?.initialModel || '';
    const targetId = id || ','

    let {formData, old} = loadUser(payload ?? targetUser);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/applications/users`); // 戻る遷移先はindexページ

    const actionLabel = ACTION_LABELS[actionType];
    const statusLabel = APPLICATION_STATUS[applicationStatus];
    const statusClass = {
        default: 'text-gray-500 text-sm',
        rejected: 'font-bold text-amber-800',
        pending: 'font-bold text-blue-500',
    };

    return {
        targetId,

        formData,
        old,
        createdAt,

        actionLabel,
        // canEdit: canEdit() || '',
        statusLabel: statusLabel,
        statusClass: statusClass[applicationStatus],
        errors: {},

        resultData: '',
        backUrl,

        canEdit() {
            if(applicationStatus === 'pending' && actionType !== 'disable') return true;
        },

        canDelete() {
            if(applicationStatus === 'pending') return true;
        },

        async submitUpdate() {
            try {
                const response = await submitUpdateApplicationData(targetId, targetUserId, this.formData);
                // 成功処理
                alert(response.data.message);
                window.location.replace(backUrl);
            } catch (e) {
                this.handleApplicationError(e);
            }
        },

        async submitDelete() {
            try {
                const response = await submitDeleteApplicationData(targetId);

                alert(response.data.message);
                window.location.replace(backUrl);
            } catch(e) {
                this.handleApplicationError(e);
            }
        },

        handleApplicationError(error) {
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

