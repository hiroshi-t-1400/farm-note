// /var/www/src/resources/js/components/modules/admin/users/users
import { tsToDate } from "../../../../../utils/date";
import { offsetPagenation } from "../../../../../api/transformers/pagenation";

import { ROLES } from "../../../../../constants/roles";
import { APPLICATION_STATUS } from "../../../../../constants/applicationStatus";
import { ACTION_LABELS } from "../../../../../constants/actions";

export default (config) => {
    const data = config?.initialModels?.data;
    const path = config?.initialModels?.path;

    const statusClass = {
        default: 'text-gray-500 text-sm',
        rejected: 'font-bold text-amber-800',
        pending: 'font-bold text-blue-500',
    };

    const indexData = data.map(r => {
        let reviewStatus = '';
        let reviewCss = '';

        if(r.reapplied_at) {
            reviewStatus = '再申請済み';
            reviewCss = statusClass.pending;
        } else if(r.rejection_acknowledge_at) {
            reviewStatus = '確認済み';
            reviewCss = statusClass.rejected
        }

        return {
            id: r.id,
            targetUserId: r.target_user_id,
            actionType: r.action_type,
            actionLabel: ACTION_LABELS[r.action_type],
            username: r?.payload?.name || r?.target_user?.name,
            createdAt: tsToDate(r.created_at),
            role: r?.payload?.role || r?.target_user?.role,
            roleLabel: ROLES[r?.payload?.role || r?.target_user?.role],
            rejectionReason: r.rejection_reason,
            showUrl: `${window.location.origin}/admin/applications/users/${r.id}/edit`,

            status: r.status,
            statusLabel: APPLICATION_STATUS[r.status],
            reviewStatus: reviewStatus,
            reviewCss:reviewCss,
            statusCss: statusClass[r.status],

            requesterId: r.requester.id,
            requesterName: r.requester.name
        };
    });

    return {
        indexData: indexData,
        ...offsetPagenation(config?.initialModels),

        hasRejected() {
            return this.indexData.find(d => d.status === 'rejected');
        }
    }
}

