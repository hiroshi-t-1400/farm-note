// /var/www/src/resources/js/components/modules/admin/Applications/users/reapply.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";

import { loadUser, submitCreate, submitUpdate, submitAcknowledgeApplicationData, submitReapplyHistory,  } from "./applicationLogic";
import { ACTION_LABELS } from "../../../../../constants/actions";

export default (config) => {
    let {
        action_type: actionType,
        payload,
        id: targetId = '',
        created_at,
        rejection_reason: rejectionReason,
        rejection_acknowledge_at: rejectionAcknowledgeAt,
        target_user_id: targetUserId = '',
        target_user: targetUser = '',
        reapplied_at,
    } = config?.initialModel || '';

    let {formData, old} = loadUser(payload ?? targetUser);

    const createdAt = tsToDate(created_at);

    const actionLabel = ACTION_LABELS[actionType];
    const reapplyStatus = checkReapplied();

    const backUrl = getBackUrl(`${location.origin}/admin/applications/users`); // 戻る遷移先はindexページ

    const isAcknowledged = !!rejectionAcknowledgeAt;

    function checkReapplied() {
        if(reapplied_at === null) return '';
        return tsToDate(reapplied_at);
    };

    function initFormData() {
        let {formData, old} = loadUser(payload ?? targetUser);
        formData.rejection_reason
    };

    return {
        targetId,

        formData,
        old,
        createdAt,
        actionLabel,
        reapplyStatus,

        rejectionReason,
        isAcknowledged: isAcknowledged,
        statusLabel: '却下',
        statusClass: 'font-bold text-amber-800',
        errors: {},

        resultData: '',
        backUrl,

        canAcknowledge() {
            return !this.isAcknowledged;
        },

        canReapply() {
            return this.isAcknowledged && !reapplyStatus && actionType !== 'disable';
        },

        async submitReapply() {
            let response = '';
            try {
                response = await this.submit();
            } catch(e) {
                this.handleApplicationError(e);
            }

            const data = response.data;

            await this.updateApplicationStatus(data.applicationId);
            alert(data.message);
            window.location.replace(backUrl);
        },

        async submit() {
            let response = '';
            if(actionType === 'create') {
                response = await submitCreate(this.formData);
            } else if(actionType === 'update') {
                response = await submitUpdate(targetUserId, this.formData);
            }

            return response;
        },

        // 申請が却下されたことを確認したボタン
        async submitAcknowledge() {
            try {
                const response = await submitAcknowledgeApplicationData(targetId);

                alert(response.data.message);
                // 再申請の送信ボタンをactive
                this.isAcknowledged = true;
            } catch(e) {
                this.handleApplicationError(e);
            }
        },

        // 却下されたレコードに再申請の履歴を記録
        async updateApplicationStatus(childApplicationId) {
            try {
                await submitReapplyHistory(targetId, childApplicationId);
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

