// /var/www/src/resources/js/components/modules/admin/requests/users/submitService.js

import axiosUserRequestClient from "./axiosUserRequestClient";

const submitService = {

    // 申請ロジックモデルへ、ユーザー情報新規登録・削除申請送信
    createRequest(payload) {
        return axiosUserRequestClient.post(
            `/store-create`,
            payload,
        );
    },

    // ユーザーのDisabe as deleteの申請
    destroyRequest(targetUserId) {
        return axiosUserRequestClient.post(
            `/${targetUserId}/store-disable`
        );
    },

    // 既存ユーザー情報の更新
    // login_id,emailのuniqueルールのためuser情報をモデルバインディングで取得
    updateRequest(targetUserId, payload) {
        return axiosUserRequestClient.post(
            `/${targetUserId}/store-update`,
            payload,
        );
    },

    // 申請内容の更新
    // 更新申請の変更であればuser情報の取得が必要
    updateRequestData(requestDataId, targetUserId = null, payload) {
        let targetUrl = '';
        if(targetUserId === null) {
            targetUrl = `/${requestDataId}/update`;
        } else {
            targetUrl = `/${requestDataId}/update/${targetUserId}`;
        }
        return axiosUserRequestClient.patch(
            targetUrl,
            payload,
        );
    },

    // 申請の削除
    deleteRequestData(requestDataId) {
        return axiosUserRequestClient.delete(
            `/${requestDataId}/destroy/`
        );
    },

    // 却下された申請を確認
    acknowledgeRequestData(requestDataId) {
        return axiosUserRequestClient.patch(
            `/${requestDataId}/acknowledge/`
        );
    },

    // 再申請の履歴を記録
    reapplyHistory(requestDataId) {
        return axiosUserRequestClient.patch(
            `/${requestDataId}/reapply`
        );
    },

};

export default submitService;
