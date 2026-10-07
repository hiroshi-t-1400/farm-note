// /var/www/src/resources/js/components/modules/axiosNotificationsClient.js

import axiosNotificationClient from "./axiosNotificationClient";

const submitService = {

    // 通知を１件read
    readNotification(notificationId) {
        return axiosNotificationClient.post(
            `/${notificationId}/read`
        );
    },

    /**
     * 通知を複数read
     * @param {Array} notificationIds
     */
    readNotifications(notificationIds) {
        return axiosNotificationClient.patch(
            `/read`,
            { notificationIds }
        );
    },

    // 通知を全件read
    readAllNotifications() {
        return axiosNotificationClient.post(
            `/read`
        );
    },
};

export default submitService;
