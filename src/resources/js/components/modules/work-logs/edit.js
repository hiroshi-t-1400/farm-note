// src/resources/js/components/modules/work-logs/edit.js

import { transForm } from "./transformatters";
import { storeFormLogic } from "./form-logic";
import { generateUUID } from "./utils";

export default (config) => {

    const {
        workLog,
        cropSeasons,
        users: allUsers = {},
        materials: allMaterials = {},
    } = config?.initialModels || {};

    const {
        created_at,
        updated_at,
        work_date,

        created_by: createdBy = '',
        updated_by: updatedBy = '',
        title,
        content,
        performed_by: performedBy = [], // arr
        crop_season_id: cropSeasonId = '',
        material: materialLogs = [], // arr
        ...rest
    } = $workLog || {};

    transForm({
        created_at: created_at,
        updated_at: updated_at,
        work_date: work_date,
        status: status,

        cropSeasons: cropSeasons,
    });

    const formData = {
        formDataUuid: generateUUID(),
        draftUuid: '',

        createdBy: createdBy.id,
        cropSeasonId: cropSeasonId,
        title: title,
        workDate: workDate,
        status: status,
        performedBy: performedBy,
        content: content,
        materialLogs: materialLogs,
    };


    return {

        formData,


    }
}
