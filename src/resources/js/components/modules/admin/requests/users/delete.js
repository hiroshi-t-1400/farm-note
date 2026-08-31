// /var/www/src/resources/js/components/modules/admin/requests/users/edit.js

import { getBackUrl } from "../../../../../utils";

import { submit } from "./requestLogic";

export function deleteUserRequest () {

    const backUrl = getBackUrl(`${location.origin}/admin/requests/users`); // 戻る遷移先はindexページ

    return {
        errors: {},

        async submitDelete(id) {

            try {
                const response = await submit(
                    `${window.location.origin}/admin/requests/users/destroy${id}`,
                    null,
                    'delete',
                );

                // ----------------------------------------------------
                // 成功（200 OK系）
                // ----------------------------------------------------
                alert(response.data.message);
                window.location.replace(backUrl);

            } catch (error) {
                handleRequestError(error);
            }
        },


    }
}

function handleRequestError(error) {
    console.log({'error':error});
    alert(error.message);
};
