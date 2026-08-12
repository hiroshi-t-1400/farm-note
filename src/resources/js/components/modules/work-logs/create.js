// src/resources/js/components/modules/work-logs/create.js

import { storeFormLogic } from "./form-logic";
import { generateUUID, objToSnakeCase, strToSnakeCase, tsToDate } from "./utils";


export default (config) => {

    const {cropSeasons, users, materials, matTypes} = config?.initialModels;

    return {

        formData: {

        },

        ...storeFormLogic({
            cropSeasons,
            users,
            materials,
            matTypes
        }),

    }
}
