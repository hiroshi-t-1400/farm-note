// /var/www/src/resources/js/components/modules/admin/requests/users/create.js

import { ROLES } from "../../../../../constants/roles";
import { getBackUrl } from "../../../../../utils";
import { buildPayload, submit } from "./requestLogic";

export default (config) => {

    const actionType = config?.initialModel?.['actionType'] || '';
    const targetUser = config?.initialModel?.['targetUser'] || '';
    const targetUserId = targetUser?.id || '';
console.log({'targetUser':targetUser});
    const backUrl = buildBackUrl();

    const formData = loadUser();

    const resultData = {};

    let old = buildOld();
    const isUpdate = actionType === 'update' ? true : false;

    function submitRoute() {
        return actionType === 'create'
            ? `/admin/requests/users/store-create`
            : `/admin/requests/users/${targetUserId}/store-update`;
    }

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

        submitRoute: submitRoute(),

        old, // for update
        isUpdate,
        passwordMessage: ' ＊変更しない場合は空欄',

        errors: {},

        backUrl,
        actionType,

        async submitStore() {
            const payload = buildPayload(this.formData);
                try {
                    const response = await submit(
                        this.submitRoute,
                        payload
                    );

                    // 成功処理
                    this.formData = {};
                    alert(response.data.message);

                    if (actionType === 'create') {
                        // 申請画面に留まり直前の申請内容をレンダリングする
                        this.resultData = payload;
                    } else if (actionType === 'update') {
                        // 設定した戻り画面:index へ画面遷移
                        window.location.replace(this.backUrl);
                    }

                } catch(error) {
                    this.handleRequestError(error);
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

        // async submitCreate() {
        //     this.buildPayload();
        //     const actionType = this.actionType;
        //     this.submitStore(() => {
        //         this.resultData = requestData;
        //         this.formData = {};

        //         alert(response.data.message);
        //     });
        // },

        // // ユーザー情報変更申請ボタンクリックのイベント
        // async submitUpdate() {
        //     this.builtPayload;
        //     const actionType = this.actionType;
        //     this.submitStore(() => {
        //         this.formData = {};

        //         alert(response.data.message);
        //         window.location.replace(this.backUrl);
        //     });
        // },

        // バリデーションエラーメッセージを返す
        getError(field) {
            // bladeの属性はusernameとしているためここで変換する
            if (field === 'username') return this.errors?.['name'] || null;
            return this.errors?.[field] || null;
        },
    }
}

