// src/resources/js/components/modules/work-logs/utils.js

export function tsToDate(ts, mode='date') {
    //時分 H:i が必要であればmode="hm"を設計する
    const newTS = Date.parse(ts);
    const today = new Date(newTS);

    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd}`;
};


// UUIDを安全に生成するヘルパーメソッド（非セキュア環境互換）
export function generateUUID() {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    // 非HTTPS（http://192.168.x.x 等）環境向けのフォールバック
    return 'xxxx-xxxx-4xxx-yxxx-xxxx'.replace(/[xy]/g, (c) => {
        const r = (Math.random() * 16) | 0;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
};
