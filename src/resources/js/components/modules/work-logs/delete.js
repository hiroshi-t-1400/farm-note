// src/resources/js/components/modules/work-logs/delete.js

/**
 * @param {Int|Array} ids.length > 0
 * @param {String} backUrl (ex) url('dashboard')
 */
export async function deleteWorkLog (ids, backUrl = null) {
        if (window.navigator.onLine == false) {
            alert(' ネットワーク通信エラー。ネットワーク通信ができる場所で再度お試しください。 ');
            return;
        }

        // オンライン時処理
        let timeoutId = null;

        try {
            const controller = new AbortController();
            timeoutId = setTimeout(() => controller.abort(), 5000); // 5000ms to timeout

            let response;

            response = await fetch(`/work-logs/delete/${ids}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                signal: controller.signal // controle timeout
            });
            // if (/* 一括削除フラグ */) {
            //     response = await fetch(`/work-logs/destroy/${workLog.id}`, {
            //         method: 'DELETE',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'Accept': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         signal: controller.signal // controle timeout
            //     });
            // } else {
            //     response = await fetch('/work-logs/create', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'Accept': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         body: JSON.stringify(payload),
            //         signal: controller.signal // controle timeout
            //     });
            // }

            clearTimeout(timeoutId); // 通信成功したらタイマーを解除

            // ----------------------------------------------------
            // 1. レコード非存在のエラー
            // ----------------------------------------------------
            if (response.status === 404) {
                const data = await response.json();

                alert(' 日誌が存在しないか、すでに削除されているようです。 ' + (data.message));
                return;
            }
            // ----------------------------------------------------
            // 2. その他のサーバーエラー (500系や404など
            // ----------------------------------------------------
            if (!response.ok) {
                console.error('サーバーエラーが発生しました。Status:', response.status);
                alert('サーバーエラーが発生しました。\n時間をおいて再度お試しいただくか、管理者へお問い合わせください。（Status: ' + response.status + '）');

                return;
            }

            // ----------------------------------------------------
            // 3. 削除成功処理 (200 OK系)
            // ----------------------------------------------------
            const data = await response.json(); // 成功レスポンスのJSONを解析

            alert(' 日誌を削除しました。 ');

            // 遷移するページに指定があるとき
            // 指定がない ==> indexで非同期削除
            if (backUrl == 'index') {
                return;
            } else if (backUrl) {
                return window.location.replace(backUrl);
            } else {
                returnwindow.location.replace(data.redirect_url);
            }

        } catch (error) {

            if (timeoutId) clearTimeout(timeoutId); // 念のためにタイマーを解除

            // デバッグ用にエラーの詳細を出力
            if (error.name === 'AbortError') {
                console.error('通信エラー：　タイムアウト（５秒）が発生しました。', error);
            }
        }
};

