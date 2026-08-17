// src/resources/js/components/modules/auth/login.js

export default (config = '') => {

    return {
        email: '',
        password: '',
        passwordConfirmation: '',
        loginId: '',
        username: '',
        errors: {},

        sentStatus: config?.initialData?.status || '',

        // X-XSRF-TOKENをfetchで送るためにcoockieからXSRFの文字列を基準に切り取って取得
        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        },


        async submitLogin () {
            let timeoutId = null;

            try{
                // set 5000ms to timeout
                const controller = new AbortController();
                timeoutId = setTimeout(() => controller.abort(), 5000);

                await fetch('/sanctum/csrf-cookie', {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                let response;

                response = await fetch('/login', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN')
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    }),
                    signal: controller.signal
                });

                clearTimeout(timeoutId); // タイマーを解除


                // ----------------------------------------------------
                // 1. バリデーションエラー（422）と422に結び付けた認証情報の誤りのハンドリング
                // ----------------------------------------------------
                if (response.status === 422) {
                    const data = await response.json();
                    this.errors = data.errors || {};
                    alert('ログインに失敗しました : ' + (data.message || '入力内容を確認してください。'));
                    return;
                }

                // ----------------------------------------------------
                // 2. その他のサーバーエラー（500系や404など
                // ----------------------------------------------------
                if (!response.ok) {
                    console.error('サーバーエラーが発生しました。');
                    alert('サーバーエラーが発生しました。');

                    return;
                }

                // ----------------------------------------------------
                // 3. 認証成功（200 OK系）
                // ----------------------------------------------------
                const data = await response.json();

                return window.location.href = '/dashboard';

            } catch(e) {

                if (timeoutId) clearTimeout(timeoutId);

                // DevToolsにエラー出力
                if (e.name === 'AbortError') {
                    console.error('通信エラー： タイムアウト（５秒）が発生しました。', e);
                } else {
                    console.error('不明な通信エラー', e);
                }

                alert('通信エラーのためログインに失敗しました。')
            }
        },


        async submitLogout() {
            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN')
                    }
                });

                if (!response.ok) {
                    alert('ログアウトに失敗しました。');
                    return;
                }

                // ログアウト成功時、ログイン画面へ遷移
                window.location.href = '/login';

            } catch (e) {
                console.error('通信エラーが発生しました', e);
                alert('通信エラーのためログアウトできませんでした。');
            }
        },

        async submitRegister () {
            let timeoutId = null;

            try{
                // set 5000ms to timeout
                const controller = new AbortController();
                timeoutId = setTimeout(() => controller.abort(), 5000);

                let response;

                response = await fetch('/register', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN')
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.passwordConfirmation,
                        login_id: this.loginId,
                        name: this.username
                    }),
                    signal: controller.signal
                });

                clearTimeout(timeoutId); // タイマーを解除


                // ----------------------------------------------------
                // 1. バリデーションエラー（422）と422に結び付けた認証情報の誤りのハンドリング
                // ----------------------------------------------------
                if (response.status === 422) {
                    const data = await response.json();
                    this.errors = data.errors || {};

                    alert('アカウント登録に失敗しました。 : ' + (data.message || '入力内容を確認してください。'));
                    return;
                }

                // ----------------------------------------------------
                // 2. 連続送信（429）のハンドリング
                // ----------------------------------------------------
                if (response.status === 429) {
                    const data = await response.json();
                    this.errors = data.errors || {};
                    alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
                    return;
                }

                // ----------------------------------------------------
                // 3. その他のサーバーエラー（500系や404など
                // ----------------------------------------------------
                if (!response.ok) {
                    console.error('サーバーエラーが発生しました。');
                    alert('サーバーエラーが発生しました。');

                    return;
                }

                // ----------------------------------------------------
                // 4. 認証成功（200 OK系）
                // ----------------------------------------------------
                const data = await response.json();

                // メール認証のメール送信・サジェスト画面
                return window.location.href = '/email/verify';

            } catch(e) {

                if (timeoutId) clearTimeout(timeoutId);

                // DevToolsにエラー出力
                if (e.name === 'AbortError') {
                    console.error('通信エラー： タイムアウト（５秒）が発生しました。', e);
                } else {
                    console.error('不明な通信エラー', e);
                }

                alert('通信エラーのためアカウント登録に失敗しました。')
            }
        },


        /**
         * ユーザーの操作による認証メールの再送信
         * @returns
         */
        async submitVerifyEmail () {
            let timeoutId = null;

            try{
                // set 5000ms to timeout
                const controller = new AbortController();
                timeoutId = setTimeout(() => controller.abort(), 5000);

                let response;

                response = await fetch('/email/verification-notification', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN')
                    },
                    signal: controller.signal
                });

                clearTimeout(timeoutId); // タイマーを解除

                // ----------------------------------------------------
                // 1. 連続送信（429）のハンドリング
                // ----------------------------------------------------
                if (response.status === 429) {
                    const data = await response.json();
                    this.errors = data.errors || {};
                    alert('送信操作が多すぎます。しばらく時間をおいてから再度お試しください。');
                    return;
                }

                // ----------------------------------------------------
                // 2. その他のサーバーエラー（500系や404など
                // ----------------------------------------------------
                if (!response.ok) {
                    console.error('サーバーエラーが発生しました。');
                    alert('サーバーエラーが発生しました。');

                    return;
                }

                // ----------------------------------------------------
                // 3. 認証成功（200 OK系）
                // ----------------------------------------------------
                const data = await response.json();

                // 画面遷移なし、sentSatusのフラグを返し、ユーザーにメールの確認と登録の完了を促す
                this.sentStatus = data.status;
                return;

            } catch(e) {

                if (timeoutId) clearTimeout(timeoutId);

                // DevToolsにエラー出力
                if (e.name === 'AbortError') {
                    console.error('通信エラー： タイムアウト（５秒）が発生しました。', e);
                    alert('タイムアウトが発生しました。通信環境を確認して再度お試しください。')
                } else {
                    console.error('不明な通信エラー', e);
                    alert('通信エラーが発生しました。')
                }
            }
        },
    }
}
