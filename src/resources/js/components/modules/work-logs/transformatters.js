// src/resources/js/components/modules/work-logs/transformatters.js

import { tsToDate } from "./utils"

export function transForm (initialData = {}) {

// const initialData = initialData?.workLog

    if (initialData?.status == 'plan') {
        return status = true;
    } else if(initialData?.status == 'completed') {
        return status = false;
    }

    return {
        // createdBy: initialData.created_by || '',
        // updatedBy: initialData.updated_at || '',
        // title: initialData.title || '',
        // status: initialData.status || '',
        // content: initialData.content || '',
        // performedBy: initialData.performed_by || [],
        // cropSeason: initialData.crop_season || '',
        // materials: initialData.materials || [],

        // crop_seasonを扱いやすいように変形する
        allCropSeasons: (initialData.cropSeasons || []).map((season, index) => ({
            ...season,
            id: index + 1,
            cropNmae: season?.crop?.cropName ?? '',
            // cropName: season.crop?.name ?? '',
            cropSeasonNameYear: `${season?.crop?.cropName ?? ''}${season.year ?? ''}`,
        })),

        // allMaterials: initialData.materials || [],
        // allUsers: initialData.users || [],

        createdAt: tsToDate(initialData?.created_at || ''),
        updatedAt: tsToDate(initialData?.updated_at || ''),
        workDate: tsToDate(initialData?.work_date || ''),

        status: status,

    }
}
