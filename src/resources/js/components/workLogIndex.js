export default (config) => {

    return {

        allRecent: (config.initialRecent || []),

        // crop_seasonを扱いやすいように変形する
        allCropSeasons: (config.initialCropSeasons || []).map((season, index) => ({
            ...season,
            id: index + 1,
            crop_name: season.crops?.name ?? '',
            crop_season_nameYear: `${season.crops?.name ?? ''}${season.year ?? ''}`,
        })),

        //
        // @param ts {string}
        tsToDate(ts) {
            const newTS = Date.parse(ts);
            const today = new Date(newTS);

            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const HH = String(today.getHours());
            const MM = String(today.getMinutes());

            return `${yyyy}-${mm}-${dd}-${HH}-${MM}`;
        },
    }

}
