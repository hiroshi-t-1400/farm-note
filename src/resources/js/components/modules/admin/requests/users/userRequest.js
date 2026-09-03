// /var/www/src/resources/js/components/modules/admin/requests/users/create.js

import { getBackUrl } from "../../../../../utils";
import { submitCreate, submitUpdate, loadUser } from "./requestLogic";
import handleRequestError from "./error";

export default (config) => {

    const actionType = config?.initialModel?.['actionType'] || '';
    const targetUser = config?.initialModel?.['targetUser'] || '';
    const targetUserId = targetUser?.id || '';

    const resultData = {};

    const isUpdate = actionType === 'update' ? true : false;

    const backUrl = buildBackUrl();
    function buildBackUrl() {
        if (actionType === 'create') {
            return getBackUrl(`${location.origin}/dashboard`);
        }
        return getBackUrl(`${location.origin}/admin/requests/users`);
    };

    const {old, formData} = loadUser(targetUser);

    return {
        formData,
        resultData,

        old, // for update
        isUpdate,
        passwordMessage: ' ＊変更しない場合は空欄',

        errors: {},

        backUrl,
        actionType,

        async submit() {
            try {
                if (actionType === 'create') {
                    const response = await submitCreate(this.formData);
                    // 成功処理
                    this.resultData = {...this.formData};
                        // 初期化
                    this.formData = loadUser().formData;
                    alert(response.data.message);

                } else if (actionType === 'update') {
                    const response = await submitUpdate(targetUserId, this.formData);
                    // 設定した戻り画面:index へ画面遷移
                    alert(response.data.message);
                    window.location.replace(this.backUrl);
                }

            } catch (e) {
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

