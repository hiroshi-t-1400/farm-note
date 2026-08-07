

export default (config) => {

    const {
            created_at,
            created_by: createdBy = '',
            updated_at,
            updated_by: updatedBy = '',
            title: title = '',
            work_date,
            status: status = '',
            content: content = '',
            // content,
            performed_by: performedBy = [],
            crop_season,
            material,
            ...rest
    } = config?.initialWorkLog;

    const cropSeason = {
        ...crop_season,
        crop_name: crop_season.crop.name,
        field_name: crop_season.field.name,
        field_notes: crop_season.field.notes
    };

    function tsToDate(ts) {
        const newTS = Date.parse(ts);
        const today = new Date(newTS);

        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');

        return `${yyyy}-${mm}-${dd}`;
    };

    const createdAt = tsToDate(created_at);
    const updatedAt = tsToDate(updated_at);
    const workDate = tsToDate(work_date);

    const materials = material.map(m => ({
        quantity: m.pivot.quantity,
        dilutionRate: m.pivot.dilution_rate + '倍',
        materialAmount: m.pivot.material_amount,

        name: m.name,
        type: m.material_category.label,
        manufacturer: m.manufacturer,
        defaultDilutionRate: m.default_dilution_rate,
        standardSprayVolume: m.standard_spray_volume + 'L',
        unit: m.unit,
    }));

    console.log(config?.initialWorkLog);

    return {
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
