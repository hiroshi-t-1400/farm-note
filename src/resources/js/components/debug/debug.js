// src/resources/js/components/debug/debug.js

export function registerNetworkStore (Alpine) {
    Alpine.store('network', {

        isOnline: (() => {
            const saved = localStorage.getItem('ONLINE_FLAG');
            if (saved !== null) {
                return JSON.parse(saved);
            }
            return window.navigator.onLine;
        })(),

        // init() {
        //     window.addEventListener('online', () => { this.isOnline = true; });
        //     window.addEventListener('offline', () => { this.isOnline = false; });
        // },

        // debug
        toggleOnline() {
            this.isOnline = !this.isOnline;
            this.saveOnlineFlag()
        },

        saveOnlineFlag() {
            localStorage.setItem('ONLINE_FLAG', JSON.stringify(this.isOnline));
        },

        get showOnlineStatus() {
            return this.isOnline ? 'オン' : 'オフ';
        }

    });

}
