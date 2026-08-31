// /var/www/src/resources/js/components/modules/admin/requests/users/edit.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";

import { buildPayload, submit, loadUser } from "./requestLogic";
import { deleteUserRequest } from "./delete";

export default (config) => {
    let {payload, id, created_at, rejection_reason: rejectionReason, actionType, target_user_id: targetUserId} = config?.initialModel || '';

    let {formData, old} = loadUser(payload);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ
    const submitRoute = getSubmitRoute();

    function getSubmitRoute() {
        const addPath = actionType === 'create'
            ? 'update'
            : `update/${targetUserId}`;

            return `${window.location.origin}/admin/requests/users/${id}/${addPath}`;
    }

    return {
        targetId: id,

        formData,
        old,
        createdAt,

        rejectionReason,

        errors: {},

        resultData: '',
        backUrl,

        async submitUpdate() {
            const payload = buildPayload(this.formData);
            try {
                const response = await submit(
                    submitRoute,
                    payload,
                    'patch',
                );

                // ----------------------------------------------------
                // 成功（200 OK系）
                // ----------------------------------------------------
                alert(response.data.message);
                window.location.replace(backUrl);

            } catch (error) {
                this.handleRqruestError(error);
            }
        },

        handleRequestError(error) {
            console.log({'error':error});
            if (error.type === 'validation') {
                this.errors = error.errors;
                alert(error.message);
                return;
            }
            alert(error.message);
        },

        async submitDelete() {
            deleteUserRequest().submitDelete(this.targetId);
        },

        // バリデーションエラーメッセージを返す
        getError(field) {
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

