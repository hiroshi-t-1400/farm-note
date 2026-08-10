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

    const caption = workLog?.[0]?.cropSeason?.cropSeasonsNameYear || '';

    const page = pagenation(config?.initialWorkLog['meta']);

    return {

        next: page.next || '',
        prev: page.prev || '',


        debugWorkLog,

        workLog,
        caption,

    }
}
