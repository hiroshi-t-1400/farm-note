// /var/www/src/resources/js/components/modules/admin/users/index.js

import { tsToDate } from "../../dashboard/utils";
import { offsetPagenation } from "../../../../api/transformers/pagenation";

import { ROLES } from "../../../../constants/roles";
import { USER_STATUS } from "../../../../constants/userStatus";

export default (config) => {
    const data = config?.initialModels?.data;

    const indexData = data.map(u => ({
        userId: u.id,
        username: u.name,
        role: u.roles[0]?.['name'],
        roleLabel: ROLES[u.roles[0]?.['name']],
        showUrl: `${window.location.origin}/users/${u.id}`,

        status: u.status,
        statusLabel: USER_STATUS[u.status],
        createdAt: tsToDate(u.created_at),
        updatedAt: tsToDate(u.updated_at),
        // statusCss: statusClass[r.status],
    }));

    return {
        indexData: indexData,

        ...offsetPagenation(config?.initialModels),
    }
}

