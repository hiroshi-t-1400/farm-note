// src/resources/js/stores/authStore.js

export function registerAuthStore(Alpine) {
    Alpine.store('auth', {
        user: null,
        loading: true, // ロード状態の管理

        setUser(userData) {
            this.user = userData;
        },

        get check() {
            return this.user !== null;
        },

        // --------------------------------
        // ロール判定ヘルパー
        // --------------------------------
        // 引数に渡したロールを持っているか
        hasRole(role) {
            if (!this.check || !this.user.roles) return false;
            // レスポンスで受け取るSpatieのroleは複数割り当てられている場合はオブジェクト配列、roleが１つなら文字列配列、それぞれに対応
            // return this.user.roles.some(r => (typeof r === 'string' ? r : r.name) === role); // gemini
            return this.user.roles.some(r => {
                if (typeof r === 'string') {
                    return r === role;
                } else {
                    return r.name === role;
                }
            })
        },

        isOwner() {
            return this.hasRole('owner');
        },

        isManager() {
            return this.hasRole('manager');
        },

        isWorker() {
            return this.hasRole('worker');
        },

        // --------------------------------
        // パーミッション認可判定ヘルパー
        // --------------------------------
        can(permission, resource = null) {
            if (!this.check) return false;
            // Spatieによる直接パーミッションDirect Permissionsを持っている場合の処理
            // ex) manage work logs
            if (this.user.permissions) {
                const hasDirectPermission = this.user.permissions.some(
                    p => {
                        if (typeof p === 'string') {
                            return p === permission;
                        } else {
                            return p.name === permission;
                        }
                    }
                );
                if (hasDirectPermission) return true;
            }

            // リソースの作成者チェック
            // 作成者であれば操作を認可される箇所に適用
            if (resource) {
                const creatorId = resource?.created_by ?? resource?.user_id ?? resource?.author_id ?? resource?.requested_by ?? resource.requesterId ?? resource.createdBy;

                if (creatorId !== undefined) {
                    if (permission === 'update' || permission === 'delete') {
                        return this.user.id === creatorId;
                    }
                }
            }

            return false;
        },

        // Apiからログインユーザーを取得する初期化メソッド
        async init() {
            try {
                const response = await window.http.get('/user');
                this.setUser(response.data);
                // console.log({'受け取ったuser': this.user});
            } catch (error) {
                // 401未認証などの場合は null をセット
                this.setUser(null);
            } finally {
                this.loading = false;
            }
        },
    })
};
