// src/resources/js/components/modules/admin/approvals/approve.js

import { tsToDate } from "../../../../utils";
import { getBackUrl } from "../../../../utils";

import { ACTION_LABELS } from "../../../../constants/actions";
import { ROLES } from "../../../../constants/roles";
import { loadUser, submitApproveApplication, submitRejectApplication } from "./approvalLogic";
import { APPLICATION_STATUS } from "../../../../constants/applicationStatus";

export default (config) => {
    let {
        action_type: actionType,
        payload,
        id: targetId,
        created_at,
        updated_at,
        parent_application_id: parentApplicationId,
        status,
        rejection_reason: rejectedReason,
        target_user_id: targetUserId = '',
        target_user: targetUser = '',
        requester,
    } = config?.initialModels || '';

    const userData = loadUser(payload ?? targetUser);

    const createdAt = tsToDate(created_at);
    const updatedAt = tsToDate(updated_at);
    const actionLabel = ACTION_LABELS[actionType];

    const applicationStatus = getStatus();

    const backUrl = getBackUrl(`${location.origin}/admin/approvals/users`);

    function getStatus() {
        let temp = APPLICATION_STATUS[status];
        if(!parentApplicationId) return temp;
        return `再申請 ${temp}`;
    };

    return {
        targetId,

        ...userData,

        createdAt,
        updatedAt,
        applicationStatus,
        actionLabel,
        roleLabel: ROLES[userData.role],
        rejectedReason,

        requester,

        rejectionReason: '',

        errors: {},

        resultData: '',
        backUrl,

        async submitApprove() {
            if (!confirm(`申請を承認し、ユーザーの${actionLabel}を行ってよろしいですか？`)) {
                return;
            }

            this.errors = {};

            try {
                const response = await submitApproveApplication(
                    targetId
                );
                // 成功処理
                // 一覧画面へ移動する
                window.location.replace(this.backUrl);
            } catch(e) {

                this.handleApplicationError(e);
            }
        },

        async submitReject() {
            if (!confirm('申請を却下してよろしいですか？')) {
                return;
            }

            this.errors = {};

            try {
                const response = await submitRejectApplication(
                    targetId,
                    this.rejectionReason,
                );
                // 成功処理
                // 一覧画面へ移動する
                window.location.replace(this.backUrl);
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
            return this.errors?.[field] || null;
        },

    }
}

