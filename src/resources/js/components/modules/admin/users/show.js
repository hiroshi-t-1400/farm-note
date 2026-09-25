// /var/www/src/resources/js/components/modules/admin/users/show.js

import { tsToDate } from "../../dashboard/utils";

import { ROLES } from "../../../../constants/roles";
import { USER_STATUS } from "../../../../constants/userStatus";

import { submitDisable } from "../applications/users/applicationLogic";

export default (config) => {

    let {id: userId, name: username, login_id: loginId, email, created_at, updated_at, roles, status, actionType} = config?.initialModels || '';

    const createdAt = tsToDate(created_at);
    const updatedAt = tsToDate(updated_at);

    const roleLabel = ROLES[roles[0]?.['name']];
    const statusLabel = USER_STATUS[status];
    const isActive = checkStatus();
    let statusClass = '';

    const editUrl =`${location.origin}/admin/applications/users/update/${userId}`

    let backUrl = getBackUrl();

    // dashboadなどから直接アクセスされている場合は元の画面に戻る
    function getBackUrl() {
        const isRefEdit = document.referrer.includes('update');

        if (!isRefEdit && document.referrer !== location.href) {
            return document.referrer;
        }
        return `${location.origin}/users`;
    };

    function checkStatus() {
        if (status !== 'active') {
            statusClass = 'text-red-500';
            return false;
        }
        return true;
    };


    return {
        userId,
        username,
        loginId,
        email,
        createdAt,
        updatedAt,
        actionType,
        roleLabel,
        statusLabel,

        isActive,
        statusClass,

        backUrl,
        editUrl,

        errors: {},

        // 閲覧中のユーザーを削除する申請を送信
        async submitDelete() {
            if (!confirm('ユーザー情報の削除を申請してよろしいですか？')) {
                return;
            }

            try {
                const response = await submitDisable(this.userId);

                // 成功処理
                alert(response.data.message);
                window.location.replace(backUrl);
            } catch(e) {
                this.handleApplicationError(e);
            }
        },

        handleApplicationError(error) {
            console.log({ 'error': error });
            alert(error.message);
        },
    }
}

