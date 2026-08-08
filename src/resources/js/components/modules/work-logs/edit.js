// src/resources/js/components/modules/work-logs/edit.js

import { storeFormLogic } from "./form-logic";
import { generateUUID, objToSnakeCase, strToSnakeCase, tsToDate, networkCtl } from "./utils";


export default (config) => {

    const {workLog, cropSeasons, users, materials, matTypes} = config?.initialModels;

    const targetFormData = {
        ...workLog,
        materialLogs: workLog.material,
        createdAt: tsToDate(workLog.createdAt),
        updatedAt: tsToDate(workLog?.updatedAt),
        workDate: tsToDate(workLog?.workDate),
    };

    return {
        network: networkCtl(),

        ...storeFormLogic({
            cropSeasons,
            users,
            materials,
            matTypes
        }),
        formData: {...targetFormData},


    }
}

