// src/resources/js/components/modules/dashboard/recent.js

import { tsToDate } from "./utils";

export default (config) => {

    const rawRecent = config?.initialRecent || {};

    const allRecent = rawRecent.map(r => {
        const { createdAt, updatedAt, workDate, ...rest } = r;
        return {
            ...rest,
            createdAt: tsToDate(createdAt, 'Hi'),
            updatedAt: tsToDate(updatedAt, 'Hi'),
            workDate: tsToDate(workDate, 'Hi'),
        }
    })

    const allLogUrl = `${location.origin}/work-logs/index/`;


    return {
        allRecent,
        allLogUrl,
    }

}
