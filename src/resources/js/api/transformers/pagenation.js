// /var/www/src/resources/js/api/transformers/pagenation.js

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

export function offsetPagenation({path, per_page, next_page_url, prev_page_url}) {
    return {
        next: next_page_url,
        prev: prev_page_url,
        perPage: per_page,
    }
};
