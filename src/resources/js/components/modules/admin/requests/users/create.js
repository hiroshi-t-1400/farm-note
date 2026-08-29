// /var/www/src/resources/js/components/modules/admin/requests/users/create.js

import { ROLES } from "../../../../../constants/roles";
import { getBackUrl } from "../../../../../utils";
import { requestLogic } from "./requestLogic";

export default (config) => {

    const actionType = config?.initialModel?.['actionType'] || '';
    const targetUser = config?.initialModel?.['targetUser'] || '';
    const targetUserId = targetUser?.id || '';

    const backUrl = buildBackUrl();

    const formData = loadUser();

    const resultData = {};

    let old = buildOld();
    const isUpdate = actionType === 'update' ? true : false;

    function buildBackUrl() {
        if (actionType === 'create') {
            return getBackUrl(`${location.origin}/dashboard`);
        }
        return getBackUrl(`${location.origin}/admin/requests/users`);
    };

    function loadUser() {
        return {
            email: targetUser?.email || '',
            password: '',
            loginId: targetUser?.login_id || '',
            username: targetUser?.name || '',
            role: targetUser?.roles?.[0]?.['name'] || 'worker',
        }
    };

    function buildOld() {
        const get = formData == {} ? {} : {...formData};
        get.roleLabel = ROLES[get.role];
        return get;
    };

    return {
        formData,
        resultData,

        old, // for update
        isUpdate,
        passwordMessage: ' ＊変更しない場合は空欄',

        errors: {},

        backUrl,
        actionType,

        ...requestLogic(),



        async submitUpdate() {
            this.buildPayload();
            const actionType = this.actionType;
            this.submitStore(() => {
                this.formData = {};

                alert(response.data.message);
                window.location.replace(this.backUrl);
            });
        },

        async submitCreate() {
            this.buildPayload();
            const actionType = this.actionType;
            this.submitStore(() => {
                this.resultData = requestData;
                this.formData = {};

                alert(response.data.message);
            });
        },

        // バリデーションエラーメッセージを返す
        getError(field) {
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

