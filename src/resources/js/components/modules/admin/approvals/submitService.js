// /var/www/src/resources/js/components/modules/admin/approvals/submitService.js

import axiosUserApprovalClient from "./axiosUserApprovalClient";

const submitService = {

    // 申請を承認
    approveApplication(applicationId) {
        return axiosUserApprovalClient.patch(
            `/${applicationId}/approve`,
        );
    },

    // 申請を却下
    rejectApplication(applicationId, rejectionReason) {
        return axiosUserApprovalClient.patch(
            `/${applicationId}/reject`,
            rejectionReason,
        );
    },
};

export default submitService;
