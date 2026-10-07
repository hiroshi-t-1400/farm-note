// /var/www/src/resources/js/components/modules/notifications.js

// import { pagenation } from "../../api/transformers/pagenation";
import { submitMarkAsReadSome } from "./notificationLogic";

export default (config) => {

    const notificationDatas = config?.initialModel.map(n => {
        const { data, ...rest } = n;

        const displayDate = pastDays(n?.created_at);

        return {
            ...data,
            ...rest,
            displayDate,
        }
    });

    const hasUnreadNotifications
        = notificationDatas?.length > 0 ?
            true
            : false;

    // 日付を 今日or昨日or2026-10-10 のように段階的に表示
    function pastDays(date) {
        const createdAt = new Date(date);
        const today = new Date();

        const dayMS = 24 * 60 * 60 * 1000; // １日 as ミリ秒

        const diffMS = today - createdAt;

        if(diffMS > dayMS * 2) {
            return date; // 丸２日以上経過していたら日付の文字列
        } else if(diffMS > dayMS) {
            return '昨日';
        } else {
            return '今日';
        }
    };

    return {
        notificationDatas: notificationDatas,
        hasUnreadNotifications: hasUnreadNotifications,

        hasNotificationApproved() {
            return this.notificationDatas?.some(data => data.status === 'approved') || '';
        },

        async markApprovedNotificationsAsRead() {
            const notificationIds = this.getUUID('approved');

            try {
                const response = await submitMarkAsReadSome(
                    notificationIds
                );

                // 成功処理
                // 既読化した通知を非表示にする
                this.notificationDatas = this.disableNotifications('approved');

                console.log({'response':response});
                alert(response.data.message);
            } catch(e) {
                this.handleApplicationError(e);
            }
        },

        /**
         * @param {String} status
         */
        getUUID(status) {
            return notificationDatas.filter(
                data => data.status === status
            ).map(
                filtered => (
                    filtered.id
                ));
        },

        disableNotifications(status) {
            return notificationDatas.filter(
                data => data.status !== status
            );
        },

        handleApplicationError(error) {
            console.log({ 'error': error });
            if (error.type === 'validation') {
                this.errors = error.errors;
                alert(error.message);
                return;
            }
            alert(error.message);
        },
    }
}
