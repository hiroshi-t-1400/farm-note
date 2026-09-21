// /var/www/src/resources/js/components/modules/admin/requests/users/delete.js

import { submitDeleteRequestData } from "./requestLogic";


export default async function submitDisable(requestDataId) {
    try {

        const response = await submitDeleteRequestData(requestDataId);
    } catch (e) {
        handleRequestError(e);
    }
}
