// /var/www/src/resources/js/components/modules/admin/applications/users/submitService.js

import axiosUserApplicationClient from "./axiosUserApplicationClient";

const submitService = {

    // 申請ロジックモデルへ、ユーザー情報新規登録・削除申請送信
    createApplication(payload) {
        return axiosUserApplicationClient.post(
            `/store-create`,
            payload,
        );
    },

    // ユーザーのDisabe as deleteの申請
    destroyApplication(targetUserId) {
        return axiosUserApplicationClient.post(
            `/${targetUserId}/store-disable`
        );
    },

    // 既存ユーザー情報の更新
    // login_id,emailのuniqueルールのためuser情報をモデルバインディングで取得
    updateApplication(targetUserId, payload) {
        return axiosUserApplicationClient.post(
            `/${targetUserId}/store-update`,
            payload,
        );
    },

    // 申請内容の更新
    // 更新申請の変更であればuser情報の取得が必要
    updateApplicationData(applicationDataId, targetUserId = null, payload) {
        let targetUrl = '';
        if(targetUserId === null) {
            targetUrl = `/${applicationDataId}/update`;
        } else {
            targetUrl = `/${applicationDataId}/update/${targetUserId}`;
        }
        return axiosUserApplicationClient.patch(
            targetUrl,
            payload,
        );
    },

    // 申請の削除
    deleteApplicationData(applicationDataId) {
        return axiosUserApplicationClient.delete(
            `/${applicationDataId}/destroy/`
        );
    },

    // 却下された申請を確認
    acknowledgeApplicationData(applicationDataId) {
        return axiosUserApplicationClient.patch(
            `/${applicationDataId}/acknowledge/`
        );
    },

    // 再申請の履歴を記録
    reapplyHistory(parentApplicationId, childApplicationId) {
        return axiosUserApplicationClient.patch(
            `/${parentApplicationId}/${childApplicationId}/reapply`
        );
    },

};

export default submitService;
