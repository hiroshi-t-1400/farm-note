// /var/www/src/resources/js/components/modules/admin/users/users
import { tsToDate } from "../../../../../utils/date";
import { offsetPagenation } from "../../../../../api/transformers/pagenation";

import { ROLES } from "../../../../../constants/roles";
import { REQUEST_STATUS } from "../../../../../constants/requestStatus";
import { ACTION_LABELS } from "../../../../../constants/actions";

export default (config) => {

    const data = config?.initialModels?.data;
    const path = config?.initialModels?.path;

    const statusClass = {
        default: 'text-gray-500 text-sm',
        rejected: 'font-bold text-amber-800',
        pending: 'font-bold text-blue-500',
    };

    const indexData = data.map(r => ({
        id: r.id,
        targetUserId: r.target_user_id,
        actionType: r.action_type,
        actionLabel: ACTION_LABELS[r.action_type],
        username: r.payload.name,
        createdAt: tsToDate(r.created_at),
        role: r.payload.role,
        roleLabel: ROLES[r.payload.role],
        rejectionReason: r.rejection_reason,
        showUrl: `${window.location.origin}/admin/requests/users/record/edit/${r.id}`,

        status: r.status,
        statusLabel: REQUEST_STATUS[r.status],
        statusCss: statusClass[r.status],

        requesterId: r.requester.id,
        requesterName: r.requester.name
    }));

    return {
        indexData: indexData,

        ...offsetPagenation(config?.initialModels),

        hasRejected() {
            return this.indexData.find(d => d.status === 'rejected');
        }
    }
}

