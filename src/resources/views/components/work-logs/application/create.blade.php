<x-layouts.app>

<x-slot:content>


    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 px-2 place-content-center bg-green-50 min-h-screen ">

    {{ $title }}


    {{ $form }}

{{-- まずフォーム１カ所分つくる --}}
        <x-work-logs.basic-form-section>
            <x-slot:formBody>
                <select x-model="formData.crop_season_id" @change="changeCropSeasons()" name="crop_season_id" class="rounded-md outline-2 outline-gray-600 px-4 m-0.5 text-lg" id="crop_season_id">
                    <option value="">作物を選択</option>
                    <template x-for="cropSeason in allCropSeasons" :key="cropSeason.id">
                        <option :value="cropSeason.id" x-text="cropSeason.crop_season_nameYear"></option>
                    </template>
                </select>
            <x-slot>
        </x-work-logs.create>



    </div>
</x-slot>


</x-layouts.app>

