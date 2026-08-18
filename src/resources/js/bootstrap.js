import axios from 'axios';
import applyCaseMiddleware from 'axios-case-converter';

// Axiosインスタンスを作成
const rawAxios = axios.create({
    baseURL: '/',
    headers: {
        'X-requester-With': 'XMLHttpRequest',
    },
    withCredentials: true, // SanctumのCookie認証のため
    withXSRFToken: true, // Laravel 11/12でのCSRFトークン自動送信
})

// axios-case-converterを適用
const http = applyCaseMiddleware(rawAxios);

// Alpine.jsや他のファイルから use できるようグローバル化
window.http = http;
