// /var/www/src/resources/js/components/modules/axiosNotificationsClient.js

import axios from "axios";
import applyCaseMiddleware from "axios-case-converter";

const rawAxios = axios.create({
    baseURL: '/notifications',
    headers: {
        'X-requester-With': 'XMLHttpRequest',
    },
    withCredentials: true,
    withXSRFToken: true,
})

// axios-case-converterを適用
const axiosNotificationClient = applyCaseMiddleware(rawAxios, { ignoreHeaders:true });


axiosNotificationClient.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosNotificationClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // エラーハンドリング:完全に共通化したものがあれば
        return Promise.reject(error);
    }
)

export default axiosNotificationClient;
