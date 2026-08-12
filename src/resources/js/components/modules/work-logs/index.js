// src/resources/js/components/modules/work-logs/show.js

import { tsToDate, pagenation, generateUUID } from "./utils";
import { deleteWorkLog } from "./delete";

export default (config) => {

    const debugWorkLog = config?.initialWorkLog;

    const workLog = config?.initialWorkLog['data'].map(log => {
        return {
            uuid: generateUUID(),
            ...log,
            createdAt: tsToDate(log?.createdAt) || '',
            updatedAt: tsToDate(log?.updatedAt) || '',
            workDate: tsToDate(log?.workDate) || '',
            url: `/work-logs/show/${log.id}`
        }
    });

    const page = pagenation(config?.initialWorkLog['meta']);
    const pathName = location.pathname;

    return {

        debugWorkLog,

        next: page.next || '',
        prev: page.prev || '',

        workLog,

        get caption () {
            if (pathName == '/work-logs/index/') return '';
            return this.workLog?.[0]?.cropSeason.cropSeasonsNameYear || '';
        },

        // 一覧画面のまま非同期で１件削除
        async deleteLog (ids = '') {
            const mode = 'index';
            const result = await deleteWorkLog(ids, mode);

            const filterd = this.workLog.filter(log => log.id != ids);

            console.log({'削除後のfilterd': filterd});
            this.workLog = [...filterd];
            console.log({'削除後のthis.workLog': this.workLog});

            const hasIds = this.workLog.map(element => {
                element.id;
            });
            console.log({'削除後のthis.workLogが持っている記事のid列挙': hasIds});

            return;
        },


    }
}
