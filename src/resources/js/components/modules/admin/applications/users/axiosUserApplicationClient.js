// /var/www/src/resources/js/components/modules/admin/applications/users/axiosUserApplicationClient.js

import axios from "axios";
import applyCaseMiddleware from "axios-case-converter";

const rawAxios = axios.create({
    baseURL: '/admin/applications/users',
    headers: {
        'X-requester-With': 'XMLHttpRequest',
    },
    withCredentials: true,
    withXSRFToken: true,
})

// axios-case-converterを適用
const axiosUserApplicationClient = applyCaseMiddleware(rawAxios, { ignoreHeaders:true });


axiosUserApplicationClient.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosUserApplicationClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // エラーハンドリング:完全に共通化したものがあれば
        return Promise.reject(error);
    }
)

export default axiosUserApplicationClient;
