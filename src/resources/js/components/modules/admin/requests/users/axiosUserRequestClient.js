// /var/www/src/resources/js/components/modules/admin/requests/users/axiosUserRequestClient.js

import axios from "axios";
import applyCaseMiddleware from "axios-case-converter";

const rawAxios = axios.create({
    baseURL: '/admin/requests/users',
    headers: {
        'X-requester-With': 'XMLHttpRequest',
    },
    withCredentials: true,
    withXSRFToken: true,
})

// axios-case-converterを適用
const axiosUserRequestClient = applyCaseMiddleware(rawAxios, { ignoreHeaders:true });


axiosUserRequestClient.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosUserRequestClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // エラーハンドリング:完全に共通化したものがあれば
        return Promise.reject(error);
    }
)

export default axiosUserRequestClient;
