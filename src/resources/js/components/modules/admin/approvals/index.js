// /var/www/src/resources/js/components/modules/admin/users/users
import { tsToDate } from "../../dashboard/utils";

export default (config) => {

    const actionLabel = {
        create: '登録',
        update: '更新',
        delete: '削除'
    };

    const roleLabel = {
        owner: 'オーナー',
        manager: '管理者',
        worker: '一般ユーザー'
    };

    const data = config?.initialModels?.data;
    const path = config?.initialModels?.path;

    const indexData = data.map(r => ({
        id: r.id,
        targetUserId: r.target_user_id,
        actionType: r.action_type,
        actionLabel: actionLabel[r.action_type],
        username: r.payload.name,
        createdAt: tsToDate(r.created_at),
        role: r.payload.role,
        roleLabel: roleLabel[r.payload.role],
        rejectionReason: r.rejection_reason,
        showUrl: `${path}/${r.id}`,

        requesterId: r.requester.id,
        requesterName: r.requester.name
    }));


    return {
        indexData: indexData,
    }
}

