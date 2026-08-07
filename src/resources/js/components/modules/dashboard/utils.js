// src/resources/js/components/modules/work-logs/utils.js

// @param ts {string}
export function tsToDate(ts, mode='date') {
    const newTS = Date.parse(ts);
    const today = new Date(newTS);

    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const HH = String(today.getHours());
    const MM = String(today.getMinutes());

    if (mode == 'date')return `${yyyy}-${mm}-${dd}`;
    if (mode == 'Hi') return `${yyyy}-${mm}-${dd} ${HH}:${MM}`;
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

// Camel case文字列を主にバックエンド用のスネークケースに変換する
export function strToSnakeCase(str) {
    return str.split(/(?=[A-Z])/).join('_').toLowerCase();
};

export function objToSnakeCase(obj) {
    const result = {};
    Object.keys(obj).forEach(key => {
        result[strToSnakeCase(key)] = obj[key];
    });
};

