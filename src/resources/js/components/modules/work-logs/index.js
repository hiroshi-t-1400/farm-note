// src/resources/js/components/modules/work-logs/show.js

import { tsToDate, pagenation } from "./utils";

export default (config) => {

    const debugWorkLog = config?.initialWorkLog;

    const workLog = config?.initialWorkLog['data'].map(log => {
        return {

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
            return this.workLog[0].cropSeason.cropSeasonsNameYear;
        },

    }
}
