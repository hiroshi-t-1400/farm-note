// /var/www/src/resources/js/components/modules/admin/requests/users/reapply.js

import { tsToDate } from "../../../../../utils/date";
import { getBackUrl } from "../../../../../utils";
import { REQUEST_STATUS } from "../../../../../constants/requestStatus";

import { loadUser, submitCreate, submitUpdate, submitDisable, submitAcknowledgeRequestData } from "./requestLogic";

export default (config) => {
    let {
        action_type,
        payload,
        id,
        created_at,
        rejection_reason: rejectionReason,
        rejection_acknowledge_at: rejectionAcknowledgeAt,
        target_user_id: targetUserId = '',
        status: requestStatus,
    } = config?.initialModel || '';
    const targetId = id || ','

    let {formData, old} = loadUser(payload);

    const createdAt = tsToDate(created_at);
    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ

    const isAcknowledged = checkAcknowledged();

    function checkAcknowledged() {
        return rejectionAcknowledgeAt !== null;
    };



    return {
        targetId,

        formData,
        old,
        createdAt,

        rejectionReason,
        isAcknowledged: isAcknowledged || '', // 再申請送信ボタンと確認ボタンのオンオフ
        statusLabel: '却下',
        statusClass: 'font-bold text-amber-800',
        errors: {},

        resultData: '',
        backUrl,

        async submit() {
            if(action_type === 'create') {
                await this.create();
            } else if(action_type === 'update') {
                await this.update();
            }
        },

        async create() {
            try {
                const response = await submitCreate(this.formData);
                // 成功処理
                alert(response.data.message);
                window.location.replace(backUrl);
            } catch (e) {
                this.handleRequestError(e);
            }
        },

        async update() {
            try {
                const response = await submitUpdate(targetUserId, this.formData);
                //成功処理
                alert(response.data.message);
                window.location.replace(backUrl);
            } catch (e) {
                this.handleRequestError(e);
            }
        },

        // 再申請したレコードにreappliedステータsu

        // 申請が却下されたことを確認したボタン
        async submitAcknowledge() {
            try {
                const response = await submitAcknowledgeRequestData(targetId);

                alert(response.data.message);
                // 再申請の送信ボタンをactive
                this.isAcknowledged = true;
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

