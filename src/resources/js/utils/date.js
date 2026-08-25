// /var/www/src/resources/js/utils/date.js

//時分 H:i が必要であればmode="hm"を設計する
export function tsToDate(ts, mode='date') {
    const newTS = Date.parse(ts);
    const today = new Date(newTS);

    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd}`;
};
