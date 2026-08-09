// src/resources/js/components/modules/work-logs/edit.js

import { storeFormLogic } from "./form-logic";
import { generateUUID, objToSnakeCase, strToSnakeCase, tsToDate } from "./utils";


export default (config) => {

    const {workLog, cropSeasons, users, materials, matTypes} = config?.initialModels;

    const targetFormData = {
        ...workLog,
        materialLogs: workLog.material,
        createdAt: tsToDate(workLog.createdAt),
        updatedAt: tsToDate(workLog?.updatedAt),
        workDate: tsToDate(workLog?.workDate),
    };

    console.log('workLogのid', workLog.id);

    return {

        workLogId: workLog.id,

        ...storeFormLogic({
            cropSeasons,
            users,
            materials,
            matTypes,
            workLog
        }),
        formData: {...targetFormData},


    }
}

