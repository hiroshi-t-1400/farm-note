// /var/www/src/resources/js/components/modules/admin/approvals/axiosUserApprovalClient.js

import axios from "axios";
import applyCaseMiddleware from "axios-case-converter";

const rawAxios = axios.create({
    baseURL: '/admin/approvals/users',
    headers: {
        'X-requester-With': 'XMLHttpRequest',
    },
    withCredentials: true,
    withXSRFToken: true,
})

// axios-case-converterを適用
const axiosUserApprovalClient = applyCaseMiddleware(rawAxios, { ignoreHeaders:true });


axiosUserApprovalClient.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosUserApprovalClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // エラーハンドリング:完全に共通化したものがあれば
        return Promise.reject(error);
    }
)

export default axiosUserApprovalClient;
