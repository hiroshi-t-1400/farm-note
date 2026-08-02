export default (config) => {

    return {


        
            // crop_seasonを扱いやすいように変形する
            allCropSeasons: (config.initialCropSeasons || []).map((season, index) => ({
                ...season,
                id: index + 1,
                crop_name: season.crops?.name ?? '',
                crop_season_nameYear: `${season.crops?.name ?? ''}${season.year ?? ''}`,
            })),
    }

}
