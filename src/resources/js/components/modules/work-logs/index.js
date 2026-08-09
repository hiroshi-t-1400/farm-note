// src/resources/js/components/modules/work-logs/show.js

import { tsToDate } from "./utils";

export default (config) => {

    const debugWorkLog = config?.initialWorkLog;

    const workLog = config?.initialWorkLog.map(log => {
    console.log({'mapのlog': log});

        return {

            ...log,
            createdAt: tsToDate(log?.createdAt) || '',
            updatedAt: tsToDate(log?.updatedAt) || '',
            workDate: tsToDate(log?.workDate) || '',
            url: `/work-logs/show/${log.id}`
        }
    });
    console.log({'mapのlog': workLog});

    const caption = workLog?.[0]?.cropSeason?.cropSeasonsNameYear || '';

    return {

        debugWorkLog,

        workLog,
        caption,

    }
}
