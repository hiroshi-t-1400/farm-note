// /var/www/src/resources/js/components/modules/admin/requests/users/delete.js

import { submitDeleteRequestData } from "./requestLogic";
import handleRequestError from "./error";


export default async function submitDisable(requestDataId) {
    try {

        const response = await submitDeleteRequestData(requestDataId);
    } catch (e) {
        handleRequestError(e);
    }
}
