import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // WSL+Docker環境でHMRを有効にするための設定（重要）
    server: {
        // HMRプロキシ設定
        hmr: {
            // 外部からアクセスできるホスト名を設定
            host: "192.168.11.20",
            // 必要であればポートも指定。今回はデフォルト値である5173をそのまま使用するのでコメントアウト
            // port: 5173
        },
        watch: {
            usePolling: true,
            ignored: ['**/storage/framework/views/**'],
        },
        // 開発環境のためWindowsからアクセスできるようにtrueを返させる
        host: true,

    },
});
