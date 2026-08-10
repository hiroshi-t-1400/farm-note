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

// @param JsonResponse meta{Object}
export function pagenation({path, per_page, next_cursor, prev_cursor}) {

    // const enable = 'hover:text-gray-900 hover:underline transition-colors duration-150';
    // const disable = 'text-gray-300 cursor-not-allowed select-none';

    // let nextClass, prevClass;

    // const classes = {
    //     next: enable,
    //     prev: enable
    // };

    // if (!next_cursor) {
    //     classes.next = disable;
    // }
    // if (!prev_cursor) {
    //     classes.prev = disable;
    // }

    return {
        next: next_cursor !== null ? `${path}?cursor=${next_cursor}` : '',
        prev: prev_cursor !== null ? `${path}?cursor=${prev_cursor}` : '',
        perPage: per_page,
        // classes,
    }
};
