// /var/www/src/resources/js/components/modules/admin/users/users
import { tsToDate } from "../../../../utils";
import { offsetPagenation, pagenation } from "../../../../api/transformers/pagenation";
import { ACTION_LABELS } from "../../../../constants/actions";

export default (config) => {

    const data = config?.initialModels?.data;
    const path = config?.initialModels?.path;
console.log(config?.initialModels);
    const indexData = data.map(r => ({
        id: r.id,
        targetUserId: r.target_user_id,
        actionType: r.action_type,
        actionLabel: ACTION_LABELS[r.action_type],
        username: r?.payload?.name || r?.target_user?.name,
        createdAt: tsToDate(r.created_at),
        parentApplicationId: r.parent_application_id || '',
        applicationStatus: r.status,
        rejectionReason: r.rejection_reason,
        showUrl: `${path}/${r.id}`,

        requesterId: r.requester.id,
        requesterName: r.requester.name
    }));

    return {
        indexData: indexData,
        ...offsetPagenation(config?.initialModels),
    }
}

