// src/resources/js/components/modules/work-logs/show.js

import { tsToDate } from "./utils";

export default (config) => {

    const debugWorkLog = config?.initialWorkLog;

    const {
        createdBy,
        updatedBy,
        title,
        status,
        content,
        performedBy,
        cropSeason,
        material,
        ...rest
    } = config?.initialWorkLog;

    const createdAt = tsToDate(config?.initialWorkLog?.createdAt);
    const updatedAt = tsToDate(config?.initialWorkLog?.updatedAt);
    const workDate = tsToDate(config?.initialWorkLog?.workDate);

    const materials = material.map((mat, index) => ({
        ...mat,
        indexStr: `資材 ${index + 1}` || '',
        typeLabel: `【${mat.typeLabel}】` || '',
        defaultDilutionRate: `${mat.defaultDilutionRate}倍` || '',
        dilutionRate: `${mat.dilutionRate}倍` || '',
        materialAmount: `${mat.materialAmount}` || ''
        // logs: {
        //     defaultDilutionRate: {label: '使用量', value:`${mat.defaultDilutionRate}倍` || ''},
        //     dilutionRate: {label: '希釈倍率', value:`${mat.dilutionRate}倍` || ''},
        //     materialAmount: {label: '原液量', value:`${mat.materialAmount}倍` || ''}
        // }
    }));

    return {
        debugWorkLog,

        createdAt,
        createdBy,
        updatedAt,
        updatedBy,
        title,
        workDate,
        status,
        content,
        performedBy,
        cropSeason,
        materials,
        rest,

    }
}
