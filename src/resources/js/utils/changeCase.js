// /var/www/src/resources/js/utils/changeCase.js

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
