// /var/www/src/resources/js/utils/uuid.js

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
