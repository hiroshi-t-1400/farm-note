// /var/www/src/resources/js/utils/getUrl.js

/**
 * 直前のページのURLを返す。直前のページが現在のページであった場合指定した任意のURLを返す。
 *
 * @param {String} url 任意のURL
 * @returns {String}
 */
export function getBackUrl(url) {
    return document.referrer !== location.href
    ? document.referrer
    : url;
};
