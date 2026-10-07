// /var/www/src/resources/js/components/modules/notifications.js

// import { pagenation } from "../../api/transformers/pagenation";

export default (config) => {
console.log(config?.initialModel);

    const notificationDatas = config?.initialModel.map(n => {

        n.data.displayDate = pastDays(n?.created_at);

        return n.data
    });

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


console.log({'notificationDatas':notificationDatas});
    return {
        notificationDatas: notificationDatas,
    }
}
